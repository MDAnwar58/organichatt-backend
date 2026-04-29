<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\OrderItemsForOrderResource;
use App\Http\Resources\Frontend\OrderResource;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\ShippingInfo;
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
    public function getUserOrderDetails($user_id)
    {
        $order_items = OrderItem::where('user_id', $user_id)
            ->with('cart.product.offers', 'cart.product.brand.offers', 'cart.product.category.offers', 'cart.product.sub_category.offers', 'cart.product.product_weights.weight', 'cart.product.product_sizes.size', 'cart.product.product_size_numbers.size_number')
            ->get();
        $shippingInfo = ShippingInfo::where('user_id', $user_id)->first();
        return [
            'order_items' => OrderItemsForOrderResource::collection($order_items),
            'shipping_info' => $shippingInfo ?? (object) []
        ];
    }
    public function userOrders(Request $request, $user_id)
    {
        $tab = $request->query('tab', 'all order');
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $query = Invoice::with(['orderItems.product.category', 'orderItems.reviews', 'orderItems.weight', 'orderItems.size', 'orderItems.size_number']);

        $query->when($search, function ($q) use ($search) {
            $q->where(function ($query) use ($search) {
                $query->where('tran_id', 'like', '%' . $search . '%')
                    ->orWhereHas('orderItems.product', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        });

        $query->when($tab === "all order", function ($q) use ($user_id, $search) {
            $q->where('user_id', $user_id);
        });
        $query->when($tab !== "all order", function ($q) use ($user_id, $tab, $search) {
            $q->where('user_id', $user_id)
                ->where('order_status', $tab);
        });

        $orderLength = $query->count();

        $orders = $query->latest()->limit($limit)->get();

        return OrderResource::collection($orders)->additional([
            'order_length' => $orderLength,
        ]);
    }
}
