<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQestionAnswer extends Model
{
    use HasFactory;
    protected $fillable = ['survey_id', 'survey_question_id', 'survey_answer_id', 'answer'];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
