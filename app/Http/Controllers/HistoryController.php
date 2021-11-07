<?php

namespace App\Http\Controllers;

use App\Repositories\DepotRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    private $depotRepository;
    private $paymentRepository;

    public function __construct(DepotRepository $depotRepository, PaymentRepository $paymentRepository)
    {
        $this->depotRepository = $depotRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function bank()
    {
        $depots = $this->depotRepository->all(['*'], ['type' => true])->toArray();
        $payments = $this->paymentRepository->all(['*'], [], ['bill.products'], [], -1, -1, ['check_number' => false])->toArray();

        return $this->calculateResult($depots, $payments);

    }

    public function box()
    {
        $depots = $this->depotRepository->all(['*'], ['type' => false])->toArray();
        $payments = $this->paymentRepository->all(['*'], [], ['bill.products'], [], -1, -1, ['check_number' => true])->toArray();

        return $this->calculateResult($depots, $payments);

    }

    private function calculateResult($depots, $payments)
    {
        $result = array_merge($depots, $payments);

        Log::info(print_r($result, true));

        usort($result, function ($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

//        Log::info(print_r($result, true));

        $total = 0;

        foreach ($result as $r) {
            if (isset($r['amount'])) {
                $total += $r['amount'];
            } else {
                $total -= $r['bill']['tax_stamp'];
                Log::info(print_r($r, true));
                Log::info(print_r($r['bill']['products'], true));
                foreach ($r['bill']['products'] as $product) {
                    $total -= $product['purchase']['quantity'] * $product['purchase']['price'];
                }
            }
        }

        return response()->json([
            'history' => $result,
            'total' => $total
        ]);
    }

}
