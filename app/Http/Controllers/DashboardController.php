<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Http\Resources\SurveyResource;
use App\Http\Resources\SurveyAnswerResource;
use App\Http\Resources\SurveyResourceDashboard;

class DashboardController extends Controller
{
    public function index(Request $request){
        $user = $request->user();

        //Total Number of Survey
        $total = Survey::query()->where('user_id', $user->id)->count();
        // $total = Survey::query()->count(); returning total 7 when user id 2 $user->id
        
        //Latest Survey
        $latest = Survey::query()->where('user_id', $user->id)->latest('created_at')->first();
        

        //Total Number of Answer
        $totalAnswers = SurveyAnswer::query()
            ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
            ->where('surveys.user_id', $user->id)
            ->count();

        // Latest 5 answer
        $latestAnswers = SurveyAnswer::query()
            ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
            ->where('surveys.user_id', $user->id)
            ->orderBy('end_date', 'DESC')
            ->limit(5)
            ->getModels('survey_answers.*');

        return [
            'totalSurveys' => $total,
            'latestSurvey' => $latest ? new SurveyResourceDashboard($latest) : null,
            'totalAnswers' => $totalAnswers,
            'latestReturn' => $latest,
            'latestAnswers' => SurveyAnswerResource::collection($latestAnswers)
        ];
    }
}
