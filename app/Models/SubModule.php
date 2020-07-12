<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class SubModule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sub_module';  

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

    public function mainmodule()
    {
        return $this->hasOne('App\Models\MainModule', 'id', 'main_module_id');
    }           
}
