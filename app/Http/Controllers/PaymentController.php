<?php

namespace App\Http\Controllers;

use App\Payment;

use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;

class PaymentController extends CrudController
{
    public function __construct(PaymentRepository $repository)
    {
        $relations = ['bill.provider', 'bill.products'];
        $orderBy = ['date' => 'desc'];
        parent::__construct($repository, $relations, $orderBy);
    }

    public function cash(Request $request)
    {
        $this->nullConditions = ['check_number' => true];
        return parent::index($request);
    }

    public function check(Request $request)
    {
        $this->nullConditions = ['check_number' => false];
        return parent::index($request);
    }
    //public function check(Request $request)
    //{
    //Payment::whereNotNull('check_number')->get();;

    //return parent::index($request);

    //}
    //public function cash(Request $request)
    //{
    //Payment::whereNull('check_number')->get();;


    //return parent::index($request);

    //}
}
