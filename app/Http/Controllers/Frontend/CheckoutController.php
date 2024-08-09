<?php

namespace App\Http\Controllers\Frontend;

use App\Helper\Response;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkOut(Request $request, $user_id)
    {
        $user_order_items = OrderItem::where('user_id', $user_id)->get();

        if (count($request->cart_ids) > 0) {
            if (count($user_order_items) > 0) {
                OrderItem::where('user_id', $user_id)->delete();
            }
            foreach ($request->cart_ids as $cart_id) {
                OrderItem::create([
                    'user_id' => $user_id,
                    'cart_id' => $cart_id,
                ]);
            }
        }

        return Response::Out("success", "Checkout Confirm!", "", 200);

    }
}
