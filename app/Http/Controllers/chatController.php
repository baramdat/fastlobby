<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Chat;
use App\Models\Site;
use App\Models\User;
use App\Events\ChatMessages;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Validator;
use App\Notifications\MessageNotification;
use Illuminate\Support\Facades\Notification;

class chatController extends Controller
{
    public function chatPage()
    {
        $user = Auth::user();
        $site = Site::where('id', $user->site_id)->first();
        $users = [];
        $cureent_role = "User";

        if ($user->hasRole('Admin')) {

            $cureent_role = "Admin";
            // $all = User::whereHas('roles', function ($q) {

            //     $q->where('name', 'BuildingAdmin')->orWhere('name', 'Integrator');
            // })->get();

            $all = User::whereHas('roles', function ($q) {
                $q->where('name', 'BuildingAdmin')->orWhere('name', 'Integrator');
            })->get();

            if (isset($all) && sizeof($all) > 0) {

                foreach ($all as $single) {

                    $users[] = User::find($single->id);
                }
            }
        } elseif ($user->hasRole('Integrator')) {

            $cureent_role = "Integrator";

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
            $cureent_role = "BuildingAdmin";

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

            $cureent_role = "Tenant";


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

            $cureent_role = "Employee";

            $users[] = User::whereHas('roles', function ($q) {

                $q->where('name', 'Tenant');
            })->where('id', $user->parent_id)->first();
        }
        //  dd($cureent_role);
        return view('templates/chat/compose', ['users' => $users, 'current_role' => $cureent_role]);
    }

    public function sendMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'message' => 'required',
                'receiver_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $chat = new Chat();
            $chat->subject = $request->subject;
            $chat->sender_id = Auth::user()->id;
            $chat->receiver_id = $request->receiver_id;
            $chat->message = $request->message;
            $chat->save();
            $message = Chat::find($chat->id);
            $notifyable = User::where('id', $chat->receiver_id)->first();
            $sender =  User::where('id', $chat->sender_id)->first();
            Notification::send($notifyable, new MessageNotification($sender, $message));

