<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $item = new User;
        $item->password = Hash::make($request->password);
        $item->fill($request->all())->save();

        return response()->json([
            'data' => $item
        ], 200);
    }

    //名前・メールアドレスの更新
    public function update(Request $request, $user_id)
    {
        $item = User::find($user_id);
        $item->update($request->all());

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function updatePassword(Request $request, $user_id)
    {
        $item = User::find($user_id);
        $item->password = Hash::make($request->new_password);
        $item->save();

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function destroy($user_id)
    {
        User::destroy($user_id);

        return response()->json([], 204);
    }
}
