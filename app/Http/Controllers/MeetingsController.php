<?php

namespace App\Http\Controllers;
use App\Meetings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MeetingsController extends Controller
{
    public function index()
    {
        $cases = Meetings::all();
        
        return response()->json([
            'status'     => 'success',
            'cases'     => $cases,
        ]);
    }

    public function getall($id) {
        $meetings = Meetings::where('case_id', $id)->get();

        
        return response()->json([
            'status'     => 'success',
            'meetings'     => $meetings,
        ]);
    }

    public function editSingle(Request $request, $id) {
        $meeting = Meetings::find($id);
        $meeting->meeting_date = $request->selectedDate;
        $meeting->save();

        return response()->json([
            'status'     => 'success',
            'meeting'     => $meeting,
        ]);
    }

    public function create(Request $request) {
        $user = Auth::user();

        $meeting = new Meetings;
        $meeting->meeting_date = $request->selectedDate;
        $meeting->case_id = $request->caseId;
        $meeting->mediator_id = $request->mediatorId;
        $meeting->save();

        return response()->json([
            'status'     => 'success',
            'meeting'     => $meeting,
        ]);
    }

    public function delete($id)
    {
        $meeting = Meetings::find($id);
        $meeting->delete();

        return $meeting;
    }

}



