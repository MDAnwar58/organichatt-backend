<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ShippingInfo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\ProfileUpdateRequest;

class ProfileController extends Controller
{
    function get($id)
    {
        $user = User::find($id);
        $shippingInfo = $user->shippingInfo()->where('user_id', $id)->first();
        return [
            'user' => $user,
            'shippingInfo' => $shippingInfo ?? (object) []
        ];
    }
    function update(ProfileUpdateRequest $request, $id)
    {
        $exist_phone_number = User::where('phone_number', $request->phone_number)->first();
        $user = User::find($id);

        if ($exist_phone_number && $user->phone_number === "") {
            $request->validate([
                'phone_number' => 'unique:users,phone_number'
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        if ($request->hasFile('avatar')) {
            $user->avatar = $request->avatar;
            $file = $request->file('avatar');
            $name = uniqid();
            $fileExtension = $file->getClientOriginalExtension();
            $filename = $name . "." . $fileExtension;
            $file->move('upload/images/profile/', $filename);
            $path = url('/') . '/upload/images/profile/' . $filename;
            $user->avatar = $path;
        }
        $user->update();

        $emailunique = $user->shippingInfo()->where('email', $request->s_email)->first();
        $exist_shipping_phone_number = ShippingInfo::where('phone', $request->s_phone)->first();
        if ($exist_shipping_phone_number) {
            $request->validate([
                's_phone' => 'unique:shipping_infos,phone'
            ], [
                's_phone.unique' => 'The shipping phone number has already been taken.'
            ]);
        }
        if ($emailunique) {
            $request->validate([
                's_email' => 'nullable|email|exists:shipping_infos,email',
            ]);
        } else {
            $request->validate([
                's_email' => 'nullable|email|unique:shipping_infos,email',
            ]);
        }



        $user->shippingInfo()->updateOrCreate(['user_id' => $id], [
            'name' => $request->s_f_name,
            'email' => $request->s_email,
            'phone' => $request->s_phone,
            'city_or_town' => $request->s_city_or_town,
            'present_address' => $request->s_p_address,
            'zip_code' => $request->zip_code,
            'address' => $request->s_address,
        ]);

        return response()->json([
            'msg' => 'Profile Updated!'
        ]);
    }
}
