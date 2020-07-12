<?php 

namespace App\Repositories\Payment;

interface PaymentInterface {

    public function store($value);

    public function getSubscribePlans($value);
}

?>