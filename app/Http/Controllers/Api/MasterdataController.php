<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\BussinessModel;

class MasterdataController extends Controller
{
    public function addCategory(Request $request)
    {
        //membuat validation
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'alias'  => 'required'
        ]);

        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json(
                [
                    'code'=>400,
                    'success' => false,
                ]
            );
        }

        $insert = DB::Table('mst_category')->insert(
            [
                'title' => $request->title,
                'alias' => $request->alias
            ]);
        if($insert)
        {
            return response()->json([
                'code'=>200,
                'success' => true,
                'message' => 'Insert Data Success'
            ], 200);
        }else
        {
            return response()->json([
                'code'=>400,
                'success' => false,  
            ], 400);
        }
    }

    public function editCategory(Request $request)
    {
        //membuat validation
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title'     => 'required',
            'alias'  => 'required'
        ]);

        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json(
                [
                    'code'=>400,
                    'success' => false,
                ]
            );
        }

        $update = DB::Table('mst_category')->where('id', $request->id)->update(
            [
                'title' => $request->title,
                'alias' => $request->alias
            ]);
        if($update)
        {
            return response()->json([
                'code'=>200,
                'success' => true,
                'message' => 'Update Data Success'
            ], 200);
        }else
        {
            return response()->json([
                'code'=>400,
                'success' => false,  
            ], 400);
        }
    }

    public function deleteCategory(Request $request)
    {
        //membuat validation
        $validator = Validator::make($request->all(), [
            'id'     => 'required'
        ]);

        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json(
                [
                    'code'=>400,
                    'success' => false,
                ]
            );
        }

        $delete = DB::Table('mst_category')->where('id', $request->id)->delete();
        if($delete)
        {
            return response()->json([
                'code'=>200,
                'success' => true,
                'message' => 'Delete Data Success'
            ], 200);
        }else
        {
            return response()->json([
                'code'=>400,
                'success' => false,  
            ], 400);
        }
    }

    public function addBussiness(Request $request)
    {
        
        $buss = new BussinessModel;
        $code = Str::uuid()->toString();
        $input =array(
                'id' => $code,
                'name' => $request->name,
                'alias' => $request->alias,
                'image_url' => $request->image_url,
                'is_close'=> $request->is_close,
                'url'=> $request->url,
                'price'=> $request->price,
                'phone' => $request->phone,
                'display_phone' => $request->display_phone,
            );
        $insert = $buss->insertBussiness("add",$input);
        if($insert)
        {
            $cat_id = $request->cat_id;
                          
                foreach($cat_id as $k=>$v){
                    $input1[] = array(
                        'buss_id'=>$code,
                        'cat_id' => $cat_id[$k]
                    );
                }
               
            $buss->insertCategory("add",$input1);
            $trans_id = $request->trans_id;
            foreach($trans_id as $k=>$v){
                $input2[] = array(
                        'buss_id'=>$code,
                        'trans_id' => $trans_id[$k]
                );
            }
            $buss->insertTransaksi("add",$input2);
            $input3 =array(
                'buss_id'=>$code,
                'address1'=>$request->address1,
                'address2'=>$request->address2,
                'address3'=>$request->address3,
                'city'=>$request->city,
                'zip_code'=>$request->zip_code,
                'country'=> $request->country,
                'state'=>$request->state,
                'latitude'=>$request->latitude,
                'longitude'=>$request->longitude,
            );
            // print_r($input);exit;
            $buss->insertLocation("add",$input3);
            return response()->json([
                'code'=>200,
                'success' => true,
                'message' => 'Insert Data Success'
            ], 200);
        }
        else
        {
            return response()->json(
                [
                    'code'=>400,
                    'success' => false,
                ]
            );
        }
    }
}
