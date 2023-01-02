<?php

namespace App\Http\Controllers\Integrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalkinAppointment;
use App\Models\Appointment;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Site;
use App\Models\User;


use Exception;
use Illuminate\Support\Facades\Validator;

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
            $site->user_id = Auth::user()->id;
            if ($request->url != "") {
                $ip_address = json_encode($request->url, true);
            } else {
                $ip_address = NULL;
            }
            $site->ip_address = $ip_address;
            if ($site->save()) 
            {
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
            } else {
                return response()->json([
                    'status' => 'fail',
                    'msg' => 'Failed to add the site'
                ], 200);
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
        do {
            $randomString = "ST".Str::random(15);
        } while (Appointment::where("unique_code", "=", $randomString)->first());

        return $randomString;
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
            } else {

                $ip_address = [];
            }
            return view('templates/Integrator/site/edit', ['site' => $site, 'urls' => $ip_address]);
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

   //admin site integrator list
    public function adminIntegratorList()
    {
        try {
           $result = User::query();

            $integrators=$result->whereHas('roles',function($q){
                $q->where('name','Integrator');
            })->get();  
            
            $html = "";
            if (isset($integrators) && sizeof($integrators) > 0) { {
                    foreach ($integrators as $integrator) {
                        $html .= '<tr>
                    <td>' . $integrator->first_name . ' ' . $integrator->last_name . '</td>
                    <td>' . $integrator->email . '</td>
                    <td>' . $integrator->phone . '</td>
                    <td>' . $integrator->status . '</td>
                    <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <a  href="/user/edit/' . $integrator->id . '" class="btn btn-warning btnEdit">Edit</a>
                        <a  class="btn btn-danger text-white btnDelete" id="' . $integrator->id . '">Delete</a>
                    </div>
                    </td>';
                    }
                    return response()->json(['status' => 'success', 'data' => $html, 'detail' => $integrators]);
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }

    //admin integrator delete
    public function adminIntegratorDelete($id){
        try {
            $user = User::find($id);

            if ($user->delete()) {
                return response()->json(['status' => 'success', 'msg' => 'Integrator has been deleted']);
            }
            return response()->json(['status' => 'fail', 'msg' => 'Failed to delete the Integrator']);
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

            $result = $result->where('user_id',auth()->user()->id);
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
            $result = $result->where('user_id',auth()->user()->id);


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
                                <div class="btn-group btn-group-sm" role="group">
                                    <a  href="/integrator/site/edit/' . $site->id . '" class="btn btn-warning btnEdit">Edit</a>
                                    <a  class="btn btn-danger text-white btnDelete" id="' . $site->id . '">Delete</a>
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
    //integrator sites at dashboard
    public function integratorDashbaordSitesList()
    {
        try {
            $sites = Site::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
            if (isset($sites) && sizeof($sites) > 0) {
                $html = '';
                foreach ($sites as $site) {
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
                                <div class="btn-group btn-group-sm" role="group">
                                    <a  href="/integrator/site/edit/' . $site->id . '" class="btn btn-warning btnEdit">Edit</a>
                                    <a  class="btn btn-danger text-white btnDelete" id="' . $site->id . '">Delete</a>
                                </div>
                            </td>
                        </tr>
                    ';
                }
                return response()->json(['status' => 'success', 'data' => $html,]);
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'No Site Found!']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }

}
