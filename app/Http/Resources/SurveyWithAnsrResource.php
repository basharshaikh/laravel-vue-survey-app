<?php

namespace App\Http\Resources;

use DateTime;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyQestionAnswer;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use App\Http\Resources\SurveyAnsResource;
use App\Http\Resources\SurveyAnswerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyWithAnsrResource extends JsonResource
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
            'id' => $this->id,
            'image_url' => $this->image ? URL::to($this->image) : null,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status !== 'draft',
            'description' => $this->description,
            'created_at' => (new DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated_at' => (new DateTime($this->updated_at))->format('Y-m-d H:i:s'),
            'expire_date' => $this->expire_date,
            'questions' => $this->questions,
            'answers' => SurveyAnsResource::collection($this->answers)
        ];
    }
}
