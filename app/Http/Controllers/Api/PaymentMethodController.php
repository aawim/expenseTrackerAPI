<?php

namespace App\Http\Controllers\API;

use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $methods = PaymentMethod::select('id', 'name')->orderBy('name')->get();
        return response()->json(['data' => $methods]);
    }

    
}
