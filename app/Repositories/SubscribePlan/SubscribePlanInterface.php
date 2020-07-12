<?php 

namespace App\Repositories\SubscribePlan;

interface SubscribePlanInterface {

    public function getSubscribePlans();
    
    public function getActivatedMainModules();

    public function store($value);
}

?>