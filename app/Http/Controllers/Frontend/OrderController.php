<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\OrderItemsForOrderResource;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $id = $request->header('id');
        $user = User::find($id);
        return view('pages.order-place.order-place', compact('user'));
    }
    public function getOrderItems($user_id)
    {
        $order_items = OrderItem::where('user_id', $user_id)
            ->with('cart.product.offers', 'cart.product.brand.offers', 'cart.product.category.offers', 'cart.product.sub_category.offers', 'cart.product.product_weights.weight', 'cart.product.product_sizes.size', 'cart.product.product_size_numbers.size_number')
            ->get();
        return OrderItemsForOrderResource::collection($order_items);
    }
}
