<?php

namespace App\Http\Controllers;
use App\Cases;
use App\User;
use App\Profiles;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\SendMessage;
use Illuminate\Http\Request;

class CasesController extends Controller
{
    public function index() {
        $cases = Cases::all();
        
        return response()->json([
            'status'     => 'success',
            'cases'     => $cases,
        ]);
    }

    public function getall($cedula) {
        $cases = Cases::where('opener', $cedula)
                        ->orWhere('recipient', $cedula)
                        ->orWhere('mediator', $cedula)
                        ->get();

        foreach($cases as $caseItem){
            if(!is_null($caseItem['mediator'])) {
                $caseItem['mediator'] = User::where('cedula', $caseItem['mediator'])->get()[0];
            }

            $caseItem['opener'] = User::where('cedula', $caseItem['opener'])->get()[0];
            
            if($caseItem['status'] != 'waiting' && $caseItem['status'] != 'reviewing') {                
                $caseItem['recipient'] = User::where('cedula', $caseItem['recipient'])->get()[0];
            } else if($caseItem['status'] == 'waiting' || $caseItem['status'] == 'reviewing') {
                $caseItem['recipient'] = json_decode($caseItem->initialData);
            }
        }
        return response()->json([
            'status'     => 'success',
            'cases'     => $cases,
        ]);
    }

    public function getSingle($id) {
        $case = Cases::where('id', $id)->get();

        if($case[0]['status'] == 'reviewing' || $case[0]['status'] == 'waiting') {
            $case[0]['mediator'] = User::where('cedula', $case[0]['mediator'])->get()[0];
            $case[0]['opener'] = User::where('cedula', $case[0]['opener'])->get()[0];
            $case[0]['recipient'] = json_decode($case[0]->initialData);
        } else if($case[0]['status'] == 'active' || $case[0]['status'] == 'done') {
            $case[0]['mediator'] = User::where('cedula', $case[0]['mediator'])->get()[0];
            $case[0]['opener'] = User::where('cedula', $case[0]['opener'])->get()[0];
            $case[0]['recipient'] = User::where('cedula', $case[0]['recipient'])->get()[0];
        } else {
            $case[0]['opener'] = User::where('cedula', $case[0]['opener'])->get()[0];
        }

        return response()->json([
            'status' => 'success',
            'case' => $case[0],
        ]);
    }

    public function editSingle(Request $request, $id) {
        $case = Cases::find($id);
        $case->amount = $request->amount;
        $case->agreement = $request->agreement;
        $case->save();

        if($case['status'] != 'waiting') {
            $case['mediator'] = User::where('cedula', $case['mediator'])->get()[0];
            $case['opener'] = User::where('cedula', $case['opener'])->get()[0];
            $case['recipient'] = User::where('cedula', $case['recipient'])->get()[0];
        } else if($case['status'] == 'waiting') {
            $case['opener'] = User::where('cedula', $case['opener'])->get()[0];
        }

        return response()->json([
            'status'     => 'success',
            'case'     => $case,
        ]);
    }

    public function editNotes(Request $request, $id) {
        $case = Cases::find($id);
        $case->notes = $request->notes;
        $case->save();

        if($case['status'] != 'waiting') {
            $case['mediator'] = User::where('cedula', $case['mediator'])->get()[0];
            $case['opener'] = User::where('cedula', $case['opener'])->get()[0];
            $case['recipient'] = User::where('cedula', $case['recipient'])->get()[0];
        } else if($case['status'] == 'waiting') {
            $case['opener'] = User::where('cedula', $case['opener'])->get()[0];
        }

        return response()->json([
            'status'     => 'success',
            'case'     => $case,
        ]);
    }

