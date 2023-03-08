<?php

namespace App\Http\Controllers;

use App\Models\Videos;
use App\Models\QrCodeType;
use App\Models\Screens;
use App\Models\SiteQrCodes;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ScreenController extends Controller
{
    public function index()
    {
        $videos = Videos::where('site_id', Auth::user()->site_id)->get();
        $qrs = SiteQrCodes::where('site_id', Auth::user()->site_id)->get();

        return view('templates.screens.add', compact('videos', 'qrs'));
    }

    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'videos' => 'required',
                'qrs' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $screen = new Screens();
            $screen->name = $request->name;
            $screen->site_id = Auth::user()->site_id;
            $screen->unique_code = $this->generateUniqueCode();
            $screen->videos = json_encode($request->videos);
            $screen->qrs_codes = json_encode($request->qrs);
            $screen->save();
            if ($screen) {


                return response()->json([
                    'status' => 'success',
                    'msg' => 'Site screen  created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'msg' => 'Failed to create an Site screen!'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }
    public function screenCount(Request $request)
    {
        $filterName = $request->filterName;
        $filterLength = $request->filterLength;
        $result = Screens::query();
        if ($filterName != '') {
            $result = $result->where('name', 'like', '%' . $filterName . '%');
        }



        $count = $result->count();

        if ($count > 0) {
            return response()->json(['status' => 'success', 'data' => $count]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No Data Found']);
        }
    }

    // append data  
    public function screenList(Request $request)
    {
        $filterName = $request->filterName;
        $filterLength = $request->filterLength;
        $result = Screens::query();
        if ($filterName != '') {
            $result = $result->where('name', 'like', '%' . $filterName . '%');
        }

        $user = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();
        if (isset($user) && sizeof($user) > 0) {
            $html = '';
            foreach ($user as $usr) {
                $html .= '
                 <tr class="border-bottom"> 
                     <td>
                     ' . ucwords($usr->name) . '
                     </td>
                     <td>
                     <div class="btn-group btn-group-sm" role="group">
                         
                         <a  class="btn btn-success text-white" href="/view/screen/data/' . $usr->unique_code . '" target="_blank">View</a>
                         </div>
                   
                     </td>
                     <td>
                         <div class="btn-group btn-group-sm" role="group">
                         <a  href="/screen/edit/' . $usr->id . '" class="btn btn-warning btnEdit">Edit</a>
                         
                             <a  class="btn btn-danger text-white btnDelete" id="' . $usr->id . '">Delete</a>
                         </div>
                     </td>
                 </tr>
             ';
            }
            return response()->json(['status' => 'success', 'rows' => $html]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No User Found!']);
        }
    }

    public function screenView($id)
    {
        $screen = Screens::where('unique_code', $id)->first();
        $qr_code = SiteQrCodes::whereIn('id', json_decode($screen->qrs_codes))->get();
        $arr = json_decode($screen->videos);
        if ($arr) {
            return view('templates/screens/screen_view', ['video' => $arr[0], 'array' => $arr, 'qr_code' => $qr_code]);
        } else {
            return view('templates.404');
        }
    }
    public function editScreen($id)
    {
        $screen = Screens::where('id', $id)->first();
        $videos = Videos::where('site_id', Auth::user()->site_id)->get();
        $qrs = SiteQrCodes::where('site_id', Auth::user()->site_id)->get();
        if ($screen) {
            return view('templates/screens/edit', compact('screen', 'videos', 'qrs'));
        } else {
            return view('templates.404');
        }
    }
    // update user
    public function updateScreen(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'videos' => 'required',
                'qrs' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $screen = Screens::where('id', $request->id)->first();
            if ($screen) {
                $screen->name = $request->name;
                $screen->videos = json_encode($request->videos);
                $screen->qrs_codes = json_encode($request->qrs);

                if ($screen->save()) {
                    return response()->json([
                        'status' => 'success',
                        'msg' => 'Screen Updated Successfully'
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'fail',
                        'msg' => 'something went wrong'
                    ], 200);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    public function deleteScreen($id)
    {
        $screen = Screens::find($id);

        $screen->delete();
        if ($screen) {
            return response()->json(['status' => 'success', 'msg' => 'Screen is Deleted']);
        }
        return response()->json(['status' => 'fail', 'msg' => 'failed to delete screen']);
    }
    public function generateUniqueCode()

    {

        $randomString = "SQ-" . Str::random(15);
        return $randomString;
    }
}
