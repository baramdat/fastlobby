<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\videoChatNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Site;
use Illuminate\Support\Str;
use App\Models\VideoChatRoom;
use Exception;
use FFMpeg\Filters\Video\VideoFilters;

class VideoRoomsController extends Controller
{

    public function index()
    {
        $rooms = [];
        try {
            $token = config('services.twilio.token');
            $sid = config('services.twilio.sid');
            $client = new Client($sid, $token);
            $allRooms = $client->video->rooms->read([]);

            $rooms = array_map(function ($room) {
                return $room->uniqueName;
            }, $allRooms);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return view('templates.chat.video_chat', ['rooms' => $rooms, 'accessToken' => $token]);
    }

    //video chat compose page
    public function videoChatCompose()
    {
        $user = Auth::user();
        $site = Site::where('id', $user->site_id)->first();
        $users = [];
        $rooms = [];
        if ($user->hasRole('Admin')) {
            $all = User::whereHas('roles', function ($q) {
                $q->where('name', 'BuildingAdmin')->orWhere('name', 'Integrator');
            })->get();

            if (isset($all) && sizeof($all) > 0) {

                foreach ($all as $single) {

                    $users[] = User::find($single->id);
                }
            }
        } elseif ($user->hasRole('Integrator')) {


            $Integrators = User::whereHas('roles', function ($q) {
                $q->where('name', 'Admin');
            })->get();

            if (isset($Integrators) && sizeof($Integrators) > 0) {

                foreach ($Integrators as $value) {
                    $u = User::find($value->id);
                    if (!empty($u)) {
                        $users[] = $u;
                    }
                }
            }
            $buildingAdmins = User::whereHas('roles', function ($q) {
                $q->where('name', 'BuildingAdmin');
            })->where('parent_id', $user->id)->get();

            if (isset($buildingAdmins) && sizeof($buildingAdmins) > 0) {

                foreach ($buildingAdmins as $buildingAdmin) {

                    $us = User::where('id', $buildingAdmin->id)->first();

                    if (!empty($us)) {

                        $users[] = $us;
                    }
                }
            }
        } elseif ($user->hasRole("BuildingAdmin")) {

            $BuildingAdmins = User::whereHas('roles', function ($q) {

                $q->where('name', 'Integrator');
            })->where('id', $user->parent_id)->get();


            if (isset($BuildingAdmins) && sizeof($BuildingAdmins) > 0) {

                foreach ($BuildingAdmins as $value) {

                    $users[] = User::find($value->id);
                }
            }

            $Admins = User::whereHas('roles', function ($q) {

                $q->where('name', 'Admin');
            })->get();

            if (isset($Admins) && sizeof($Admins) > 0) {

                foreach ($Admins as $value) {

                    $users[] = User::find($value->id);
                }
            }

            $Tenants = User::whereHas('roles', function ($q) {

                $q->where('name', 'Tenant');
            })->where('parent_id', $user->id)->get();

            if (isset($Tenants) && sizeof($Tenants) > 0) {

                foreach ($Tenants as $value) {

                    $users[] = User::find($value->id);
                }
            }
        } elseif ($user->hasRole("Tenant")) {



            $us = User::whereHas('roles', function ($q) {

                $q->where('name', 'BuildingAdmin');
            })->where('id', $user->parent_id)->first();

            if ($us) {

                $users[] = $us;
            }

            $Employees = User::whereHas('roles', function ($q) {

                $q->where('name', 'Employee');
            })->where('parent_id', $user->id)->get();

            if (isset($Employees) && sizeof($Employees) > 0) {

                foreach ($Employees as $value) {

                    $users[] = User::find($value->id);
                }
            }


            $f = User::whereHas('roles', function ($q) {

                $q->where('name', 'Integrator');
            })->where('site_id', $site->id)->first();

            if ($f) {

                $users[] = $f;
            }
        } elseif ($user->hasRole("Employee")) {


            $users[] = User::whereHas('roles', function ($q) {

                $q->where('name', 'Tenant');
            })->where('id', $user->parent_id)->first();
        }
        
        $token = config('services.twilio.token');
        $sid = config('services.twilio.sid');
        $client = new Client($sid, $token);       
        return [$client->video->rooms->read([])];
        $allRooms = $client->video->rooms->read([]);
        $rooms = array_map(function ($room) {
            return $room->uniqueName;
        }, $allRooms);
        return view('templates/chat/compose_video_chat', ['users' => $users, 'rooms' => $rooms]);
    }

    //Room built method
    public function RoomBuilt(Request $request, $id)
    {
        try {
            $receiver_status = "";
            $sender = Auth::user();
            $receiver = User::where('id', $id)->first();
            // if ($sender->chat_status == "available") {
            //     DB::table('users')->where('id', $sender->id)->update(["chat_status" => "busy"]);
            // }
            // if ($receiver->chat_status == "available") {
            //     $receiver_status = "available";
            // } else {
            //     $receiver_status = "busy";
            // }

            // if ($receiver_status == "available") {

                $room = '';
                $room = VideoChatRoom::where('user_one', Auth::user()->id)->where('user_two', $id)->orWhere('user_one', $id)->where('user_two', Auth::user()->id)->first();

                if (!empty($room)) {
                    $roomName = $room->room_name;
                } else {
                    $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
                    $roomName = "room" . Auth::user()->id . $id;
                    $client->video->rooms->create([
                        'uniqueName' => "'.$roomName.'",
                        'type' => 'group',
                        'recordParticipantsOnConnect' => false
                    ]);
                    $room = new VideoChatRoom();
                    $user_one = Auth::user();
                    $user_two = User::where('id', $id)->first();
                    $room->room_name = $roomName;
                    $room->user_one = $user_one->id;
                    $room->user_two = $user_two->id;
                    $room->save();
                }
                if ($room) {

                    $message = VideoChatRoom::find($room->id);
                    $notifyable = User::where('id', $id)->first();
                    $sender =  User::where('id', Auth::user()->id)->first();
                    Notification::send($notifyable, new videoChatNotification($sender, $message));
                    $url = env('APP_URL') . '/room/join/' . $roomName;
                    DB::table('users')->where('id', $sender->id)->update(["chat_status" => "busy"]);
                    return response()->json(['status' => 'success', 'room' => $room, 'url' => $url]);
                } else {
                    return response()->json(['status' => "fail", 'msg' => 'No room found!']);
                }
            // } else {

            //     return response()->json(['status' => "fail", 'msg' => 'Participant is not available yet']);
            // }
        } catch (Exception $e) {
            return response()->json(['status' => "fail", 'msg' => $e->getMessage()]);
        }
    }

    public function createRoom(Request $request)
    {
        $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));

