<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\InvoiceResource;
use App\Http\Resources\Backend\OrderResource;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function count()
    {
        $count = Invoice::where('is_read', 0)->count();
        return $count;
    }
    function get(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $query = Invoice::select(['id', 'user_id', 'tran_id', 'total', 'order_status', 'is_read', 'payment_method', 'paid_date', 'created_at'])
            ->with([
                'user' => function ($user) {
                    $user->select(['id', 'name', 'email', 'phone_number', 'avatar']);
                }
            ])
            ->when($search, function ($query, $search) {
                $query->where('tran_id', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });

        $query->when($status === "1", function ($query) {
            $query->where('order_status', "pending");
        });

        $query->when($status === "2", function ($query) {
            $query->where('order_status', "processing");
        });

        $query->when($status === "3", function ($query) {
            $query->where('order_status', "on_the_way");
        });

        $query->when($status === "4", function ($query) {
            $query->where('order_status', "delivered");
        });

        $query->when($status === "5", function ($query) {
            $query->where('is_read', 1);
        });

        $query->when($status === "6", function ($query) {
            $query->where('is_read', 0);
        });

        $orders = $query->latest()
            ->get();
        return $orders;
    }
    function show($id)
    {
        $invoice = Invoice::with(['user.shippingInfo', 'orderItems.product.brand', 'orderItems.product.category', 'orderItems.product.sub_category', 'orderItems.product.product_weights', 'orderItems.product.product_sizes', 'orderItems.product.product_size_numbers', 'orderItems.reviews'])->find($id);
        if ($invoice->is_read == 0) {
            $invoice->is_read = 1;
            $invoice->save();
        }
        return [
            'invoice' => new InvoiceResource($invoice),
            'seller' => User::select(['id', 'name', 'email', 'phone_number'])
                ->where('role', 'admin')
                ->first() ?? (object) [],
        ];
    }
    function status($id)
    {
        $invoice = Invoice::find($id);
        if ($invoice->order_status == 'pending') {
            $invoice->order_status = 'processing';
            $invoice->update();
        } elseif ($invoice->order_status == 'processing') {
            $invoice->order_status = 'on_the_way';
            $invoice->update();
        } elseif ($invoice->order_status == 'on_the_way') {
            $invoice->order_status = 'delivered';
            $invoice->paid_date = now();
            $invoice->update();
        }
        //  elseif ($invoice->order_status == 'delivered') {
        //     $invoice->order_status = 'pending';
        //     $invoice->update();
        // }

        $status = $invoice->order_status == "pending" ? "Pending" : ($invoice->order_status == "processing" ? "Processing" : ($invoice->order_status == "on_the_way" ? "On The Way" : "Delivered"));

        return response()->json(['msg' => 'Order status ' . $status . '!'], 200);
    }
    function destory($id)
    {
        $invoice = Invoice::find($id);
        $invoice->delete();
        return response()->json(['msg' => 'Order deleted!'], 200);
    }
}
