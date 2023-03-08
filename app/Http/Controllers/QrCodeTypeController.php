<?php

namespace App\Http\Controllers;

use App\Models\QrCodeType;
use App\Models\SiteQrCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeTypeController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasRole('User')) {
            return view('templates.qr_codes_types.add');
        } else {
            return view('templates.404');
        }
    }

    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }



            $user = new QrCodeType();

            $user->name = $request->type;
            $user->save();
            if ($user) {


                return response()->json([
                    'status' => 'success',
                    'msg' => 'Qr code type created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'msg' => 'Failed to create an Qr code type!'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    public function qrCodeCount(Request $request)
    {
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $result = QrCodeType::query();
        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
        }



        $count = $result->count();

        if ($count > 0) {
            return response()->json(['status' => 'success', 'data' => $count]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No Data Found']);
        }
    }

    // append data  
    public function list(Request $request)
    {
        $filterTitle = $request->filterTitle;
        $filterLength = $request->filterLength;
        $result = QrCodeType::query();
        if ($filterTitle != '') {
            $result = $result->where('name', 'like', '%' . $filterTitle . '%');
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
    public function deleteQrCode($id)
    {
        $user = QrCodeType::find($id);

        $user->delete();
        if ($user) {
            return response()->json(['status' => 'success', 'msg' => 'Qr code is Deleted']);
        }
        return response()->json(['status' => 'fail', 'msg' => 'failed to delete Qr code']);
    }

    public function deleteSiteQrCode($id)
    {
        $siteQr = SiteQrCodes::find($id);
        $path = public_path() . '/' . $siteQr->image;
        if (file_exists($path)) {
            @unlink($path);
        }
        if ($siteQr->delete()) {
            return response()->json(['status' => 'success', 'msg' => 'Qr code is Deleted']);
        }
        return response()->json(['status' => 'fail', 'msg' => 'failed to delete Qr code']);
    }
    public function generateList(Request $request)
    {

        $filterLength = $request->filterLength;
        $result = SiteQrCodes::where('site_id', Auth::user()->site_id);
        $sitesQr = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();
        if (isset($sitesQr) && sizeof($sitesQr) > 0) {
            $html = '';
            foreach ($sitesQr as $site) {
                if (isset($site->image) &&  $site->image != NULL) {
                    $qr =  '<img src="' . asset($site->image) . '">';
                } else {
                    $qrDownload = '';
                }
                $html .= '
                    <tr class="border-bottom"> 
                        <td>
                        ' . ucwords($site->QrType->name) . '
                        </td>


                        <td>
                            ' . $qr . '
                        </td>

                        <td>
                         <div class="btn-group btn-group-sm" role="group">
                         <a   class="btn btn-warning btnRegenerate" id="' . $site->id . '">Regenerate</a>
                         
                             <a  class="btn btn-danger text-white btnDelete" id="' . $site->id . '">Delete</a>
                         </div>
                     </td>
                    </tr>
                ';
            }
            return response()->json(['status' => 'success', 'rows' => $html,]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'No Site Found!']);
        }
    }
    public function siteQrCount(Request $request)
    {
        try {

            $result = SiteQrCodes::where('site_id', Auth::user()->site_id);
            $count = $result->count();
            if ($count <= 0) {
                $list = QrCodeType::get();
                foreach ($list as $key => $value) {
                    $qr = new SiteQrCodes();
                    $link = $this->generateUniqueCode();
                    QrCode::format('png')->size(200)->generate($link, 'images/codes/' . $link . '.png');
                    $qr->site_id = Auth::user()->site_id;
                    $qr->image = ('images/codes/' . $link . '.png');
                    $qr->qr_type_id = $value->id;
                    $qr->qr_code = $link;
                    $qr->save();
                }
                $result = SiteQrCodes::where('site_id', Auth::user()->site_id);
                $count = $result->count();
            }
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

    public function regenerateQrCode($id)
    {
        try {
            $screenQr = SiteQrCodes::where('id', $id)->first();
            if ($screenQr) {
                $path = public_path() . '/' . $screenQr->image;
                if (file_exists($path)) {
                    @unlink($path);
                }
                $link = $this->generateUniqueCode();
                // QrCode::format('png')->size(200)->generate($link, 'images/codes/' . $link . '.png');
                $screenQr->image = ('images/codes/' . $link . '.png');
                $screenQr->qr_code = $link;

                if ($screenQr->save()) {
                    return response()->json([
                        'status' => 'success',
                        'msg' => 'New Qr Code Generated Successfully'
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
    public function generateUniqueCode()

    {

        $randomString = "SQ-" . Str::random(15);
        return $randomString;
    }
}
