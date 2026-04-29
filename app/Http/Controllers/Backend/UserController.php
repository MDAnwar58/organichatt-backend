<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function get(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $query = User::query();

        $permission = $status === "1" ? "allow" : ($status === "2"
            ? "reject" : "");

        $role = $status === "3"
            ? "admin" : ($status === "4"
                ? "employee" : ($status === "5"
                    ? "user" : ""));

        $query->when($permission !== "", function ($query) use ($permission) {
            $query->where('permission', $permission);
        });

        $query->when($role !== "", function ($query) use ($role) {
            $query->where('role', $role);
        });

        $query->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone_number', 'LIKE', "%{$search}%");
            });
        });

        $users = $query->latest()->get();
        return $users;
    }
    function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|unique:users,phone_number',
            'role' => 'required|string',
            'password' => 'required|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->role = $request->role;
        $user->password = bcrypt($request->password);
        if ($request->hasFile('avatar')) {
            $name = time() . uniqid(16, true);
            $file = $request->file('avatar');
            $fileExtension = $file->getClientOriginalExtension();
            $filename = $name . "." . $fileExtension;
            $file->move('upload/images/users/', $filename);
            $path = url('/') . '/upload/images/users/' . $filename;
            $user->avatar = $path;
        }
        $user->save();

        $role = $user->role === "employee"
            ? "Employee"
            : ($user->role === "admin"
                ? "Admin"
                : "User");

        return response()->json(['msg', $role . ' Created!'], 200);
    }
    function permission($id)
    {
        $user = User::find($id);
        if ($user->permission === "allow") {
            $user->permission = "reject";
            $user->update();
        } else {
            $user->permission = "allow";
            $user->update();
        }

        $permission = $user->permission === "allow"
            ? "Allow"
            : "Reject";
        return response()->json([
            'msg' => "User Permission " . $permission . "!"
        ], 200);
    }
    function edit($id)
    {
        return User::find($id);
    }
    function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'required|string|unique:users,phone_number,' . $id,
            'role' => 'required|string',
        ]);

        if ($request->input('password')) {
            $request->validate([
                'password' => 'required|confirmed',
            ]);
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->role = $request->role;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->update();

        $role = $user->role === "employee"
            ? "Employee"
            : ($user->role === "admin"
                ? "Admin"
                : "User");

        return response()->json(['msg', $role . ' Updated!'], 200);
    }
    function destroy($id)
    {
        User::destroy($id);
        return response()->json(['msg', 'User Deleted!'], 200);
    }
}
