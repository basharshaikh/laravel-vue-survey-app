<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use App\Models\SurveyQuestion;
use Illuminate\Validation\Rule;
use App\Models\SurveyQestionAnswer;
use Illuminate\Support\Facades\File;
use App\Http\Resources\SurveyResource;
use App\Http\Requests\StoreSurveyRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateSurveyRequest;
use App\Http\Resources\SurveyAnswerResource;
use App\Http\Resources\SurveyWithAnsrResource;
use App\Http\Requests\StoreSurveyAnswerRequest;



class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = $request->user();
        return SurveyResource::collection(Survey::latest()->where('user_id', $user->id)->paginate(6));
        // var_dump($request);

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSurveyRequest  $request
     * @return \Illuminate\Http\Response StoreSurveyRequest $request,
     */
    public function store( StoreSurveyRequest $request )
    {
        // creating survey in db by ::create()

        $data = $request->validated();

        // check and manage image
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }


        $survey = Survey::create($data);

        // Adding questions return $data['questions'];
        foreach($data['questions'] as $question){
            //adding new field survey_id in question arry
            $question['survey_id'] = $survey->id;
            $this->createQuestion($question);
        }




        return new SurveyResource($survey);
        // return $survey;
        // return "WOOOO"; https://prnt.sc/RTGP-B_Rj_qn
        // return $request."af";
        // $user = $request->user();
        // return $request; // returning {"title":"aSfdsa","status":false,"description":null,"image":null,"expire_date":null,"questions":[],"user_id":2}
        // return  $data; //Returning {"title":"sdfsdf","image":null,"user_id":2,"status":false,"description":null,"expire_date":null}
        // return $request->path();

        // return "kjsdfh";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey, Request $request) //$survey is coming from payload req
    {
        //
        $user = $request->user();
        if($user->id !== $survey->user_id) {
            return abort(403, 'Unauthorized action');
        }

        return new SurveyResource($survey);
    }

    /**
     * 
     * 
     * 
     */
    public function surveyForGuest(Survey $survey) 
    {
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSurveyRequest  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        $data = $request->validated();

        // check if image was given and saved on local file system
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;

            // delete here is old img in this survey. delete it
            if($survey->image){
                $absolutePath = public_path($survey->image);
                File::delete($absolutePath);
            }
        }

        // Update survey in db
        $survey->update($data);


        // Get ids as plain array of Existing questions
                                //questions() method is coming from model Survey
        $existingIds = $survey->questions()->pluck('id')->toArray();        
        // return $existingIds; //[5,6,7]

        // Get ids as plain array of new questions
        $newIds = Arr::pluck($data['questions'], 'id');
        // return $newIds;//[5,6,7,8]

        // Find questions to delete
        $toDelete = array_diff($existingIds, $newIds);      

        //Find questions to add
        $toAdd = array_diff($newIds, $existingIds);

        // Delete questions by $toDelete array
        SurveyQuestion::destroy($toDelete); // https://laravel.com/docs/9.x/eloquent#deleting-an-existing-model-by-its-primary-key

        //Create new created questions 
        foreach ($data['questions'] as $question) {
            if(in_array($question['id'], $toAdd)){
                $question['survey_id'] = $survey->id;
                $this->createQuestion($question);
            }
        }

        //Update existing question
        $questionMap = collect($data['questions'])->keyBy('id'); // object wise collecting
        foreach ($survey->questions as $question) {
            if (isset($questionMap[$question->id])) {
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }



        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey, Request $request)
    {
        //
        $user = $request->user();
        if($user->id !== $survey->user_id) {
            return abort(403, 'Unauthorized action');
        }

        $survey->delete();

        if($survey->image){
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
        }
        
        return response('', 204);
    }


    // save image custom method
    private function saveImage($image){
        //check valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            $image = substr($image, strpos($image, ',') + 1);
            $type = strtolower($type[1]); //returning extension

            if(!in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'png'])){
                throw new \Exception('Invalid image type');
            }

            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('Did not match data uri with image data');
        }

        $dir = 'images/';
        $file = Str::random().'.'.$type;
        $absolutePath = public_path($dir);
        $relativePath = $dir.$file;

        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }

        file_put_contents($relativePath, $image);

        return $relativePath;
    }


    // createQuestion($question) method
    private function createQuestion($data){
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data, [
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
            'survey_id' => 'exists:App\models\Survey,id',
        ]);

        return SurveyQuestion::create($validator->validated());
    }

    // updateQuestion method
    private function updateQuestion(SurveyQuestion $question, $data){
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data, [
            'id' => 'exists:App\Models\SurveyQuestion,id',
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
        ]);

        return $question->update($validator->validated());
    }

    // store survey answer
    public function storeAnswer(StoreSurveyAnswerRequest $request, Survey $survey){
        $validated = $request->validated();

        $surveyAnswer = SurveyAnswer::create([
            'survey_id' => $survey->id,
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
        ]);

        // iterate all ans from frontend
        foreach ($validated['answers'] as $questionID => $answer) {
            $question = SurveyQuestion::where([
                'id' => $questionID,
                'survey_id' => $survey->id
            ])->get();

            if(!$question){
                return response('Invalid Question ID: '.$questionID.'', 400);
            }

            $data = [
                'survey_question_id' => $questionID,
                'survey_answer_id' => $surveyAnswer->id,
                // 'survey_id' => $survey->id,
                'answer' => is_array($answer) ? json_encode($answer) : $answer
            ];

            SurveyQestionAnswer::create($data);
        }

        return response("", 201);
    }


    // Get surveys with ans
    public function GetSurveys(Survey $survey){
        $data = $survey->with('questions', 'answers')->paginate(3);

        return $data;
    }

    // Get surveys with ans
    public function GetSurveysWithAns(Survey $survey){
        $id = $survey->id;
        $data = new SurveyWithAnsrResource(Survey::find($id));
        return $data;
    }
}