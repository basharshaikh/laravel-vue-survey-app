<?php

namespace App\Http\Resources;
use DateTime;
use App\Models\SurveyQestionAnswer;
use App\Http\Resources\SurveyQAResourse;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyAnsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id, // individual submit
            'answers' => SurveyQestionAnswer::get()->where('survey_answer_id', $this->id)
        ];
    }
}
