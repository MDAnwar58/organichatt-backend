<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    function get()
    {
        return PrivacyPolicy::first() ?? (object) [];
    }
    function store(Request $request)
    {
        $privacyPolicyId = $request->id;

        PrivacyPolicy::updateOrCreate(
            ['id' => $privacyPolicyId],
            ['content' => $request->content]
        );

        return response()->json([
            'msg' => 'Privacy policy Saved!'
        ], 200);
    }
}