        $exists = $client->video->rooms->read(['uniqueName' => $request->roomName]);

        if (empty($exists)) {
            $client->video->rooms->create([
                'uniqueName' => $request->roomName,
                'type' => 'group',
                'recordParticipantsOnConnect' => false
            ]);

            Log::debug("created new room: " . $request->roomName);
        }

        $this->joinRoom($request->roomName);

        //return redirect()->action('VideoRoomsController@joinRoom', ['roomName' => $request->roomName]);
    }

    public function joinRoom($roomName)
    {
        // A unique identifier for this user
        $identity = Auth::user()->first_name;

        Log::debug("joined with identity: $identity");
        $token = new AccessToken(config('services.twilio.sid'), config('services.twilio.key'), config('services.twilio.secret'), 3600, $identity);

        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($roomName);

        $token->addGrant($videoGrant);
        $room = VideoChatRoom::where('room_name', $roomName)->first();
        $user_one = User::where('id', $room->user_one)->first();
        $user_two = User::where('id', $room->user_two)->first();
        DB::table('users')->where('id', Auth::user()->id)->update(["chat_status" => "busy"]);
        return view('templates.chat.video_chat_room', ['accessToken' => $token->toJWT(), 'roomName' => $roomName, 'user_one' => $user_one->id, 'user_two' => $user_two->id]);
    }

    public function generateUniqueName()
    {
        do {
            $randomString = "room" . Str::random(15);
        } while (VideoChatRoom::where("room_name", "=", $randomString)->first());
        return $randomString;
    }

    public function ChatStatusChange(Request $request)
    {
        try {
            $user_one = User::find($request->user_one);
            $user_two = User::find($request->user_two);
            DB::table('users')->where('id', $user_one->id)->update(["chat_status" => "available"]);
            DB::table('users')->where('id', $user_two->id)->update(["chat_status" => "available"]);
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }
}
