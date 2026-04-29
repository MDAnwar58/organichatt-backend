<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\AdminReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;

class OrderReviewController extends Controller
{
    function get(Request $request)
    {
        $reviews = Review::with('user', 'product')
            ->latest()
            ->get();
        return AdminReviewResource::collection($reviews);
    }
    function destory($id)
    {
        Review::destroy($id);

        return response()->json([
            'msg' => 'Review Deleted!'
        ]);
    }
    function count()
    {
        return Review::where('is_read', false)->count();
    }
}
