<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getProduct($id)
    {
        $data = Product::find($id);
        return response()->json($data);
    }
}
