<?php

namespace App\Http\Controllers;

use App\Events\StreamAnswer;
use App\Events\StreamOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StreamingController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return view('templates.external.live_streaming', ['type' => 'broadcaster', 'id' => Auth::user()->id]);
        } else {
            $res = [];
            $res["msg"] = "no";
            return view('templates.auth.login', compact('res'));
        }
    }

    public function consumer(Request $request, $streamId)
    {
        return view('templates.external.live_streaming', ['type' => 'consumer', 'streamId' => $streamId, 'id' => Auth::user()->id]);
    }

    public function makeStreamOffer(Request $request)
    {
        $data['broadcaster'] = $request->broadcaster;
        $data['receiver'] = $request->receiver;
        $data['offer'] = $request->offer;

        event(new StreamOffer($data));
    }

    public function makeStreamAnswer(Request $request)
    {
        $data['broadcaster'] = $request->broadcaster;
        $data['answer'] = $request->answer;
        event(new StreamAnswer($data));
    }
}
