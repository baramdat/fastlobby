<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Site;
use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Support\Str;
use Twilio\Jwt\AccessToken;
use Illuminate\Http\Request;
use App\Models\VideoChatRoom;
use App\Events\UserNotification;
use Twilio\Jwt\Grants\VideoGrant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use FFMpeg\Filters\Video\VideoFilters;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Notifications\videoChatNotification;
use Illuminate\Support\Facades\Notification;


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
        try {
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
            $allRooms = $client->video->rooms->read([]);
            $rooms = array_map(function ($room) {
                return $room->uniqueName;
            }, $allRooms);
            
            return view('templates/chat/compose_video_chat', ['users' => $users, 'rooms' => $rooms]);
        } catch (\Exception $e) {
            return view('templates/chat/compose_video_chat', ['users' => [], 'rooms' => [], 'error' => $e->getMessage()]);
        }
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
                $userNotification = DB::table('notifications')->latest()->first();
                $notifications = json_decode($userNotification->data);
                $notificationId = $userNotification->id;
                if ($notificationId) {
                    $callurl = $notificationId;
                } else {
                    $callurl = '';
                }
                $VideoNotification = '
                        <a class="dropdown-item d-flex" href="/mark/read/' . $notificationId . '/' . $notifications->id . '">
                        <div class="row">
                            <div class="wd-90p">
                            <div class="d-flex">
                                    <h5 class="mb-1">' . $notifications->title . '</h5>
                                    <small class="text-muted ms-auto text-end">
                                    ' . date('H:i a', strtotime($userNotification->created_at)) . '
                                    </small>
                                </div>
                                <span>' . substr($notifications->message, 0, 55) . '</span><br>
                            </div>
                        </div>
                        </a>';
                event(new UserNotification($VideoNotification, $id, $notificationId, $callurl));
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
        
        return view('templates.chat.video_chat_room', ['accessToken' => $token->toJWT(), 'room' => $room, 'roomName' => $roomName, 'user_one' => $user_one->id, 'user_two' => $user_two->id]);
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

    public function updateUserRoomStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'status' => 'required',
                'room' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }

            $user_id = $request->user_id;
            $status = $request->status == 'joined' ? 1 : 0;
            $room = $request->room;

            $video_chat_room = VideoChatRoom::where('room_name', $room)->first();

            if (isset($video_chat_room) && !empty($video_chat_room)) {
                if ($video_chat_room->user_one == $user_id) {
                    $video_chat_room->is_one_joined = $status;
                } else if ($video_chat_room->user_two == $user_id) {
                    $video_chat_room->is_two_joined = $status;
                }
                if ($video_chat_room->save()) {
                    $chat_status = $status == 'joined' ? 'busy' : 'available';
                    DB::table('users')->where('id', $user_id)->update(["chat_status" => $chat_status]);
                    response()->json(['status' => 'success', 'msg' => 'User room status updated']);
                } else {
                    response()->json(['status' => 'fail', 'msg' => 'Failed to update the user room status']);
                }
            } else {
                response()->json(['status' => 'fail', 'msg' => 'Video chat room not found']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }
}
