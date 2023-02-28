<?php

namespace App\Http\Controllers\BuildingAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Site;
use App\Models\Appointment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\visitorConfirmation;
use App\Models\WalkinAppointment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
class AppointmentController extends Controller
{
    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'date' => 'required',
                'time' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $user = User::find($request->tenant_id);
            $today = Carbon::now();
            $app = new Appointment();

            $app->name = $request->name;
            $app->email = $request->email;
            $app->phone = $request->phone;
            $app->date = $request->date;
            $app->time = $request->time;
            $app->tenant_id = $request->tenant_id;
            $app->site_id = $user->site_id;
            $app->unique_code = $this->generateUniqueCode();
            $app->created_at = $today;
            $app->save();

            if ($app) {
                $ap = Appointment::find($app->id);
                $link = $ap->unique_code;

                $client = User::find($ap->tenant_id);
                $visitor_id = $ap->unique_code;
                $site = $client->site->name;

              QrCode::format('png')->size(200)->generate($link, 'images/codes/' . $ap->unique_code . '.png');
                $img_url = ('images/codes/' . $ap->unique_code . '.png');

                DB::table('appointments')->where('id', $ap->id)->update(["qr_code" => $img_url]);
                
                $data = [];
                $data["visitor_name"] = $ap->name;
                $data["site"] = $site;
                $data["image_url"] = asset($ap->qr_code); 
                $data["id"] = $visitor_id;
                Mail::to($ap->email)->send(new visitorConfirmation($data));

                //twillo sms
                $account_sid = 'ACbe9332f45de09e658c04c6c08eb989e3';
                $auth_token = '4a456542ab17fafb6bd146ad7d93ce1e';
                $twilio_number = '+18152408707';
                $site = Site::where('id', $client->site->id)->first();
                $siteName = $site->name;
                $receiverNumber = $request->phone;
                $url = route('detail', ['id' => $visitor_id]);
                $message = 'You have been invited to visit ' . $siteName . ' please click this link and check the invitation details link is:' . $url . '';

                $client = new Client($account_sid, $auth_token);
                $client->messages->create($receiverNumber, [
                    'from' => $twilio_number,
                    'body' => $message
                ]);


                return response()->json(['status' => 'success', 'msg' => 'Appointment added successfully']);
            } else {

                return response()->json(['status' => 'fail', 'msg' => 'Failed to add appointment']);
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
            $randomString = "AP".Str::random(15);
        } while (Appointment::where("unique_code", "=", $randomString)->first());
  
