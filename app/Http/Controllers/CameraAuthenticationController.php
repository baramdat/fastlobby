<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CameraAuthentication;
use Illuminate\Support\Facades\Auth;
use Mtownsend\XmlToArray\XmlToArray;
use App\Models\User;
use Exception;

class CameraAuthenticationController extends Controller
{
    //camera auth add 
    public function authAdd(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users',
                'password' => 'required',
                'port' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            else{
                $cameraAuth = new CameraAuthentication();
                $cameraAuth->username = $request->username;
                $cameraAuth->password = $request->password;
                $cameraAuth->port = $request->port;
                $user = User::where('id',Auth::user()->id)->first();
                if($user){
                    $cameraAuth->user_id = $user()->id;
                }else{
                    return response()->json(['status'=>'fail','msg'=>'user not found']);
                }
                $cameraAuth->token = base64_encode($request->username.$request->password);
                $cameraAuth->save();
                return response()->json(['status'=>'success','msg'=>'Camera auth generated successfully']);
            }
        }
        catch(Exception $e){
            return response()->json(['status'=>'fail','msg'=>$e->getMessage(),'error line'=>$e->getLine()]);
        }
    }

    //camera get channel list
    public function getChannelList(Request $request)
    {
        try {
            $URL = "http://68.195.234.210:3065/GetChannelList";
            $Username = "admin";
            $Password = "troiano10!";
            $ch = curl_init($URL);
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$Username:$Password");
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type:application/json'
                )
            );
            $curl_response = curl_exec($ch);
            curl_close($ch);
            $array = XmlToArray::convert($curl_response);
            $items =  $array['item'];
            $data = [];
            $html = "";
            foreach ($items as $key => $value) {

                $data[$key]["Id"] = $value["@content"];
                $id =  $data[$key]["Id"];
                $data[$key]["status"] = $value["@attributes"]["channelStatus"];
                $data[$key]["name"] = $value["@attributes"]["name"];
                    $html .= '<tr>
                    <td><a href="/streaming/'.$id.'">' . $data[$key]["name"] . '</a></td>
                    <td><a href="/streaming/'.$id.'">' . $data[$key]["status"] . '</a></td></tr>';
            }
            return response()->json(['status' => 'success', 'data' => $html]);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }
    //get camera's streaming
    //get camera's streaming
    public function getCameraList(Request $request)
    {
        try {
            $URL = "http://68.195.234.210:3065/GetChannelList";
            $Username = "admin";
            $Password = "troiano10!";
            $ch = curl_init($URL);
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$Username:$Password");
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type:application/json'
                )
            );
            $curl_response = curl_exec($ch);
            curl_close($ch);
            $array = XmlToArray::convert($curl_response);
            $items =  $array['item'];
            $data = [];
            $html = "";
            foreach ($items as $key => $value) {

                $data[$key]["Id"] = $value["@content"];
                $id =  $data[$key]["Id"];
                // $data[$key]["status"] = $value["@attributes"]["channelStatus"];
                // $data[$key]["name"] = $value["@attributes"]["name"];
                $html .= '
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="card" style="width:100%;height:100%; border-radius:5px">
                <iframe src="http://aws.fastlobby.com/streaming/' . $id . '" style="width: 100%;min-height:auto;" title="Main Stream"></iframe>
                <div class="card-body">
                  <h4 class="card-title">' . $value["@attributes"]["name"] . '</h4>
                </div>
                </div>    
                </div>
                ';
            }
            return response()->json(['status' => 'success', 'data' => $html]);
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }


}
