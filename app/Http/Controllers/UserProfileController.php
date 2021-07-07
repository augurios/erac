<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserProfile;
use App\Profiles;
class UserProfileController extends Controller
{
    public function update(UpdateUserProfile $request)
    {
        auth()->user()->update($request->all());

        if($request->role == 'mediator') {
            $profile = Profiles::where('mediator', $request->cedula)->get();
            if(count($profile) > 0) {
                $profile[0]->especialties = $request->userProfile['especialties'];
                $profile[0]->save();
            } else {
                $profile = new Profiles;
                $profile->mediator = $request->cedula;
                $profile->resume = $request->aboutme;
                $profile->especialties = $request->userProfile['especialties'];
                $profile->save();
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Perfil actualizado.',
        ]);
    }

    public function getProfile($mediator) {
        $Profile = Profiles::where('mediator', $mediator)->get()[0];

        return response()->json([
            'status'     => 'success',
            'Profile'     => $Profile,
        ]);
    }
}
