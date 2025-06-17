<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

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
