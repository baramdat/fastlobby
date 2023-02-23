<?php

namespace App\Http\Controllers;


use Exception;
use App\Models\Site;
use App\Models\User;
use App\Models\Videos;
use App\Mail\contactTenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class VideoContent extends Controller
{

    public function addVideo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'video' => 'required|file|mimetypes:video/mp4',
            ]);
            $video = new Videos();
            $path = public_path() . '/uploads/files/videos';

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
                // retry storing the file in newly created path.
            }
            $file = $request->video;
            if ($file != '') {
                $filename = time() . '.' .  $file->getClientOriginalName();
                $file->move($path, $filename);
            }
            $video->name = $filename;
            $video->description = $request->description;
            $video->site_id = Auth::user()->site_id;
            $video->save();
            return response()->json(['status' => 'success', 'msg' => 'Video posted successfully']);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    public function updateVideo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'description' => 'required|file|mimetypes:video/mp4',
            ]);
            $video = Videos::where('id', $request->id)->first();
            $path = public_path() . '/uploads/files/videos';

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
                // retry storing the file in newly created path.
            }
            $file = $request->video;
            if ($file != '') {
                if (file_exists($path . '/' . $video->name)) {
                    @unlink($path . '/' . $video->name);
                }
                $filename = time() . '.' .  $file->getClientOriginalName();
                $file->move($path, $filename);
                $video->name = $filename;
            }
            $video->description = $request->description;
            $video->save();
            return response()->json(['status' => 'success', 'msg' => 'Video updated successfully']);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }
    public function videoCount(Request $request)
    {
        try {
            $filterSearch = $request->filterSearch;
            $filterLength = $request->filterLength;

            $result = Videos::query();
            $result = $result->where('site_id', Auth::user()->site_id);
            if (isset($filterSearch) && $filterSearch != '') {

                $result = $result->where('description', 'like', '%' . $filterSearch . '%');
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

    public function list(Request $request)
    {
        try {
            $filterSearch = $request->filterSearch;
            $filterLength = $request->filterLength;
            $result = Videos::query();
            $result = $result->where('site_id', Auth::user()->site_id);
            if (isset($filterSearch) && $filterSearch != '') {

                $result = $result->where('description', 'like', '%' . $filterSearch . '%');
            }
            $i = 1;
            $videos = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();
            if (isset($videos) && sizeof($videos) > 0) {
                $html = '';
                foreach ($videos as $video) {
                    $html .= '
                            <tr class="border-bottom"> 
                                <td>' . $i++ . '</td>
                                <td>
                                <video width="180"  height="120"  controls>
                                <source src="' . asset('/uploads/files/videos/') . '/' . $video->name . '" type="video/mp4" >
                                    </video>
                                </td>
                                <td>
                                    <h6 class="mb-0 m-0 fs-14 ">
                                    ' . ucwords($video->description) . '</h6>
                                </td>
                               
                                <td>
                                    <a  href="/building/video/edit/' . $video->id . '" class="btn btn-warning btnEdit">Edit</a>
                                    <a  class="btn btn-danger text-white btnDelete" id="' . $video->id . '">Delete</a>      
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

    public function delete($id)
    {

        try {
            $result = Videos::where('id', $id)->first();
            $path = public_path() . '/uploads/files/videos/';
            if (file_exists($path . $result->name)) {
                @unlink($path . $result->name);
            }
            if ($result->delete()) {
                return response()->json(['status' => 'success', 'msg' => 'Video has been deleted']);
            }
            return response()->json(['status' => 'fail', 'msg' => 'Failed to delete the Video']);
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
        $video = Videos::where('id', $id)->first();
        //dd($video);
        if ($video) {
            return view('templates/video_content/edit', ['video' => $video]);
        } else {
            return view('templates.404');
        }
    }

    public function allVideos($id)
    {
        $video = Videos::where('site_id', $id)->first();
        $videos = Videos::where('site_id', $id)->get();
        $arr = [];
        foreach ($videos as $vd) {
            $arr[] = $vd->name;
        }
        if ($video) {
            return view('templates/video_content/all_videos', ['video' => $video, 'array' => $arr]);
        } else {
            return view('templates.404');
        }
    }

    public function external_pages($id)
    {
        $site = Site::find($id);
        return view('templates.external_design.menu', compact('site'));
    }
    public function contact_tenant($id)
    {
        $tenants = User::role(['Tenant'])->where('site_id', $id)->get();
        return view('templates.external_design.tenant', compact('tenants'));
    }
    public function send_email(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [

                'name' => 'required',
                'email' => 'required',
                'tenant' => 'required',

            ]);
            if ($validator->fails()) {

                return response()->json(['status' => 'fail', 'msg' => $validator->errors()->all()]);
            }
            $user = User::where('id', $request->tenant)->first();

            if (isset($user->email)) {

                $data = [];
                $data["visitor_name"] = $request->name;
                $data["visitor_email"] = $request->email;
                $data["visitor_message"] = $request->message;
                $data["tenant_name"] = $user->first_name ;
                Mail::to($user->email)->send(new contactTenant($data,$request->email));

                return response()->json(['status' => 'success', 'msg' => 'Your message has been sent']);
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'Failed to send message']);
            }
        } catch (Exception $e) {

            return response()->json([

                'status' => 'fail',

                'msg' => $e->getMessage()

            ], 200);
        }
    }
}
