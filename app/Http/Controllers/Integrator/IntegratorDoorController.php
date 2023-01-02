<?php

namespace App\Http\Controllers\Integrator;

use App\Http\Controllers\Controller;
use App\Models\Door;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class IntegratorDoorController extends Controller
{

    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'relay_no' => 'required',
                'site_relay_url' => 'required',
                'site_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }

            $door = new Door();

            if (Door::where('site_relay_url', $request->site_relay_url)->where('site_id', $request->site_id)->exists()) {
                return response()->json(['status' => 'fail', 'msg' => 'Same Site Url already added!']);
            }

            if (Door::where('relay_no', $request->relay_no)->where('site_id', $request->site_id)->exists()) {
                return response()->json(['status' => 'fail', 'msg' => 'Same site relay number is already added!']);
            }
            $door->site_id = $request->site_id;
            $door->site_relay_url = $request->site_relay_url;
            $door->relay_no = $request->relay_no;
            $door->user_id = Auth::user()->id;
            if ($door->save()) {
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Door has been added successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'msg' => 'Failed to add the door'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 200);
        }
    }
    //count
    public function count(Request $request)
    {
        try {
            $filterDoorRelayNo = $request->filterDoorRelayNo;
            $filterSite = $request->filterSite;
            $result = Door::query();
            $result = $result->where('user_id',Auth::user()->id);

            if ($filterDoorRelayNo != '') {
                $result = $result->where('relay_no', 'like', '%' . $filterDoorRelayNo . '%');
            }

            if ($filterSite != '') {
                $result = $result->where('Site_id', $filterSite->id);
            }
            $count = $result->count();
            if ($count > 0) {
                return response()->json(['status' => 'success', 'data' => $count]);
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'No Data Found']);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }
    // list
    public function list(Request $request)
    {
        try {
            $filterDoorRelayNo = $request->filterDoorRelayNo;
            $filterLength = $request->filterLength;
            $filterSite = $request->filterSite;
            $result = Door::query();
            $result = $result->where('user_id',Auth::user()->id);

            if ($filterDoorRelayNo != '') {
                $result = $result->where('relay_no', 'like', '%' . $filterDoorRelayNo . '%');
            }

            if ($filterSite != '') {
                $result = $result->where('Site_id', $filterSite->id);
            }
            $i = 1;
            $doors = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();
            if (isset($doors) && sizeof($doors) > 0) {
                $html = '';
                foreach ($doors as $locker) {
                    $html .= '
                            <tr class="border-bottom"> 
                                <td>' . $i++ . '</td>
                                <td>
                                    <h6 class="mb-0 m-0 fs-14 fw-semibold">
                                    ' . ucwords($locker->site_relay_url) . '
                                        </h6>
                                </td>
                                <td>
                                    <h6 class="mb-0 m-0 fs-14 ">
                                    ' . ucwords($locker->site->name) . '</h6>
                                </td>
                                <td>
                                    <h6 class="mb-0 m-0 fs-14 ">
                                    ' . $locker->relay_no . '</h6>
                                </td>
                                <td>
                                    <a  href="javascript:;" state="2" doorId="' . $locker->id . '" relay="' . $locker->relay_no . '" class="btn btn-success btn-sm relayState">Open Door</a>
                                    <a  href="/integrator/door/edit/' . $locker->id . '" class="btn btn-warning btnEdit">Edit</a>
                                    <a  class="btn btn-danger text-white btnDelete" id="' . $locker->id . '">Delete</a>
                                    <a  href="/integrator/door/access/' . $locker->site_id . '/' . $locker->id . '"  target="_blank" class="btn btn-primary btnAccess">Door Access</a>
                                </td>
                            </tr>
                        ';
                }
                return response()->json(['status' => 'success', 'rows' => $html]);
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'No door Found!']);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }
    //editview
    public function edit($id)
    {
        $door = Door::where('id', $id)->first();

        if ($door) {
            $site = Site::where('id', $door->site_id)->first();
            $urls = $site->ip_address;
            if(! empty($urls)){

                $url = json_decode($urls,true);
            }else{
                $url=[];
            }
            return view('templates/Integrator/door/edit', ['door' => $door, 'urls' => $url]);
        } else {
            return view('templates.404');
        }
    }
        // update
    public function update(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'relay_no' => 'required',
                'site_relay_url' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }

            if (Door::where('relay_no', $request->relay_no)->where('site_id', $request->site_id)->where('id', '!=', $request->id)->exists()) {
                return response()->json(['status' => 'fail', 'msg' => 'Same door is already exists']);
            }

            if (Door::where('site_relay_url', $request->site_relay_url)->where('site_id', $request->site_id)->where('id', '!=', $request->id)->exists()) {
                return response()->json(['status' => 'fail', 'msg' => 'Same relay url is already exists']);
            }

            $door = Door::find($request->id);

            $door->relay_no = $request->relay_no;
            $door->site_id = $request->site_id;
            $door->site_relay_url = $request->site_relay_url;
            if ($door->save()) {
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Door updated'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'msg' => 'Failed to update the Door'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }
    
    // delete
    public function delete($id)
    {
        try {
            $door = Door::where('id', $id)->first();
            if ($door->delete()) {
                return response()->json(['status' => 'success', 'msg' => 'Door has been deleted']);
            }
            return response()->json(['status' => 'fail', 'msg' => 'Failed to delete the Door']);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }
    
    
        //get site urls list
    public function SiteUrls(Request $request)
    {
        $site = Site::where('id', $request->data)->first();
        $html = "";
        $ipss = $site->ip_address;
        if (!empty($ipss)) {
            $ips = json_decode($site->ip_address, true);
            foreach ($ips as $ip) {
                $html .= '<option value="' . $ip . '">' . $ip . '</option>';
            }
            return response()->json(['status' => 'success', 'data' => $html]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No ip found']);
        }
    }
    
    // relay status
    public function relayStateUpdate(Request $request)
    {
        try {
            $locker = Door::find($request->doorId);
            if ($locker) {
                $url = $locker->site_relay_url . '/state.xml?relay' . $request->relay . 'State=' . $request->state;
                $session = curl_init($url);
                // set some options for curl
                curl_setopt($session, CURLOPT_HEADER, false); // don't return the header
                curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // return the result as a string
                curl_setopt($session, CURLOPT_CONNECTTIMEOUT, 300); // timeout if we don't connect after 3 seconds to the device

                $locker = Door::find($request->doorId);

                $xml = curl_exec($session);

                $error_msg = "";
                if (curl_errno($session)) {
                    $error_msg = curl_error($session);
                }

                curl_close($session);
                if ($xml) {
                    $xml = self::xmlToArray($xml);
                    $relayArr = [];
                    $i = 0;
                    if (is_array($xml)) {
                        foreach ($xml as $key => $relay) {
                            if (strpos($key, 'relay') !== false) {
                                $relayArr[$i]["relay"] = substr($key, 5);
                                $relayArr[$i++]["state"] = $xml[$key];
                            }
                        }
                    }
                    return response()->json(['status' => 'success', 'msg' => 'Locker' . strtoupper($locker->relay_no) . ($request->state == 0 ? ' closed' : ' opened') . ' successfully', 'data' => $relayArr]);
                } else {
                    return response()->json(['status' => 'fail', 'msg' => $error_msg]);
                }
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'Door not found']);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    public function xmlToArray($xmlstring)
    {

        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        return $array;
    }
    
    //door access page
    public function  doorAccess($site, $door)
    {
        try {
            $site = Site::find($site);
            $door = Door::find($door);
            if (isset($site) && isset($door)) {
                return view('templates.Integrator.door.access_page', ['site' => $site, 'door' => $door]);
            } else {
                return view('templates/error');
            }
        } catch (Exception $e) {
            return view('templates/error');
        }
    }
    //door access update
    public function doorAccessUpdate(Request $request, $user)
    {
        $current = date('H A');

        $user = User::find($user);
        $site = Site::where('id', $request->site)->first();
        $door = Door::where('id', $request->door)->first();
        $html = '';
        if ($door->site_id == $user->site_id) {
            $strtTime = date('H A', strtotime($user->start_time));
            $endTime = date('H A', strtotime($user->end_time));

            if ($current >= $strtTime && $current <= $endTime) {
                $html = '
                <p>Access Granted Successfully</p>
                ';
                return response()->json(['status' => 'success', 'msg' => $html]);
            } else {
                $html = '
                <p>Unauthorized access!</p>
                ';
                return response()->json(['status' => 'fail', 'msg' => $html]);
            }
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'Not authorized to the site!']);
        }
    }
}
