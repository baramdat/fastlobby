<?php

namespace App\Http\Controllers;

use App\Models\Wayfinder;
use Illuminate\Http\Request;
use App\Models\WayfinderLoctaions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class WayfinderController extends Controller
{
    public function index(Request $request){
        try {
            
            $images=$request->file;
           // $fileName   = time() . '.' . $image->getClientOriginalExtension();

            $image = new Wayfinder();
            $path = public_path() . '/uploads/files/wayfinder';

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
                // retry storing the file in newly created path.
            }
            if ($image != '') {
              
                $filename = time() . '.' .  $images->getClientOriginalExtension();
                $images->move($path, $filename);
                $image->image = $filename;
            }
            $image->description = $request->description;
            
            $image->wayfinder_location= $request->location;
            $image->save();
            $doc_url=asset('uploads/files/wayfinder').'/'.$images->getClientOriginalExtension();
            return response()->json(['status' => 'success', 'doc_url'=>$doc_url,'msg' => 'Image added successfully']);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    public function wayFinderLocation(Request $request){
        try {
            
            $location = new WayfinderLoctaions();
 
            $location->start = $request->start;
            $location->end = $request->end;
            $location->site_id =  Auth::user()->site_id ;
            $location->save();
            return response()->json(['status' => 'success', 'loc_id'=>$location->id]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }
    
    public function wayFinderCount(Request $request){
        try {
            $filterName = $request->filterName;
            $filterStatus = $request->filterStatus;
            $result = WayfinderLoctaions::query();
            $result =$result->where('site_id',Auth::user()->site_id) ;
            if ($filterName != '') {
                $result = $result->where('end', 'like', '%' . $filterName . '%');
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
     
    public function wayFinderList(Request $request){
        try {
            $filterName = $request->filterName;
            $filterStatus = $request->filterStatus;
            $filterLength = $request->filterLength;
            $result = WayfinderLoctaions::query();
            $result =$result->where('site_id',Auth::user()->site_id) ;
            if ($filterName != '') {
                $result = $result->where('end', 'like', '%' . $filterName . '%');
            }

            
            $i = 1;


            $list = $result->take($filterLength)->skip($request->offset)->orderBy('id', 'DESC')->get();
            if (isset($list) && sizeof($list) > 0) {
                $html = '';
                foreach ($list as $lis) {
                   
                    $html .= '
                        <tr class="border-bottom"> 
                            <td>
                            <h6 class="mb-0 m-0 fs-14 ">' . ucwords($lis->start) . '</h6>
                            </td>

                            <td>
                                <h6 class="mb-0 m-0 fs-14 ">' . ucwords($lis->end) . '</h6>
                            </td>

                          
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a  href="/wayfinder/details/' . $lis->id . '" class="btn btn-warning btnEdit">Start Journey</a>
                                </div>
                            </td>
                        </tr>
                    ';
                }
                return response()->json(['status' => 'success', 'rows' => $html, 'data' => $list]);
            } else {
                return response()->json(['status' => 'fail', 'msg' => 'No Loction Found!']);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    public function wayFinderDetails($id){
        $wayfinder = Wayfinder::where('wayfinder_location', $id)->first();
        $wayfinders = Wayfinder::where('wayfinder_location', $id)->get();
        $arr = [];
        $text=[];
        foreach ($wayfinders as $vd) {
            $arr[] = $vd->image;
            $text[]=$vd->description?$vd->description:'';
        }

        // if ($wayfinder) {
            return view('templates/wayfinder/detail', ['wayfinder' => $wayfinder, 'array' => $arr,'text'=>$text]);
        // } else {
        //     return view('templates.404');
        // }
    }
}