            return response()->json(['status' => 'success', 'msg' => 'message sent']);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }

    public function MessagesGet()
    {
        try {
            // $user = Auth::user();
            if (Auth::user()->hasRole('Admin')) {
                $messages = Chat::All()->orderBy('id', 'ASC')->get();
                return $messages;
            } else {
                $messages = Chat::where('sender_id', Auth::user()->id)->orWhere('receiver_id', Auth::user()->id)
                    ->orderBy('id', 'ASC')->get();
            }
            $html = "";
            if (isset($messages) && sizeof($messages) > 0) {

                foreach ($messages as $value) {
                    $user = User::find($value->sender_id);
                    $html .= '
                            <tr>
                                <td>' . ucwords($user->first_name) . ' ' . ucwords($user->last_name) . '</td>
                                <td>' . ucwords($value->subject) . ' </td>
                                <td>' . ucwords($value->message) . ' </td>
                            </tr>
                    ';
                }

                return response()->json(['status' => 'success', 'html' => $html]);
            } else {

                return response()->json(['status' => 'fail']);
            }
        } catch (Exception $e) {
        }
    }

    public function messageSend(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'message' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
        }

        $conversation = new Conversation();

        $conversation->sender_id = Auth::user()->id;
        $conversation->receiver_id = $request->receiver_id;
        $conversation->is_sender = "no";
        $conversation->save();

        if ($conversation) {

            if ($request->attach != "") {

                $files = $request->attach;
            } else {

                $files = NULL;
            }

            $chat = new Chat();

            $chat->sender_id = Auth::user()->id;
            $chat->receiver_id = $request->receiver_id;
            $chat->subject = $request->subject;
            $chat->message = $request->message;
            $chat->conversation_id = $conversation->id;
            $chat->file = $files;
            $chat->save();


            if ($chat) {
                $message = Chat::find($chat->id);
                $notifyable = User::where('id', $chat->receiver_id)->first();
                $sender =  User::where('id', $chat->sender_id)->first();
                Notification::send($notifyable, new MessageNotification($sender, $message));
                $userNotification = DB::table('notifications')->where('type', 'App\Notifications\MessageNotification')->latest()->first();
                $notifications = json_decode($userNotification->data);
                $notificationId = $userNotification->id;
                event(new ChatMessages($request->receiver_id));
                return response()->json(['status' => 'success', 'msg' => 'message sent']);
            } else {

                return response()->json(['status' => 'fail', 'msg' => 'Failed to send message']);
            }
        }
    }

    public function messageInbox(Request $request)
    {

        $query = $request->query('query');

        $user = Auth::user();

        $chats = Conversation::query();


        if (isset($query) && $query != '') {

            if ($query == "read") {

                $chats = $chats->where('read_at', '!=', NULL);
            } else {

                $chats = $chats->where('read_at', NULL);
            }
        }

        $chats = $chats->where('receiver_id', $user->id)->orWhere('sender_id', $user->id)->where('is_sender', 'yes')->orderBy('id', 'DESC');




        $chats = $chats->paginate(10);

        return view('templates.chat.list', compact('chats'));
    }

    public function inbox($id)
    {

        $decrypt = Crypt::decryptString($id);
        $conversationId = $decrypt;

        $con = Conversation::find($conversationId);

        if ($con) {
            if (Auth::user()->id == $con->sender_id) {

                $receiverId = $con->receiver_id;
            } else {
                $receiverId = $con->sender_id;
            }

            $chats = Chat::where('conversation_id', $conversationId)->get();


            return view('templates.chat.item', compact('chats', 'conversationId', 'receiverId'));
        } else {

            return view('templates.404');
        }
    }

    public function messageReply(Request $request)
    {

        $conversation = Conversation::find($request->conversation_id);
        $receiverId = $request->receiver_id;



        if ($request->receiver_id == $conversation->sender_id) {

            $conversation->is_sender = "yes";
            $conversation->save();
        }
        $c = Chat::where('id', $request->message_id)->first();
        if ($request->attach != "") {

            $files = $request->attach;
        } else {

            $files = NULL;
        }
        $chat = new Chat();

        $chat->sender_id = Auth::user()->id;
        $chat->receiver_id = $request->receiver_id;
        $chat->message = $request->message;
        $chat->message_id = $request->message_id;
        $chat->conversation_id = $request->conversation_id;
        $chat->file = $files;
        $chat->save();

        if ($chat) {
            if ($chat->sender_id == Auth::user()->id) {

                $receiverId = $chat->receiver_id;
            } else {

                $receiverId = $chat->sender_id;
            }

            $message = Chat::find($chat->id);
            $sender = User::find($message->sender_id);
            $receiver = User::find($message->receiver_id);
            $notifyable = User::where('id', $chat->receiver_id)->first();
            Notification::send($notifyable, new MessageNotification($sender, $message));
            if ($sender->image == "") {
                $img = '<img src="' . asset('assets/images/users/6.jpg') . '" class="me-2 rounded-circle avatar avatar-lg" alt="">';
            } else {
                $img = '<img src="' . asset('uploads/files/' . $sender->image) . '" class="me-2 rounded-circle avatar avatar-lg" alt="">';
            }
            $msg = $message->message;
            $html = '
                <div class="email-media" id="row' . $message->id . '">
                    <div class="mt-0 d-sm-flex">
                        ' . $img . '
                        <div class="media-body pt-0">
                            <div class="float-md-end d-flex fs-15">
                                <small class="me-3 mt-3 text-muted">' . $message->created_at->diffForHumans() . '</small>
                            </div>
                            <div class="media-title text-dark font-weight-semibold mt-1">' . ucwords($sender->first_name) . ' ' . ucwords($sender->last_name) . '<span class="text-muted font-weight-semibold">( ' . $sender->email . ' )</span></div>
                            <small class="mb-0">to ' . ucwords($receiver->first_name) . ' ' . ucwords($receiver->last_name) . ' ( ' . $receiver->email . ') </small>

                        </div>

                    </div>
                    <div class="eamil-body mt-5">
                            ' . $msg . '
                    </div>
                </div>
            ';

            return response()->json(['status' => 'success', 'html' => $html, 'id' => $message->id, 'receiverId' => $receiverId]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'Failed to reply']);
        }
    }

    public function fileUpload(Request $request): \Illuminate\Http\JsonResponse
    {
        $file = $request->file;
        $fileCount = $request->fileCount;
        $imgExt = ['jpg', 'png', 'JPG', 'PNG', 'jpeg', 'svg'];
        $html = '';
        $response = array();
        if ($file) {
            $path = public_path() . '/uploads/files/';
            $fileNam = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $size = $file->getSize();
            if ($size > 8000000) {
                $response["status"] = "fail";
                $response["msg"] = "Please upload file under <b>8MB</b> size.";
            } else {
                $imgHash = time() . md5(rand(0, 10));
                $filename = "ticket" . $imgHash . "." . $ext;
                $move = $file->move($path, $filename);
                if (in_array($ext, $imgExt)) {

                    $imgRow = '
                        <a href="javascript:;">
                        <img src="' . asset('uploads/files/' . $filename) . '" class="card-img-top" alt="img">
                        </a>
                    ';
                } elseif ($ext == 'pdf') {
                    $imgRow = '
                    <a href="javascript:;">
                    <embed src="' . asset('uploads/files/' . $filename) . '" class="card-img-top" alt="img" width="150px" height="150px"/>
                    </a>
                    ';
                } else {
                    $imgRow = '<a href="javascript:void(0)" class="ticket-file bg-light text-muted font-weight-bold">TXT</a>';
                }
                $html = '
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 mb-2 mb-sm-0" id="row' . $fileCount . '">
                    <div class="border overflow-hidden p-0 br-7">
                        ' . $imgRow . '
                        <div class="p-3 text-center">
                            <a href="javascript:;" class="fw-semibold fs-15 text-dark">' . $fileNam . '</a><br>
                            <a href="javascript:;" class="fw-semibold fs-15 text-dark btnDelete" id="' . $fileCount . '"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>
                ';
                $response["status"] = "success";
                $response["msg"] = "Files has been uploaded";
                $response["file"]["filename"] = $filename;
                $response["file"]["name"] = $fileNam;
                $response["file"]["ext"] = $ext;
                $response["file"]["fileCount"] = $fileCount;
                $response["file"]["size"] = $size;
                $response["file"]["html"] = $html;
            }
        } else {
            $response["status"] = "fail";
            $response["msg"] = "Please select file.";
        }
        return response()->json($response);
    }


    public function fileDelete(Request $request)
    {
        $currentImage = $request->file;
        if (!is_null($currentImage)) {
            $path = public_path() . '/uploads/files/';
            if (file_exists($path . $currentImage)) {
                @unlink($path . $currentImage);
            }
            if ($request->files = '') {
                $files = '';
            } else {
                $files = json_encode($request->files);
            }
            $response["status"] = "success";
            $response["msg"] = "file is deleted";
        } else {
            $response["status"] = "fail";
            $response["msg"] = "file is not selected";
        }
        return response()->json($response);
    }


    public function sentList()
    {
        $user = Auth::user();

        $chats = Chat::orderBy('id', 'DESC')->where('sender_id', $user->id)->paginate(20);

        return view('templates/chat/sent_list', compact('chats'));
    }

    public function sent($id)
    {

        $decrypt = Crypt::decryptString($id);
        $conversationId = $decrypt;

        $chat = Chat::find($conversationId);

        if ($chat) {

            return view('templates.chat.sent_item', compact('chat'));
        } else {

            return view('templates.404');
        }
    }
}
