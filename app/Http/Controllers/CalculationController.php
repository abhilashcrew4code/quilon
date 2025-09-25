<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    //

    public function profitCalculation()
    {
        return view('calculation.profit.index');
    }
}
