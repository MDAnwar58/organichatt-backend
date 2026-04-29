<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\InvoiceCreateRequest;
use App\Models\Cart;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\OrderItem;
use App\Models\ShippingInfo;
use DB;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function createInvoice(InvoiceCreateRequest $request, $userId)
    {
        DB::beginTransaction();
        try {
            ShippingInfo::updateOrCreate(
                ['user_id' => $userId],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'city_or_town' => $request->city_or_town,
                    'present_address' => $request->p_address,
                    'zip_code' => $request->zip_code,
                    'address' => $request->address
                ]
            );

            $tranId = uniqid();

            $invoice = new Invoice();
            $invoice->user_id = $userId;
            $invoice->tran_id = $tranId;
            $invoice->total = $request->total_amount;
            $invoice->payment_method = $request->payment_method;
            $invoice->save();

            if ($request->orderProducts) {
                $orderProducts = json_decode($request->orderProducts, true);
                if (count($orderProducts) > 0) {
                    foreach ($orderProducts as $product) {
                        $invoiceProduct = new InvoiceProduct();
                        $invoiceProduct->invoice_id = $invoice->id;
                        $invoiceProduct->product_id = $product['id'];
                        $invoiceProduct->user_id = $userId;
                        $invoiceProduct->qty = $product['qty'];
                        if (!is_null($product['price'])) {
                            $invoiceProduct->sale_price = $product['price'];
                            $invoiceProduct->sale_discount_price = $product['discount_price'];
                        } elseif (!is_null($product['weight_price'])) {
                            $invoiceProduct->weight_price = $product['weight_price'];
                            $invoiceProduct->weight_discount_price = $product['weight_discount_price'];
                        } elseif (!is_null($product['size_price'])) {
                            $invoiceProduct->size_price = $product['size_price'];
                            $invoiceProduct->size_discount_price = $product['size_discount_price'];
                        } else {
                            $invoiceProduct->size_number_price = $product['size_number_price'];
                            $invoiceProduct->size_number_discount_price = $product['size_number_discount_price'];
                        }
                        $invoiceProduct->product_optional_type =
                            $product['weight_id'] !== null ? "weight" :
                            ($product['size_id'] !== null ? "size" :
                                ($product['size_number_id'] !== null ? "size_number" : null));


                        if ($product['weight_id'] !== null) {
                            $invoiceProduct->weight_id = $product['weight_id'];
                        }
                        if ($product['size_id'] !== null) {
                            $invoiceProduct->size_id = $product['size_id'];
                        }
                        if ($product['size_number_id'] !== null) {
                            $invoiceProduct->size_number_id = $product['size_number_id'];
                        }
                        $invoiceProduct->save();

                        //TODO: delete cart in order items
                        if ($product['cart_id'] && $userId && $product['id']) {
                            $cart = Cart::where('id', $product['cart_id'])
                                ->where('user_id', $userId)
                                ->where('product_id', $product['id'])
                                ->first();
                            $cart->delete();

                            $orderItem = OrderItem::where('user_id', $userId)
                                ->where('id', $product['cart_id'])
                                ->first();
                            if ($orderItem) {
                                $orderItem->delete();
                            }
                        }
                    }
                }
            }

            $order = Invoice::select(['id', 'user_id', 'tran_id', 'total', 'order_status', 'is_read', 'payment_method', 'paid_date', 'created_at'])
                ->where('id', $invoice->id)
                ->with([
                    'user' => function ($user) {
                        $user->select(['id', 'name', 'email', 'phone_number', 'avatar']);
                    }
                ])->first();

            DB::commit();

            return response()->json([
                'msg' => 'Your Order Successfully Created!',
                'order' => $order
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
