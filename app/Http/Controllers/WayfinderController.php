<?php

namespace App\Http\Controllers;

use App\Models\Wayfinder;
use Illuminate\Http\Request;
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
}
