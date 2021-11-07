<?php

namespace App\Repositories;

use App\Bill;
use Exception;
use Illuminate\Support\Facades\Log;

class BillRepository extends CrudRepository
{
    public function __construct(Bill $model)
    {
        parent::__construct($model);
    }

    public function store(array $data)
    {
        Log::info(print_r($data, true));
        $data = $this->prepareDate($data);
        $bill = parent::store($data);
        foreach ($data['products'] as $product) {
            $bill->products()->attach($product['product_id'], ['quantity' => $product['quantity'], 'price' => $product['price']]);
        }

        return $bill;
    }

    public function update(array $pks, array $data)
    {
        Log::info(print_r($data, true));
        $data = $this->prepareDate($data);
        $bill = parent::update($pks, $data);

        $bill->products()->detach();
        foreach ($data['products'] as $product) {
            $bill->products()->attach($product['product_id'], ['quantity' => $product['quantity'], 'price' => $product['price']]);
        }
        return parent::update($pks, $data);
    }

    protected function prepareDate(array $data)
    {
        if (isset($data['date']) && $data['date'] != null) {
            $data['date'] = explode('T', $data['date'])[0];
        }
        if (isset($data['deadline']) && $data['deadline'] != null) {
            $data['deadline'] = explode('T', $data['deadline'])[0];
        }
        return $data;
    }
}