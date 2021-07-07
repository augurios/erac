<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use App\Events\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chat');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages($id)
    {
        $msgs = Message::where('case',$id)->with('user')->get();

        foreach($msgs as $caseItem){
                $caseItem['recipient'] = User::where('cedula', $caseItem['recipient'])->get()[0];
        }

        return response()->json([
            'status'  => 'success',
            'message' => $msgs,
        ]); 
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $request->input('message'),
            'recipient' => $request->input('recipient'),
            'case' => $request->input('case'),
            'action' => $request->input('action')
        ]);

        broadcast(new SendMessage($user, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
