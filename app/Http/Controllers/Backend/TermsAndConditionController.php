<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;

class TermsAndConditionController extends Controller
{
    function get()
    {
        return TermsAndCondition::first() ?? (object) [];
    }
    function store(Request $request)
    {
        $termsAndConditionId = $request->id;

        TermsAndCondition::updateOrCreate(
            ['id' => $termsAndConditionId],
            ['content' => $request->content]
        );

        return response()->json([
            'msg' => 'Terms and Condition Saved!'
        ], 200);
    }
}
