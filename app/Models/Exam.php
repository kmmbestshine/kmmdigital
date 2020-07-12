<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Exam extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exams'; 

    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }        

    public function submodule()
    {
        return $this->hasOne('App\Models\SubModule', 'id', 'sub_module_id');
    }  
    
   	/**
     * Get the phone record associated with the user.
     */
    public function marks()
    {
        return $this->hasOne('App\Models\Mark', 'exam_id', 'id');
    }        
}
