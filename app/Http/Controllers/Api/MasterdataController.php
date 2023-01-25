<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        //membuat validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'alias' => 'required',
            'phone' => 'required|min:10|confirmed',
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

        $insert = DB::Table('bussiness')->insert(
            [
                'id' => Str::uuid()->toString(16),
                // 'id'=> random_bytes(16),
                'name' => $request->name,
                'alias' => $request->alias,
                'image_url' => $request->image_url,
                'is_close'=> $request->is_close,
                'url'=> $request->url,
                'price'=> $request->price,
                'phone' => $request->phone,
                'display_phone' => $request->display_phone,
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
}
