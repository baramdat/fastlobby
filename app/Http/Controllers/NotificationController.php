<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Auth;

class NotificationController extends Controller
{

    public function messageNotificationManage()
    {
        try{
            $html = "";
            $user = auth()->user();
            $userNotifications = array();
            $unread = 0;
            $head = '<h5 class="m-0">(' . $unread . ') New Notifications</h5>';
            $notificationss = $user->notifications->where('type', 'App\Notifications\MessageNotification')
                ->all();
            $countHead = "";
            
            if (isset($notificationss) && sizeof($notificationss) > 0) {
                $message = "";
                foreach ($notificationss as $notification) {
                    if ($notification->read_at == NULL) {
                        $unread++;
                        $message .= '
                        <a class="dropdown-item d-flex" href="/mark/read/' . $notification->id . '/' . $notification->data['id'] . '">
                        <div class="wd-90p">
                            <div class="d-flex">
                                <h5 class="mb-1">' . $notification->data["title"] . '</h5>
                                <small class="text-muted ms-auto text-end">
                                ' . date('F d,Y H:i a', strtotime($notification->created_at)) . '
                                </small>
                            </div>
                            <span>' . substr($notification->data['message'], 0, 55) . '</span>
                        </div>
                    </a>
                        ';
                        $head = '<h5 class="m-0">(' . $unread . ') New Messages</h5>';
                        $countHead = '<span class="alert-count unreadCount">' . $unread . '</span>
                                <i class="bx bx-bell"></i>';
                    }
                }
                return response()->json(['status' => 'success', 'unread' => $unread, 'messages' => $message, 'head' => $head]);
            }else{
                return response()->json(['status' => 'fail', 'unread' => $unread, 'messages' => "", 'head' => $head]);
            }
        }catch(\Exception $e){
            return response()->json(['status' => 'fail', 'unread' => '', 'messages' => $e->getMessage(), 'head' => '']);
        }
    }

    public function videoNotificationManage()
    {
        try{
            $html = "";
            $user = auth()->user();
            $userNotifications = array();
            $url = "";
            $unread = 0;
            $head = '<h5 class="m-0">(' . $unread . ') New Notifications</h5>';
            $notificationss = $user->notifications->where('type', 'App\Notifications\videoChatNotification')
                ->all();
            $countHead = "";
            
            if (isset($notificationss) && sizeof($notificationss) > 0) {
                $message = "";
                foreach($notificationss as $notification) {
                    if ($notification->read_at == NULL) {
                        $notificationId = $notification->id;
                        // $sender_id = $notification->data['sender_id'];
                          if ($notificationId) {
                            $url = '/mark/read/' . $notificationId . '/' . $notification->data['id'];
                        } else {
                            $url = '';
                        }
                        $unread++;
                        $message .= '
                        <a class="dropdown-item d-flex" href="/mark/read/' . $notificationId . '/' . $notification->data['id'] . '">
                        <div class="row">
                            <div class="wd-90p">
                            <div class="d-flex">
                                    <h5 class="mb-1">' . $notification->data["title"] . '</h5>
                                    <small class="text-muted ms-auto text-end">
                                    ' . date('H:i a', strtotime($notification->created_at)) . '
                                    </small>
                                </div>
                                <span>' . substr($notification->data['message'], 0, 55) . '</span><br>
                            </div>
                        </div>
                        </a>
                        ';
                        $head = '<h5 class="m-0">(' . $unread . ') New Messages</h5>';
                        $countHead = '<span class="alert-count unreadCount">' . $unread . '</span>
                                <i class="bx bx-bell"></i>';
                    } 
                    // else {
                    //     $url = " ";
                    //     $notificationId = " ";
                    // }
                }
                return response()->json(['status' => 'success', 'unread' => $unread, 'url' => $url, 'notificationId' => $notificationId,  'messages' => $message, 'head' => $head]);
            }else{
                return response()->json(['status' => 'fail', 'unread' => $unread, 'url' => $url, 'notificationId' => '',  'messages' => '', 'head' => $head]);
            }
        }catch(\Exception $e){
            return response()->json(['status' => 'fail', 'unread' => '', 'url' => '', 'notificationId' => '',  'messages' => $e->getLine().": ".$e->getMessage(), 'head' => '']);
        }
    }

    public function messageMarkRead($id, $data)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        $url = $notification->data["route"];
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        return redirect($url);
    }
    
    public function videoNotificationRead($id)
    {
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        return response(['status'=>'success','msg'=>'call declined!']);
    }
        public function notificationDelete($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        
        if($notification){
            $notification->delete();
        }

        return response()->json(['status'=>'success','msg'=>'deleted']);
    }
}
