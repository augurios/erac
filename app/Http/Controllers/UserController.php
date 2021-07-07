<?php

namespace App\Http\Controllers;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $users->load('roles');
        
        return response()->json([
            'status'     => 'success',
            'users'     => $users,
        ]);
    }
}
