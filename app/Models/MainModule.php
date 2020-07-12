<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class MainModule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'main_module';

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

    public function state()
    {
        return $this->hasOne('App\Models\StateMaster', 'id', 'state_id');
    }

    public function board()
    {
        return $this->hasOne('App\Models\BoardMaster', 'id', 'board_id');
    }

    public function medium()
    {
        return $this->hasOne('App\Models\LanguageMaster', 'id', 'medium_id');
    }                   
}
