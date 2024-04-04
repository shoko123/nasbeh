<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;

class PermissionController extends Controller
{
    public function __construct()
    {
    }

    public function me()
    {
        //dd("ME");
        $user = auth()->user();
        $me = User::findOrFail($user->id);

        return response()->json([
            'name' => $user->name,
            'id' => $user->id,
            'is_verified' => ! ($user->email_verified_at === null),
            'permissions' => $me->getAllPermissions()->pluck('name'),
        ], 200);
    }
}
