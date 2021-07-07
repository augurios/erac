<?php

namespace App\Http\Controllers;
use App\Especialties;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EspecialtiesController extends Controller
{
    public function index()
    {
        $especs = Especialties::all();
        
        return response()->json([
            'status'     => 'success',
            'especialties'     => $especs,
        ]);
    }

    public function create(Request $request) {
        $espec = new Especialties;
        $espec->title = $request->title;
        $espec->save();

        return response()->json([
            'status'     => 'success',
            'especialties'     => $espec,
        ]);
    }

    public function delete($id)
    {
        $espec = Especialties::find($id);
        $espec->delete();

        return $espec;
    }

}



