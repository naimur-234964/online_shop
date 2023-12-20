<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Products::find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found',
            ]);
        }
        if (Cart::count() > 0) {
            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }

            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price);

                $status =  true;
                $message = $product->title . ' added in cart';

            } else {
                $status =  false;
                $message = $product->title . ' already added in cart';
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price);
            $status =  true;
            $message = $product->title . ' added in cart';
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);

        // Cart::add('293ad', 'Product 1', 1, 9.99);
    }
    public function cart()
    {
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }
}
