<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateNameEmailRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')
                 ->except(['confirm', 'store']);
    }

    public function confirm(Request $request)
    {
        RegisterRequest::rules($request, 'users');

        return response()->json([], 200);
    }

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
        UpdateNameEmailRequest::rules($request, $user_id, 'users', $item);

        $item->update($request->all());

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function updatePassword(Request $request, $user_id)
    {
        $item = User::find($user_id);
        UpdatePasswordRequest::rules($request, $item);

        $item->password = Hash::make($request->new_password);
        $item->save();

        return response()->json([
            'data' => $item
        ], 200);
    }

    public function updateImage(Request $request, $user_id)
    {
        $item = User::find($user_id);
        ImageService::deleteImage($item->image_url);

        $url = ImageService::uploadImage($request);
        $item->image_url = $url;
        $item->save();

        return response()->json([
            'data' => $item,
        ], 200);
    }

    public function destroy($user_id)
    {
        User::destroy($user_id);

        return response()->json([], 204);
    }
}
