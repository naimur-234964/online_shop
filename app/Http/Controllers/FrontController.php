<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {

        $products = Products::where('is_featured', 'Yes')
            ->orderBy('id', 'DESC')
            ->take(8)
            ->where('status', 1)
            ->get();
        $data['featuredProducts'] = $products;

        $latestProducts = Products::orderBy('id', 'DESC')
            ->where('status', 1)
            ->take(8)
            ->get();
        $data['latestProducts'] = $latestProducts;

        return view('front.home', $data);
    }
}