        return $randomString;
    }

    // count
    public function count(Request $request)
        {
            try {
                $filterSearch = $request->filterSearch;
                $filterLength = $request->filterLength;
                $filterStatus = $request->filterStatus;
                $filterPhone = $request->filterPhone;
    
                $result = Appointment::query();
                $result = $result->where('site_id', Auth::user()->site_id);
                if (isset($filterSearch) && $filterSearch != '') {
    
                    $result = $result->where('name', 'like', '%' . $filterSearch . '%');

                }
    
                if (isset($filterStatus) &&  $filterStatus != 'all') {
                    $result = $result->where('status', $filterStatus);
                }
    
                if (isset($filterPhone) &&  $filterPhone != ' ') {
    
                    $result = $result->where('phone', $filterPhone);
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
                $filterSearch = $request->filterSearch;
                $filterLength = $request->filterLength;
                $filterStatus = $request->filterStatus;
                $filterPhone = $request->filterPhone;
                // $filterTitle=$request->filterTitle;
                // $filterLength=$request->filterLength;
                $result = Appointment::query();

                $result = $result->where('site_id', Auth::user()->site_id);

                if (isset($filterSearch) &&  $filterSearch != '') {
                    $result = $result->where('name', 'like', '%' . $filterSearch . '%');
                }
    
    
    
                if (isset($filterStatus) &&  $filterStatus != 'all') {
                    $result = $result->where('status', $filterStatus);
                }
    
                if (isset($filterPhone) &&  $filterPhone != ' ') {
                    $result = $result->where('phone', $filterPhone);
                }
    
                $i = 1;
                $appointments = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();
                if (isset($appointments) && sizeof($appointments) > 0) {
                    $html = '';
                    foreach ($appointments as $value) {
                        if ($value->status == "pending") {
                            $status = '<span class="badge bg-warning text-white p-1" style="border-radius:10px">' . ucwords($value->status) . '</span>';
                        } elseif ($value->status == "check_in") {
                            $status = '<span class="badge bg-primary  text-white p-1" style="border-radius:10px">Checked In</span>';
                        } elseif ($value->status == "decline") {
                            $status = '<span class="badge bg-danger  text-white p-1" style="border-radius:10px">' . ucwords($value->status) . '</span>';
                        }
                        $html .= '
                                <tr class="border-bottom" id="row' . $value->id . '" data-id="' . $value->id . '"> 
                                    <td>
                                        <h6 class="mb-0 m-0 fs-14 fw-semibold">
                                        ' . ucwords($value->user->first_name) . ' ' . ucwords($value->user->last_name) . '
                                            </h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 m-0 fs-14 fw-semibold">
                                        ' . ucwords($value->name) . '
                                            </h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 m-0 fs-14 ">
                                        ' . ucwords($value->email) . '</h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 m-0 fs-14 ">
                                        ' . $value->phone . '</h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 m-0 fs-14 ">
                                        ' . date('M d,Y', strtotime($value->date)) . ' | '. strtoupper($value->time) . '</h6>
                                    </td>

                                    <td id="td_' . $value->id . '">
                                        <h6 class="mb-0 m-0 fs-14 ">
                                        ' . $status . '</h6>
                                    </td>
                                    <td>
        
                                       
                                        <div class="btn-group btn-group-sm" role="group">
                                        <a  href="/appointment/detail/page/' . $value->unique_code . '" class="btn btn-info btn-sm">Details</a>
                                        <a  class="btn btn-danger text-white btnDelete" id="' . $value->id . '">Delete</a>
                                    </div>
                                    </div>
                                        
                                    </td>
                                </tr>
                            ';
                    }
                    return response()->json(['status' => 'success', 'rows' => $html]);
                } else {
                    return response()->json(['status' => 'fail', 'msg' => 'No Form Found!']);
                }
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'fail',
                    'msg' => $e->getMessage()
                ], 200);
            }
        }
    //dashbaord building appointments list
    public function dashbaordAppointmentList()
    {
        try {

            $appointments = Appointment::where('site_id', Auth::user()->site_id)->orderBy('id', 'DESC')->get();
            if (isset($appointments) && sizeof($appointments) > 0) {
                $html = '';
                foreach ($appointments as $value) {
                    if ($value->status == "pending") {
                        $status = '<span class="badge bg-warning text-white p-1" style="border-radius:10px">' . ucwords($value->status) . '</span>';
                    } elseif ($value->status == "check_in") {
                        $status = '<span class="badge bg-primary  text-white p-1" style="border-radius:10px">Checked In</span>';
                    } elseif ($value->status == "decline") {
                        $status = '<span class="badge bg-danger  text-white p-1" style="border-radius:10px">' . ucwords($value->status) . '</span>';
                    }else{
                        $status = '<span class="badge bg-success text-white p-1" style="border-radius:10px">Approved</span>';
                    }
                    $html .= '
                                <tr class="border-bottom" id="row' . $value->id . '" data-id="' . $value->id . '"> 
                                    <td>
                                        <h6 class="mb-0 m-0 fs-14 fw-semibold">
                                        ' . ucwords($value->user->first_name) . ' ' . ucwords($value->user->last_name) . '
                                            </h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 m-0 fs-14 fw-semibold">
                                        ' . ucwords($value->name) . '
                                            </h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 m-0 fs-14 ">
                                        ' . ucwords($value->email) . '</h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 m-0 fs-14 ">
                                        ' . $value->phone . '</h6>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 m-0 fs-14 ">
                                        ' . date('M d,Y', strtotime($value->date)) . ' | ' . strtoupper($value->time) . '</h6>
                                    </td>

                                    <td id="td_' . $value->id . '">
                                        <h6 class="mb-0 m-0 fs-14 ">
                                        ' . $status . '</h6>
                                    </td>
                                    <td>
        
                                       
                                        <div class="btn-group btn-group-sm" role="group">
                                        <a  href="/appointment/detail/' . $value->unique_code . '" class="btn btn-info btn-sm">Details</a>
                                        <a  class="btn btn-danger text-white btnDelete" id="' . $value->id . '">Delete</a>
                                    </div>
                                    </div>
                                        
                                    </td>
                                </tr>
                            ';
                }
                return response()->json(['status' => 'success', 'data' => $html]);
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'No Form Found!']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }
}
