<?php

use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = \App\Product::select('id as product_id')->get()->toArray();

        factory(\App\Bill::class, 15)->create()
            ->each(function ($bill) use ($products) {
                if ((rand(0, 1) && $bill->provision) || !$bill->provision)
                    $bill->payment()->save(factory(\App\Payment::class)->make());
//                $nbProducts = rand(1, count($products));
                $bill->products()->attach(array_slice($products, rand(0, count($products))), ['price' => rand(1, 100), 'quantity' => rand(1, 10)]);
            });
    }
}
