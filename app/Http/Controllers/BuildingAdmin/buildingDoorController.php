<?php

namespace App\Http\Controllers\BuildingAdmin;

use App\Http\Controllers\Controller;
use App\Models\Door;
use App\Models\Site;
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

class buildingDoorController extends Controller
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
                'msg' => $e->getMessage()
            ], 200);
        }
    }

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
                                    <a  href="javascript:;" state="2" lockerId="' . $locker->id . '" relay="' . $locker->relay . '" class="btn btn-success btn-sm relayState">Open Door</a>
                                    <a  href="/building/door/edit/' . $locker->id . '" class="btn btn-warning btnEdit">Edit</a>
                                    <a  class="btn btn-danger text-white btnDelete" id="' . $locker->id . '">Delete</a>      
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
            return view('templates/building_admin/door/edit', ['door' => $door, 'urls' => $url]);
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
}
