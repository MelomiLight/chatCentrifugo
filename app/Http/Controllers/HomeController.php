<?php

namespace App\Http\Controllers;

use App\Actions\CentrifugoAction;
use App\Jobs\SendMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public CentrifugoAction $action;

    public function __construct(CentrifugoAction $action)
    {
        $this->middleware('auth');
        $this->action = $action;
    }

    public function index()
    {
        $user = User::where('id', auth()->id())->select([
            'id', 'name', 'email',
        ])->first();

        return view('home', [
            'user' => $user,
        ]);
    }

    public function messages(): JsonResponse
    {
        $messages = Message::with('user')->get()->append('time');

        return response()->json($messages);
    }

    public function message(Request $request): JsonResponse
    {
        $message = Message::create([
            'user_id' => auth()->id(),
            'text' => $request->get('text'),
        ]);

        $this->action->publish('channel', [
            'id' => $message->id,
            'user_id' => $message->user_id,
            'text' => $message->text,
            'time' =>$message->time,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Message created and job dispatched.",
        ]);
    }
}
