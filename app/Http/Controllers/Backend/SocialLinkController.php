<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    function get()
    {
        return SocialLink::oldest()->get();
    }
    function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'link' => 'required'
        ]);

        $socialLink = new SocialLink();
        $socialLink->title = $request->title;
        $socialLink->link = $request->link;
        $socialLink->save();

        return response()->json([
            'msg' => 'Social Link Created!'
        ], 200);
    }
    function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'link' => 'required'
        ]);

        $socialLink = SocialLink::find($id);
        $socialLink->title = $request->title;
        $socialLink->link = $request->link;
        $socialLink->save();

        return response()->json([
            'msg' => 'Social Link Updated!'
        ], 200);
    }
    function destroy($id)
    {
        SocialLink::destroy($id);

        return response()->json([
            'msg' => 'Social Link Deleted!'
        ], 200);
    }
}
