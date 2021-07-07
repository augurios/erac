<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAvatarController extends Controller
{
    public function store(Request $request)
    {
        try {
            auth()->user()->update([
                'avatar' => $request->file('avatar')->storeAs('avatars', $request->user()->name . $request->user()->lastname . time() . '.png', ['disk' => 'public']),
            ]);

            return response()->json([
                'status'     => 'success',
                'message'    => 'Imagen de perfil Actualizada',
                'avatar'     => auth()->user()->avatar,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
