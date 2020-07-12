<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscribePlanMaster extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscribe_plan_master';  

    public function mainmodule()
    {
        return $this->hasOne('App\Models\MainModule', 'id', 'main_module_id');
    }    
}
