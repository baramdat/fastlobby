<?php



namespace App\Http\Controllers;



use Exception;

use Carbon\Carbon;

use App\Models\Site;

use App\Models\User;
use App\Models\Appointment_details;


use Twilio\Rest\Client;

use App\Models\Appointment;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Mail\externalVisitor;

use App\Events\QrNotification;

use App\Mail\visitorConfirmation;

use App\Models\WalkinAppointment;

use Illuminate\Support\Facades\DB;

use App\Mail\externalVisitorQrCode;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class AppointmentController extends Controller

{

    //add


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

            $today = Carbon::now();
            $app = new Appointment();
            $app->name = $request->name;
            $app->email = $request->email;
            $app->phone = $request->phone;
            $app->date = $request->date;
            $app->time = $request->time;
            $app->tenant_id = Auth::user()->id;
            $app->site_id = auth()->user()->site_id;
            $app->unique_code = $this->generateUniqueCode();
            $app->created_at = $today;
            $app->save();
            if ($app) {
                $ap = Appointment::find($app->id);
                $link =  $ap->unique_code;

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
                $account_sid = config('services.twilio.sid');
                $auth_token = config('services.twilio.token');
                $twilio_number = config('services.twilio.phone');

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


                // Mail::send('templates.email.visitor_register_invitation', ['client'=>$client,'visitor'=>$ap,'visitorId'=>$visitor_id], function ($message) use ($ap) {
                //     $message->to($ap->email);
                //     $message->subject('Visiting Invitation');
                //     $message->from(env('MAIL_FROM_ADDRESS'), 'VM-Platform');
                // });


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




    public function externalAppointmentCreate(Request $request)

    {

        try {

            $validator = Validator::make($request->all(), [

                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'tenant' => 'required',

            ]);



            if ($validator->fails()) {

                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }



            $today = Carbon::now();

            $app = new Appointment();
            $newDateTime = Carbon::now()->subHour()->format('h');
            $current = Carbon::now();
            $app->name = $request->name;
            $app->email = $request->email;
            $app->phone = $request->phone;

             $app->time = $newDateTime.'-'.$current->format('h a');

            $app->tenant_id = $request->tenant;
            $app->date = $today;
            $app->visiting_address = $request->address;

            $app->site_id = $request->site_id;
            $app->type = 'walkin';
            $app->created_at = $today;
            $app->unique_code = $this->generateUniqueCode();
            $app->save();

            if ($app) {

                $ap = Appointment::find($app->id);

                $client = User::find($ap->tenant_id);

                $site = $client->site->name;
                $link = $ap->unique_code;
                $user = User::where('id', $app->tenant_id)->first();
                QrCode::format('png')->size(200)->generate($link, 'images/codes/' . $ap->unique_code . '.png');
                $img_url = ('images/codes/' . $ap->unique_code . '.png');

                DB::table('appointments')->where('id', $ap->id)->update(["qr_code" => $img_url]);
                if (isset($user->email)) {

                    $data = [];

                    $data["visitor_name"] = $ap->name;

                    $data["tenant_name"] = $user->first_name . " " . $user->last_name;

                    $data["site"] = $site;

                    $data["image_url"] = asset($ap->qr_code);

                    $data["id"] = $ap->id;

                    Mail::to($user->email)->send(new externalVisitor($data));
                }

                if (isset($user->phone)) {

                    //twillo sms
                    $account_sid = config('services.twilio.sid');
                    $auth_token = config('services.twilio.token');
                    $twilio_number = config('services.twilio.phone');

                    $siteName = $client->site->name;
                    $receiverNumber = $user->phone;
                    $url = env('APP_URL') . '/external/visitor/detail/' . $app->id;
                    $message =  "You have a new visitor " . $ap->name . " arrived at the door of site " . $siteName . ". Please click link to approve or decline the appointment. " . $url;

                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create($receiverNumber, [
                        'from' => $twilio_number,
                        'body' => $message
                    ]);
                }
                return response()->json(['status' => 'success', 'msg' => 'Appointment request has been sent']);
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'Failed to request an appointment']);
            }
        } catch (Exception $e) {

            return response()->json([

                'status' => 'fail',

                'msg' => $e->getMessage()

            ], 200);
        }
    }
    public function requestNewQr($id)
    {
        try {
            $app = Appointment::where('unique_code', $id)->first();

            if ($app) {
                $app->status = 'pending';
                $app->save();
                // $client = User::find($app->tenant_id);

                $site = Site::find($app->site_id);

                $user = User::where('id', $app->tenant_id)->first();

                if (isset($user->email)) {

                    $data = [];

                    $data["visitor_name"] = $app->name;

                    $data["tenant_name"] = $user->first_name . " " . $user->last_name;

                    $data["site"] = $site->name;

                    $data["image_url"] = asset($app->qr_code);

                    $data["id"] = $app->id;

                    Mail::to($user->email)->send(new externalVisitorQrCode($data));
                }

                if (isset($user->phone)) {

                    //twillo sms
                    $account_sid = config('services.twilio.sid');
                    $auth_token = config('services.twilio.token');
                    $twilio_number = config('services.twilio.phone');

                    $siteName = $site->name;
                    $receiverNumber = $user->phone;
                    $url = env('APP_URL') . '/external/visitor/qr/request/' . $app->id;
                    $message =  "You have a new visitor " . $app->name . " arrived at the door of site " . $siteName . ". Please click link to approve or decline the appointment. " . $url;

                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create($receiverNumber, [
                        'from' => $twilio_number,
                        'body' => $message
                    ]);
                }
                return redirect(url('appointment/detail/').'/'.$id);
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'Failed to request an appointment']);
            }
        } catch (Exception $e) {

            return response()->json([

                'status' => 'fail',

                'msg' => $e->getMessage()

            ], 200);
        }
    }

    //appointment detail page view
    public function detailPage($id, Request $req)
    {

        $app = Appointment::where('unique_code', $id)->first();
        if ($app) {
            $client = User::find($app->tenant_id);
            return view('templates.appointment.direct_visitor_detail_page', compact('client', 'app'));
        } else {
            return view('templates/error');
        }
    }


    //add qr to sites

    public function addQr(Request $request)
    {

        try {

            $sites = Site::all();

            if (isset($sites) && sizeof($sites)) {

                foreach ($sites as $site) {

                    $site_id = Crypt::encryptString($site->id);

                    $link = url("external/appointment/create/" . $site_id);

                    QrCode::format('png')->size(200)->generate($link, 'images/codes/' . $site->id . '.png');

                    $img_url = ('images/codes/' . $site->id . '.png');

                    if ($site->qr_code == NULL) {

                        DB::table('sites')->where('id', $site->id)->update(["qr_code" => $img_url]);
                    }

                    return 'Done';
                }
            } else {

                return view('templates.error');
            }
        } catch (Exception $e) {

            return view('templates.error');
        }
    }





    public function guardRecentAppointment()

    {

        try {

            $today = Carbon::now()->format('Y-m-d');

            $site = User::find(auth()->user()->id);

            $clients = User::whereHas('roles', function ($q) {

                $q->where('name', 'Tenant');
            })->where('site_id', $site->site->id)->get();

            if ($clients) {

                foreach ($clients as $client) {

                    $clientIds[] = $client->id;
                }

                $apps = Appointment::whereIn('tenant_id', $clientIds)->whereDate('created_at', $today)->orderBy('id', 'DESC')->get();

                if ($apps) {

                    $html = "";

                    foreach ($apps as $key => $app) {

                        if ($app->status == "pending") {

                            $status = '<span class="badge bg-warning text-white p-1" style="border-radius:10px">' . ucwords($app->status) . '</span>';
                        } elseif ($app->status == "check_in") {

                            $status = '<span class="badge bg-primary text-white p-1" style="border-radius:10px">Checked In</span>';
                        } elseif ($app->status == "decline") {

                            $status = '<span class="badge bg-danger text-white p-1" style="border-radius:10px">' . ucwords($app->status) . '</span>';
                        }

                        $html .= '><tr>

                        <td><b>' . ucwords($app->user->first_name) . ' ' . ucwords($app->user->last_name) . '</b></td>

                        <td><b>' . ucwords($app->user->email) . '</b></td>

                        <td><b>' . ucwords($app->name) . '</b></td>

                        <td><b>' . ucwords($app->email) . '</b></td>

                        <td><b>' . date('M d,Y', strtotime($app->date)) . '</b></td>

                        <td><b>' . strtoupper($app->time) . '</b></td>

                        <td><b>' . $status . '</b></td>

                        </tr>';
                    }

                    return response()->json(['status' => 'success', 'data' => $html]);
                }
            } else {

                return response()->json(['status' => 'fail', 'msg' => 'No data found!']);
            }
        } catch (Exception $e) {

            return response()->json(['status' => 'fail', 'msg' => $e->getMessage(), 'line' => $e->getLine()]);
        }
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

            $result = $result->where('tenant_id', Auth::user()->id);

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

    //external appointment trhough unique code
    public function externalAppointmentPage(Request $request)
    {
        try {
            $code = $request->data;
            $unique = substr($code, 0, 2);
            if ($unique = 'ST') {
                $site  = Site::where('unique_code', $unique)->first();
                $url = env('APP_URL') . '/external/new/appointment/' . $site->unique_code;
                return response()->json(['status' => 'success', 'url' => $url]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }
    //External appointment schedule page
    public function externalAppointmentForm($id)
    {
        try {
            // $site_id = Crypt::decrypt($id);
            $site = Site::where('unique_code', $id)->first();
            if ($site) {
                return view('templates/external/appointment', compact('site'));
            } else {
                return view('templates/error')->with(['msg' => 'Sites not found!']);
            }
        } catch (Exception $e) {
            return view('templates/error');
        }
    }



    //External appointment page

    public function externalVisitorDetailPage($id)

    {

        $visitor = Appointment::find($id);

        if ($visitor) {

            $client = User::where('id', $visitor->tenant_id)->first();

            return view('templates.external.visitor_approval', ['visitor' => $visitor, 'client' => $client]);
        } else {

            return view('templates.error');
        }
    }

    public function externalVistorRequest($id)
    {
        $visitor = Appointment::find($id);

        if ($visitor) {

            $client = User::where('id', $visitor->tenant_id)->first();

            return view('templates.external.visitor_qr_request', ['visitor' => $visitor, 'client' => $client]);
        } else {

            return view('templates.error');
        }
    }


    // list

    public function list(Request $request)

    {

        try {


            $filterSearch = $request->filterSearch;

            $filterLength = $request->filterLength;

            $filterStatus = $request->filterStatus;
            $filterDate = $request->filterDate;
            $filterPhone = $request->filterPhone;

            // $filterTitle=$request->filterTitle;

            // $filterLength=$request->filterLength;

            $result = Appointment::query();

            $result = $result->where('tenant_id', Auth::user()->id);

            if (isset($filterSearch) &&  $filterSearch != '') {

                $result = $result->where('name', 'like', '%' . $filterSearch . '%');
            }
            if (isset($filterStatus) &&  $filterStatus != 'all') {

                $result = $result->where('status', $filterStatus);
            }
            if (isset($filterDate) && $filterDate != '') {
                $date = explode('-', $filterDate);
                $from = Carbon::createFromFormat('m/d/Y', trim($date[0]));
                $to = Carbon::createFromFormat('m/d/Y', trim($date[1]));
                if ($from != $to) {
                    $result = $result->whereBetween('created_at', [$from, $to]);
                } else {
                    $today = \Carbon\Carbon::now()->format('Y-m-d');
                    $result = $result->whereDate('created_at', $today);
                }
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
                        $history='';
                    } elseif ($value->status == "check_in") {

                        $status = '<span class="badge bg-primary  text-white p-1" style="border-radius:10px">Checked In</span> <br>';
                        $history='<a class="btnCheckedHistory" id="' . $value->id . '" style="color: blue; font-size:12px">View History</a>';
                    } elseif ($value->status == "decline") {
                        $history='';
                        $status = '<span class="badge bg-danger  text-white p-1" style="border-radius:10px">' . ucwords($value->status) . '</span>';
                    } else {
                        $history='';
                        $status = '<span class="badge bg-success  text-white p-1" style="border-radius:10px">Approved</span>';
                    }

                    $html .= '

                            <tr class="border-bottom" id="row' . $value->id . '" data-id="' . $value->id . '"> 

                                

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

                                    ' . date('M d,Y', strtotime($value->date)) . '</h6>

                                </td>

                                <td>

                                    <h6 class="mb-0 m-0 fs-14 ">

                                    ' . strtoupper($value->time) . '</h6>

                                </td>

                                <td id="td_' . $value->id . '">

                                    <h6 class="mb-0 m-0 fs-14 ">

                                    ' . $status . '</h6>
                                    
                                 '.$history.'
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



    public function detail($id, Request $req)
    {
        $app = Appointment::where('unique_code', $id)->first();
        if ($req->external == 1) {
            event(new QrNotification($id));
        }
        if ($app) {
            $client = User::find($app->tenant_id);
            return view('templates.appointment.register_visitor_detail', compact('client', 'app'));
        } else {
            return view('templates/error');
        }
    }



    public function informClient(Request $request)
    {

        try {

            $visitor = Appointment::find($request->id);
            
            $appointment_details=Appointment_details::where('appointment_id',$request->id)->first();
            if($appointment_details){
                $app_details=new Appointment_details();
                $newDateTime = Carbon::now()->subHour()->format('h');
                $current = Carbon::now();
                $app_details->appointment_id=$request->id;
                $app_details->check_in_time=$newDateTime.'-'.$current->format('h a');
                $app_details->save();
                $visitor->time=$newDateTime.'-'.$current->format('h a');
            }else{
                $app_details=new Appointment_details();
                $app_details->appointment_id=$request->id;
                $app_details->check_in_time=$visitor->time;
                $app_details->save();
            }
            
            $client = User::find($visitor->tenant_id);
            Mail::send('templates.email.visitor_appointment_request', ['client' => $client, 'visitor' => $visitor], function ($message) use ($client) {

                $message->to($client->email);

                $message->subject('Guest Arrived');

                $message->from(env('MAIL_FROM_ADDRESS'), 'Fastlobby');
            });

            $visitor->status = "check_in";

            $visitor->save();

            //twillo sms
            $account_sid = config('services.twilio.sid');
            $auth_token = config('services.twilio.token');
            $twilio_number = config('services.twilio.phone');



            $receiverNumber = $client->phone;



            $message = 'Your Guest (' . $visitor->name . ') Has Arrived';

            if ($receiverNumber != " ") {



                $client = new Client($account_sid, $auth_token);

                $client->messages->create($receiverNumber, [

                    'from' => $twilio_number,

                    'body' => $message

                ]);
            }
            return response()->json(['status' => 'success', 'msg' => 'Mail sent']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }



    //walkin count

    public function walkinCount(Request $request)

    {

        try {

            $filterSearch = $request->filterSearch;

            $filterLength = $request->filterLength;

            $filterStatus = $request->filterStatus;



            $result = WalkinAppointment::query();

            $result = $result->where('tenant_id', Auth::user()->id);

            if (isset($filterSearch) && $filterSearch != '') {



                $result = $result->where('name', 'like', '%' . $filterSearch . '%');
            }







            if (isset($filterStatus) &&  $filterStatus != 'all') {

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

    public function walkinVisitors(Request $request)

    {

        try {

            $filterSearch = $request->filterSearch;

            $filterLength = $request->filterLength;

            $filterStatus = $request->filterStatus;

            // $filterTitle=$request->filterTitle;

            // $filterLength=$request->filterLength;

            $result = WalkinAppointment::query();

            $result = $result->where('tenant_id', Auth::user()->id);

            if (isset($filterSearch) &&  $filterSearch != '') {

                $result = $result->where('name', 'like', '%' . $filterSearch . '%');
            }







            if (isset($filterStatus) &&  $filterStatus != 'all') {

                $result = $result->where('status', $filterStatus);
            }





            $i = 1;

            $appointments = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();

            if (isset($appointments) && sizeof($appointments) > 0) {

                $html = '';

                foreach ($appointments as $value) {

                    $visitor_id = Crypt::encryptString($value->id);

                    $html .= '

                                <tr class="border-bottom"> 

                                    <td>' . $i++ . '</td>

                                    <td>

                                        <h6 class="mb-0 m-0 fs-14 fw-semibold">

                                        ' . ucwords($value->name) . '

                                            </h6>

                                    </td>

                                    <td>

                                        <h6 class="mb-0 m-0 fs-14 ">

                                        ' . ucwords($value->gender) . '</h6>

                                    </td>

                                    <td>

                                        <h6 class="mb-0 m-0 fs-14 ">

                                        ' . $value->Country . '</h6>

                                    </td>

                                    <td>

                                    <h6 class="mb-0 m-0 fs-14 ">

                                    ' . $value->city . '</h6>

                                    </td>

                                    <td>

                                    <h6 class="mb-0 m-0 fs-14 ">

                                    ' . $value->address . '</h6>

                                    </td>

                                    <td>

                                        <h6 class="mb-0 m-0 fs-14 ">

                                        ' . $value->status . '</h6>

                                    </td>



                                    <td>

        

                                        <a  href="/walk-in-visitors/detail/' . $visitor_id . '" class="btn btn-info btn-sm">Details</a>

        

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



    //walkin visitor detail page

    public function walikInVisitorDetails($id)
    {

        $visitor_id = Crypt::decryptString($id);

        if ($visitor_id) {

            $app = WalkinAppointment::find($visitor_id);

            if ($app) {


                $client = User::where('id', $app->tenant_id)->first();

                return view('templates.appointment.walkin_visitor_detail', ['visitor' => $app, 'client' => $client]);
            } else {

                return view('templates.error');
            }
        } else {

            return view('templates.error');
        }
    }

    public function ApproveWalkInRequest(Request $request)
    {

        try {

            $app_walkin = Appointment::where('id', $request->id)->first();

            if ($app_walkin) {

                $app_walkin->status = $request->status;

                $app_walkin->save();

                if ($request->status == "approve") {

                    // $today = Carbon::now();
                    // $app = new Appointment();
                    // $app->name = $app_walkin->name;
                    // $app->email = $app_walkin->email;
                    // $app->phone = $app_walkin->phone;
                    // $app->date = $today;
                    // $app->time = '08-09 am';
                    // $app->status = 'aprove';
                    // $app->tenant_id = $app_walkin->tenant_id;
                    // $app->site_id = $app_walkin->site_id;
                    // $app->unique_code = $this->generateUniqueCode();
                    // $app->created_at = $today;
                    // $app->save();
                    $client = User::find($app_walkin->tenant_id);
                    $visitor_id = $app_walkin->unique_code;
                    $site = $client->site->name;
                    $data = [];
                    $data["visitor_name"] = $app_walkin->name;
                    $data["site"] = $site;
                    $data["image_url"] = asset($app_walkin->qr_code);
                    $data["id"] = $visitor_id;
                    Mail::to($app_walkin->email)->send(new visitorConfirmation($data));

                    //twillo sms
                    $account_sid = 'ACbe9332f45de09e658c04c6c08eb989e3';
                    $auth_token = '4a456542ab17fafb6bd146ad7d93ce1e';
                    $twilio_number = '+18152408707';
                    $site = Site::where('id', $client->site->id)->first();
                    $siteName = $site->name;
                    $receiverNumber = $app_walkin->phone;
                    $url = route('detail', ['id' => $visitor_id]);
                    $message = 'You have been invited to visit ' . $siteName . ' please click this link and check the invitation details link is:' . $url . '';

                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create($receiverNumber, [
                        'from' => $twilio_number,
                        'body' => $message
                    ]);


                    // return response()->json(['status' => 'success', 'msg' => 'Appointment added successfully']);
                    // $ap = Appointment::find($app->id);
                    // $link =  $ap->unique_code;

                    // $client = User::find($ap->tenant_id);
                    // $visitor_id = $ap->unique_code;
                    // $site = $client->site->name;

                    // QrCode::format('png')->size(200)->generate($link, 'images/codes/' . $ap->unique_code . '.png');
                    // $img_url = ('images/codes/' . $ap->unique_code . '.png');

                    // DB::table('appointments')->where('id', $ap->id)->update(["qr_code" => $img_url]);

                    // $data = [];
                    // $data["visitor_name"] = $ap->name;
                    // $data["site"] = $site;
                    // $data["image_url"] = asset($ap->qr_code); 
                    // $data["id"] = $visitor_id;
                    // Mail::to($ap->email)->send(new visitorConfirmation($data));

                    // //twillo sms
                    // $account_sid = config('services.twilio.sid');
                    // $auth_token = config('services.twilio.token');
                    // $twilio_number = config('services.twilio.phone');

                    // $site = Site::where('id', $client->site->id)->first();
                    // $siteName = $site->name;
                    // $receiverNumber = $ap->phone;
                    // $url = route('detail',['id'=>$visitor_id]);
                    // $message = 'You have been invited to visit ' . $siteName . ' please click this link and check the invitation details link is:' . $url . '';

                    // $client = new Client($account_sid, $auth_token);
                    // $client->messages->create($receiverNumber, [
                    //     'from' => $twilio_number,
                    //     'body' => $message
                    // ]);


                    // return response()->json(['status' => 'success', 'msg' => 'Appointment added successfully']);


                    return response()->json(['status' => 'success', 'msg' => 'Appointment request approved']);
                } else {
                    return response()->json(['status' => 'success', 'msg' => 'Appointment request declined']);
                }
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'Appointment not found!']);
            }
        } catch (Exception $e) {

            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }


    public function ApproveQrRequest(Request $request)
    {

        try {

            $app = Appointment::where('id', $request->id)->first();

            if ($request->status == "approve") {

                $app->status = 'aprove';
                $app->save();
                return response()->json(['status' => 'success', 'msg' => 'Appointment added successfully']);
            } else {
                $app->status = 'decline';
                $app->save();
                return response()->json(['status' => 'success', 'msg' => 'Appointment request declined']);
            }
        } catch (Exception $e) {

            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }
    public function generateUniqueCode()

    {

        do {

            $randomString = "AP-" . Str::random(15);
        } while (Appointment::where("unique_code", "=", $randomString)->first());



        return $randomString;
    }



    public function delete($id)
    {



        $app = Appointment::find($id);

        if ($app) {

            $path = asset('images/codes/');

            $qrcode = $app->id;



            if (file_exists($path . $qrcode)) {

                unlink($path . $qrcode);
            }



            $app->delete();



            return response()->json(['status' => 'success']);
        } else {



            return response()->json(['status' => 'fail']);
        }
    }





    public function walkinAppointmentList()

    {

        $site = User::find(auth()->user()->id);

        $clients = User::whereHas('roles', function ($q) {

            $q->where('name', 'Tenant');
        })->where('site_id', $site->site->id)->get();

        $clientIds = [];

        if (isset($clients) && sizeof($clients) > 0) {



            foreach ($clients as $c) {



                if (!in_array($c->id, $clientIds)) {

                    $clientIds[] = $c->id;
                }
            }



            $today = \Carbon\Carbon::now()->format('Y-m-d');

            $i = 1;



            $apps = Appointment::whereIn('tenant_id', $clientIds)->whereDate('created_at', $today)->get();

            $html = " ";



            if (isset($apps) && sizeof($apps) > 0) {



                foreach ($apps as $app) {



                    if ($app->status == "pending") {

                        $bg = "bg-warning";
                        $bg_text = "Pending";
                    } elseif ($app->status == "aprove") {

                        $bg = "bg-primary";
                        $bg_text = "Approved";
                    } elseif ($app->status == "check_in") {
                        $bg_text = "Checked In";
                        $bg = "bg-primary";
                    } else {
                        $bg_text = "Declined";
                        $bg = "bg-danger";
                    }





                    $html .= '<tr>

                            <td>' . ucwords($app->user->first_name) . ' ' . ucwords($app->user->last_name) . '</td>

                            <td>' . ucwords($app->user->email) . '</td>

                            <td>' . ucwords($app->name) . '</td>

                            <td>' . $app->phone . '</td>

                            <td>

                                <span class="badge ' . $bg . '" style="rounded-circle:10px;">' . $bg_text . '</span>

                            </td>

                        </tr>';

                    $i++;
                }



                return response()->json(['status' => 'success', 'data' => $html]);
            } else {



                return response()->json(['status' => 'fail', 'msg' => 'no appointment found!']);
            }
        } else {

            return response()->json(['status' => 'fail', 'msg' => 'Client not found!']);
        }
    }





    public function statusList(Request $request)
    {



        $app = Appointment::find($request->id);



        if ($app) {



            if ($app->status == "pending") {
                
                $status = '<span class="badge bg-warning text-white p-1" style="border-radius:10px">' . ucwords($app->status) . '</span>';
            } elseif ($app->status == "check_in") {
                
                $status = '<span class="badge bg-primary text-white p-1" style="border-radius:10px">Checked In</span> <br> <a class="btnCheckedHistory" id="' . $app->id . '" style="color: blue; font-size:12px">View History</a>';
            } elseif ($app->status == "decline") {
             
                $status = '<span class="badge bg-danger text-white p-1" style="border-radius:10px">' . ucwords($app->status) . '</span>';
            } else {
                
                $status = '<span class="badge bg-success text-white p-1" style="border-radius:10px">Approved</span>';
            }

            return response()->json(['status' => 'success', 'html' => $status]);
        }
    }


     public function checkInHistory(Request $request){
        $app = Appointment_details::where('appointment_id',$request->id)->get();



        if ($app) {

         $details='';
         $i=1;
         foreach($app as $ap){
            $details .= '<h5> '.$i.': '. $ap->check_in_time.'</h5>';
            $i++;
         }

            return response()->json(['status' => 'success', 'html' => $details]);
        }
     }


    public function AppointmentHandling($id)
    {




        return redirect('appointment/detail/' . $id);
    }


    // appointment get through unique code
    public function AppointmentDetailThroughCode(Request $request, $id)
    {
        try {
            $code = $id;
            $unique = substr($code, 0, 2);
            if ($unique = 'AP') {
                $site_id = 0;
                if (isset($request->is_external) && isset($request->site_id)) {
                    $site_id = $request->site_id;
                } else {
                    if (!is_null(Auth::user())) {
                        $user = Auth::user();
                        $site_id = $user->site_id;
                    }
                }

                $appointment  = Appointment::where('unique_code', $code)->first();
                if ($appointment->site_id == $site_id) {
                    $url = env('APP_URL') . '/appointment/detail/' . $appointment->unique_code;
                    $url = isset($request->is_external) ? $url . '?external=1' : $url;
                    return response()->json(['status' => 'success', 'url' => $url]);
                } else {
                    return response()->json(['status' => 'fail', 'msg' => 'Invitation is not for this site!']);
                }
            } elseif ($unique == 'ST') {

                $site  = Site::where('unique_code', $code)->first();
                $url = env('APP_URL') . '/external/new/appointment/' . $site->unique_code;
                return response()->json(['status' => 'success', 'url' => $url]);
            }
        } catch (Exception $e) {

            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }

    // bar code sccaner for appointments
    public function BarcodeScanner(Request $request, $id)
    {
        try {
            $code = $id;

            $unique = substr($code, 0, 2);
            if ($unique = 'AP') {
                $site_id = 0;
                if (isset($request->is_external) && isset($request->site_id)) {
                    $site_id = $request->site_id;
                } else {
                    if (!is_null(Auth::user())) {
                        $user = Auth::user();
                        $site_id = $user->site_id;
                    }
                }

                $appointment  = Appointment::where('unique_code', $code)->first();
                if ($appointment->site_id == $site_id) {

                    return response()->json(['status' => 'success', 'id' => $appointment->id, 'ap-status' => $appointment->status]);
                } else {
                    return response()->json(['status' => 'fail', 'msg' => 'Invalid code for this site!']);
                }
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'Invalid code for this site']);
            }
        } catch (Exception $e) {

            return response()->json(['status' => 'fail', 'msg' => $e->getMessage()]);
        }
    }
}
