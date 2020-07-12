<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class DiscussionQuestions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'discussion_questions';

    // use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    // public function sluggable()
    // {
    //     return [
    //         'slug' => [
    //             'source' => 'question'
    //         ]
    //     ];
    // }       
}
