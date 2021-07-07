<?php

namespace App\Http\Controllers;

use App\User;

class PublicProfileController extends Controller
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function index( $userId )
    {
        $user = $this->model->where('id', $userId)->select(['name', 'avatar'])->first();

        return response()->json([
            'status'  => 'success',
            'message' => $user,
        ]);
    }
}
