<?php

namespace App\Repositories;

use App\Payment;

class PaymentRepository extends CrudRepository
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function store(array $data)
    {
        $data = $this->prepareDate($data);
        return parent::store($data);
    }

    public function update(array $pks, array $data)
    {
        $data = $this->prepareDate($data);
        return parent::update($pks, $data);
    }


}
