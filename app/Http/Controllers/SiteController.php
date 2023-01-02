<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Locker;
use App\Models\LockerSize;
use App\Models\WalkinAppointment;
use App\Models\Appointment;
use App\Models\Site;
use Illuminate\Support\Facades\Crypt;

use Exception;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SiteController extends Controller
{

    //add
    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'address' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }

            $site = new Site();

            if (Site::where('name', trim($request->name))->exists()) {
                return response()->json(['status' => 'fail', 'msg' => 'Same site already added!']);
            }
            $site->name = trim($request->name);
            $site->address = $request->address;
            $site->status = $request->status;
            $site->unique_code = $this->generateUniqueCode();
             if ($request->url != "") {
                $ip_address = json_encode($request->url, true);
            } else {
                $ip_address = NULL;
            }
            $site->user_id = Auth::user()->id;
            $site->ip_address = $ip_address;
            $site->save();
            // $site_id = Crypt::encrypt($site->id);
            $link = $site->unique_code;
            QrCode::format('png')->size(200)->generate($link, 'images/codes/' . $site->unique_code . '.png');
            $img_url = ('images/codes/' . $site->unique_code.'.png');
            if ($site->qr_code == NULL) {
                DB::table('sites')->where('id', $site->id)->update(["qr_code" => $img_url]);
            }
            return response()->json([
                'status' => 'success',
                'msg' => 'Site has been added successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }
    
    
    public function generateUniqueCode()
    {
        do {
            $randomString = "ST".Str::random(15);
        } while (Appointment::where("unique_code", "=", $randomString)->first());

        return $randomString;
    }
    
    //unique code for sites
    public function addSiteUniqueCode()
    {
        try {
            $sites = Site::all();
            if (isset($sites) && sizeof($sites) > 0) {
                foreach ($sites as $site) {
                    $randomString = "ST" . Str::random(15);
                    if ($site->unique_code == NULL) {
                        DB::table('sites')->where('id', $site->id)->update(["unique_code" => $randomString]);
                    }
                }
            }
            return "done";
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }
    
    //Qr for all sites
     public function GenerateSitesQr()
    {

        try {
            $sites = Site::all();
            if (isset($sites) && sizeof($sites) > 0) {
                foreach ($sites as $site) {
                    $link = $site->unique_code;
                    QrCode::format('png')->size(200)->generate($link, public_path('images/codes/') . $site->unique_code . '.png');
                    $img_url = ('images/codes/' . $site->unique_code . '.png');
                    if ($site->qr_code == NULL) {
                        DB::table('sites')->where('id', $site->id)->update(["qr_code" => $img_url]);
                    }
                }
                return "done";
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }

    // update
    public function update(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'address' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }

            if (Site::where('name', $request->name)->where('id', '!=', $request->id)->exists()) {
                return response()->json(['status' => 'fail', 'msg' => 'Site with same name is already exists']);
            }



            $site = Site::find($request->id);

            $site->name = trim($request->name);
            $site->address = $request->address;
            $site->status = $request->status;
            if ($request->url != "") {
                $ip_address = json_encode($request->url, true);
            } else {
                $ip_address = NULL;
            }
            $site->ip_address = $ip_address;

            if ($site->save()) {
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Site updated'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'msg' => 'Failed to update the site'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    public function viewEdit($id)
    {
        $site = Site::where('id', $id)->first();
        $html = "";
        if (!empty($site)) {
            if ($site->ip_address != " ") {
                $ip_address = json_decode($site->ip_address, true);

            }else{

                $ip_address=[];
            }
            return view('templates/site/edit', ['site' => $site, 'urls' => $ip_address]);
        } else {
            return view('templates.404');
        }
    }

    public function viewDetails($id)
    {
        $site = Site::where('id', $id)->first();
        if (!empty($site)) {
            return view('templates/site/detail', ['locker' => $site]);
        } else {
            return view('templates.404');
        }
    }

    // view locker by id
    public function view($id)
    {
        $locker = Locker::find($id);
        if (!empty($locker)) {
            return view('templates.users.user_detail', ['locker' => $locker]);
        } else {
            return view('templates.404', ['locker' => $locker]);
        }
    }

    // delete
    public function delete($id)
    {
        try {
            $site = Site::find($id);

            if ($site->delete()) {
                return response()->json(['status' => 'success', 'msg' => 'Site has been deleted']);
            }
            return response()->json(['status' => 'fail', 'msg' => 'Failed to delete the site']);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    // count
    public function count(Request $request)
    {
        try {
            $filterName = $request->filterName;
            $filterStatus = $request->filterStatus;
            $result = Site::query();

            if ($filterName != '') {
                $result = $result->where('name', 'like', '%' . $filterName . '%');
            }

            if ($filterStatus != 'all') {
                $result = $result->where('status', $filterStatus);
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
            $filterName = $request->filterName;
            $filterStatus = $request->filterStatus;
            $filterLength = $request->filterLength;
            $result = Site::query();

            if ($filterName != '') {
                $result = $result->where('name', 'like', '%' . $filterName . '%');
            }

            if ($filterStatus != 'all') {
                $result = $result->where('status', $filterStatus);
            }

            $i = 1;


            $sites = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();
            if (isset($sites) && sizeof($sites) > 0) {
                $html = '';
                foreach ($sites as $site) {
                    if (isset($site->qr_code) &&  $site->qr_code != NULL) {
                        $qr =  '<img src="'.asset($site->qr_code).'">';
                        $qrDownload = '<a  class="btn btn-success text-white btnQrDownload" data="' . $site->name . '" fileUrl="' . asset('/images/codes/' . $site->unique_code . '.png') . '">Download QR</a>';
                    } else {
                        $qrDownload = '';
                        $qr = '';
                    }
                    $html .= '
                        <tr class="border-bottom"> 
                            <td>
                                <h6 class="mb-0 m-0 fs-14 fw-semibold">
                                ' . ucwords($site->name) . '
                                    </h6>
                                <p class="text-muted"><i class="fa fa-map-marker text-primary"></i> ' . ucwords($site->address) . '</p>
                            </td>

                            <td>
                                <h6 class="mb-0 m-0 fs-14 ">' . self::status($site->status) . '</h6>
                            </td>

                            <td>
                                '.$qr.'
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a  href="/site/edit/' . $site->id . '" class="btn btn-warning btnEdit">Edit</a>
                                    <a  class="btn btn-danger text-white btnDelete" id="' . $site->id . '">Delete</a>&nbsp;
                                    ' . $qrDownload . '&nbsp;&nbsp;
                                </div>
                            </td>
                        </tr>
                    ';
                }
                return response()->json(['status' => 'success', 'rows' => $html, 'data' => $sites]);
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'No Site Found!']);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    public function adminSiteList()
    {

        $sites = Site::all();
        $siteArr = [];
        $userArr = [];
        $html = "";

        foreach ($sites as $site) {

            $users = User::where('site_id', $site->id)->get();
            if (isset($users) && sizeof($users) > 0) {

                foreach ($users as $user) {

                    if (!in_array($user->id, $userArr)) {
                        $userArr[] = $user->id;
                    }
                }

                $appointments = Appointment::whereIn('tenant_id', $userArr)->count();
                $walkin = WalkinAppointment::whereIn('tenant_id', $userArr)->count();

                $html .= '<tr>
                <td>' . $site->name . '</td>
                <td>' . $appointments . '</td>
                <td>' . $walkin . '</td>';
            } else {

                $html .= '<tr>
                <td>' . $site->name . '</td>
                <td>0</td>
                <td>0</td>';
            }
        }

        return response()->json(['status' => 'success', 'data' => $html]);
    }

    public static function status($status)
    {
        $html = '';
        if ($status == 'active') {
            $html = '<badge class="badge bg-success">Available</badge>';
        } else if ($status == 'inactive') {
            $html = '<badge class="badge bg-danger">Inactive</badge>';
        } else {
            $html = '<badge class="badge bg-danger">Inactive</badge>';
        }

        return $html;
    }

    public function externalScanPage($id)
    {
        $site = Site::find($id);
        if ($site) {
            return view('templates.external.scan', ['site' => $site]);
        } else {
            return view('templates.error');
        }
    }
}