    public function create(Request $request) {
        $user = Auth::user();

        $case = new Cases;
        $case->status = 'reviewing';
        $case->amount = $request->amount;
        $case->description = $request->conflict;
        $case->agreement = $request->solution;
        $case->opener = $user->cedula;
        $case->recipient = $request->cedula;
        $case->initialData = $request->initialData;
        

        $matchingProfiles = Profiles::where('especialties', $request->selectedEspeciality)->get();

        if(count($matchingProfiles) > 0) {
            $case->mediator = $this->getUserByAvailability($matchingProfiles);
        } else {
            $allProfiles = Profiles::all();
            
            if(count($allProfiles) > 0) {
                $case->mediator = $this->getUserByAvailability($allProfiles);
            } else {
                $newMediator =  User::whereHas("roles", function($q){ $q->where("name", "mediator"); })->get()[0];
                $case->mediator =  $newMediator->cedula;
            }
        }
        $case->save();

        return response()->json([
            'status'     => 'success',
            'case'     => $case,
        ]);
    }

    public function approveCase(Request $request, $id) {

        $case = Cases::find($id);
        $case->status = 'waiting';
        $case->save();

        if($case['status'] == 'waiting') {
            $case['mediator'] = User::where('cedula', $case['mediator'])->get()[0];
            $case['opener'] = User::where('cedula', $case['opener'])->get()[0];
            $case['recipient'] = json_decode($case->initialData);;
        }

        return response()->json([
            'status'     => 'success',
            'case'     => $case,
        ]);
    }

    public function initCase(Request $request, $id) {

        $case = Cases::find($id);
        $case->status = 'active';
        $case->accepted = true;
        $case->dateAcepted = now()->timestamp;
        $case->save();

        if($case['status'] != 'waiting') {
            $case['mediator'] = User::where('cedula', $case['mediator'])->get()[0];
            $case['opener'] = User::where('cedula', $case['opener'])->get()[0];
            $case['recipient'] = User::where('cedula', $case['recipient'])->get()[0];
        }

        return response()->json([
            'status'     => 'success',
            'case'     => $case,
        ]);
    }

    public function caseResolve(Request $request, $id) {

        $case = Cases::find($id);
        $case->status = 'done';
        $case->resolution = $request->resolution;
        $case->save();

        if($case['status'] != 'waiting') {
            $case['mediator'] = User::where('cedula', $case['mediator'])->get()[0];;
            $case['opener'] = User::where('cedula', $case['opener'])->get()[0];
            $case['recipient'] = User::where('cedula', $case['recipient'])->get()[0];
        }
        $user = Auth::user();
        $message = $user->messages()->create([
            'message' => '',
            'recipient' => $request->input('resolution') == 'acepted' ? $case['recipient']->cedula : $case['opener']->cedula,
            'case' => $request->input('id'),
            'action' => $request->input('resolution')
        ]);

        broadcast(new SendMessage($user, $message))->toOthers();

        return response()->json([
            'status'     => 'success',
            'case'     => $case,
        ]);
    }

    public function delete($id) {
        $case = Cases::find($id);
        $case->delete();

        return $case;
    }

    public function invertSingle(Request $request, $id) {
        $case = Cases::find($id);

        if($case->inverted) {
            $case->inverted = false;
        } else {
            $case->inverted = true;
            $case->amount = $request->amount;
            $case->counter_agreement = $request->agreement;
            $case->counter_date = now();
        }

        $case->save();

        if($case['status'] != 'waiting') {
            $case['mediator'] = User::where('cedula', $case['mediator'])->get()[0];
            $case['opener'] = User::where('cedula', $case['opener'])->get()[0];
            $case['recipient'] = User::where('cedula', $case['recipient'])->get()[0];
        } else if($case['status'] == 'waiting') {
            $case['opener'] = User::where('cedula', $case['opener'])->get()[0];
        }

        return response()->json([
            'status'     => 'success',
            'case'     => $case,
        ]);
    }

    private function getUserByAvailability($users) {
        $caseCount = 0;
        $selectedUser = 0;

        foreach ($users as $profile) {
            $totalCases = Cases::where('mediator', $profile->mediator)->where('status', 'active')->get();
            if($caseCount === 0) {
                $caseCount = count($totalCases);
                $selectedUser = $profile->mediator;
            } else if(count($totalCases) > $caseCount) {
                $caseCount = count($totalCases);
                $selectedUser = $profile->mediator;
            }
        } 
        return $selectedUser;   
    }
}



