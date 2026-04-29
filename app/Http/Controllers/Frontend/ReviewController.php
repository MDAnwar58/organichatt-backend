<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ReviewGetResource;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    function reviews($userId, $orderId, $invoiceProductId)
    {
        $reviews = Review::where('user_id', $userId)
            ->where('invoice_id', $orderId)
            ->where('invoice_product_id', $invoiceProductId)
            ->with('user')
            ->latest()
            ->get();
        return ReviewGetResource::collection($reviews);
    }
    function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'invoice_id' => 'required|exists:invoices,id',
            'invoice_product_id' => 'required|exists:invoice_products,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required',
            'content' => 'nullable'
        ]);

        $review = new Review();
        $review->user_id = $request->user_id;
        $review->invoice_id = $request->invoice_id;
        $review->invoice_product_id = $request->invoice_product_id;
        $review->product_id = $request->product_id;
        $review->rating = $request->rating;
        $review->content = $request->content;
        $review->save();

        return response()->json([
            'msg' => 'Review Sent Sucessfully!'
        ], 200);
    }
}
