<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $item = User::where('email', $request->email)->first();
        LoginRequest::rules($request, $item, 'users');
        if ($request->email === config('const.GUEST_EMAIL.USER')) {
            $role = 'guest';
        } else {
            $role = 'user';
        }

        return response()->json([
                'auth' => true,
                'role' => $role,
                'data' => $item,
            ], 200);
    }

    public function logout()
    {
        return response()->json([
                'auth' => false,
            ], 200);
    }
}
