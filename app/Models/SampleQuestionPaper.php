<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleQuestionPaper extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sample_question_papers'; 

    public function submodule()
    {
        return $this->hasOne('App\Models\SubModule', 'id', 'sub_module_id');
    }      
}
