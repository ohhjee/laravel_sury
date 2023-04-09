<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
// use App\Models\SurveyQuestion;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'user_id',
        'slug',
        'status',
        'image',
        'description',
        'expire_date'
    ];
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }
    public function questions(){
        return $this->hasMany(SurveyQuestion::class);
    }public function answers(){
        return $this->hasMany(SurveyAnswer::class);
    }
}
