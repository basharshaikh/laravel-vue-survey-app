<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Survey extends Model
{
    use HasFactory, Sluggable;

    // types for 
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_SELECT = 'select';
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';

    protected $fillable = [
        'user_id',
        'title',
        'image',
        'slug',
        'status',
        'description',
        'expire_date'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function questions(){
        return $this->hasMany(SurveyQuestion::class);
    }

    public function answers(){
        return $this->hasMany(SurveyAnswer::class);
    }
}
