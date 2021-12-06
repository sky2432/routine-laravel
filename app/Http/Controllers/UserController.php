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
        $user = new User;
        $user->password = Hash::make($request->password);
        $user->image_url = config('const.default_image_url');
        $user->fill($request->all())->save();

        return response()->json([
            'message' => 'Create user successfuly'
        ], 200);
    }

    //名前・メールアドレスの更新
    public function update(Request $request, $user_id)
    {
        $user = User::find($user_id);
        UpdateNameEmailRequest::rules($request, $user_id, 'users', $user);

        $user->update($request->all());

        return response()->json([
            'data' => $user
        ], 200);
    }

    public function updatePassword(Request $request, $user_id)
    {
        $user = User::find($user_id);
        UpdatePasswordRequest::rules($request, $user);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'data' => $user
        ], 200);
    }

    public function updateImage(Request $request, $user_id)
    {
        $user = User::find($user_id);
        ImageService::deleteImage($user->image_url);

        $url = ImageService::uploadImage($request->file('image'));
        $user->image_url = $url;
        $user->save();

        return response()->json([
            'data' => $user,
        ], 200);
    }

    public function destroy($user_id)
    {
        User::destroy($user_id);

        return response()->json([], 204);
    }
}
