<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;


class roleController extends Controller
{

    public function addRole(Request $request){
        
        $role =Role::create([
            'name'=>ucwords($request->name),
            'guard_name'=>'web'
        ]);
        return response()->json(['status'=>'success','msg'=>'Role has been added successfully']);
    }

    // edit role
    public function editRole(Request $request){
        $role = Role::where('id',$request->role)->first();        // $request->role  request to ajax
        // dd($role);
        if(!$role){
            return response()->json([
                'status'=>'error',
                'message'=>'Role not Found'
            ],400);
        }else{
            return response()->json([
                'status'=>'success',
                'data'=>$role
            ]);
        }

    }

    public function updateRole(Request $request){
        $role= Role::where('id',$request->role_id)->first();      // $request->role_id  match on blade in form and ajax
        if(!$role){
            return response()->json([
                'status'=>'error',
                'message'=>'user not Found'
            ],400);
        }else{
            $role->update([
                'name'=>$request->name
            ]);

            if ($role) {
                return response()->json([
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'user can not be Update'
                ], 500);
            }


        }

    }

    // delete role
    public function deleteRole(Request $request)
    {

        $role = Role::find($request->role);  // $request->role  request to ajax

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role not found'
            ], 400);
        }

        if ($role->delete()) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'role can not be deleted'
            ], 500);
        }
    }
    
}
