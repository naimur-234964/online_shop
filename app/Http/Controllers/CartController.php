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
                $message = '<strong>'.$product->title .'</strong> has been added in your cart Succesfully!';
                session()->flash('success', $message);
            } else {
                $status =  false;
                $message = $product->title . ' already added in cart!';
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price);
            $status =  true;
            $message = '<strong>'.$product->title .'</strong> has been added in your cart Succesfully!';
            session()->flash('success', $message);
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

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);
        $product = Products::find($itemInfo->id);

        if ($product->track_qty == 'Yes') {
            if ($request->qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Cart updated succesfully';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = 'Requested qty(' . $qty . ') not available in stock';
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Cart updated succesfully';
            $status = true;
            session()->flash('success', $message);
        }


        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
    public function deleteItem(Request $request)
    {

        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null) {
            $errorMessage = "Item not Found in Cart";
            session()->flash('error', $errorMessage);

            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ]);
        } else {
            Cart::remove($request->rowId);
            $message = "Item removed from Cart succesfully";
            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }
    }
}
