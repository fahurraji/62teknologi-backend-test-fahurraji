<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BussinessModel;

class BussinessController extends Controller
{
    //
    public function search(Request $request)
    {
        $buss = new BussinessModel;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $term = $request->term;
        $price = $request->price;
        $rating = $request->rating;
        $limit = $request->limit;
        $offset = $request->offset;
        $orderBy = $request->orderBy;
        $phone = $request->phone;
        if($latitude== null && $longitude == null)
        {
            $latitude = -6.188767871517751;
            $longitude = 106.79638043944114;
        }
        $data = $buss->search($term,$price,$rating,$phone,$limit,$offset,$orderBy);
           
        if($data)
        {      
            foreach($data as $r=>$v){
                
                $coordinat = $buss->coordinat($data[$r]->id);
                $location = $buss->location($data[$r]->id);
                $category = $buss->category($data[$r]->id);
                $transaksi = $buss->transaksi($data[$r]->id);
                $dist = $buss->jarak($latitude,$longitude,$data[$r]->id);
                $rev = $buss->review($data[$r]->id);
                
                if($location->IsNotEmpty()){
                    $loc = $location['0'];
                
                }
                $close = ($data[$r]->is_close == 1)? true : false;
                $list[]=array(
                    "id"=> $data[$r]->id,
                    "name"=> $data[$r]->name,
                    "alias"=> $data[$r]->alias,
                    "image_url"=>  $data[$r]->image_url,
                    "url"=>  $data[$r]->url,
                    "review_count"=> $rev['0']->review,
                    "category" => $category,
                    "is_close"=> $close,
                    "rating"=>(int)$data[$r]->rating,
                    "coordinates" =>$coordinat['0'],
                    "transaction"=>$transaksi,
                    "price"=> $data[$r]->price,
                    "location"=>array(
                        "address1"=> $loc->address1,
                        "address2"=> $loc->address2,
                        "address3"=> $loc->address3,
                        "city"=> $loc->city,
                        "zip_code"=> $loc->zip_code,
                        "country"=> $loc->country,
                        "state"=> $loc->state,
                        'display_address'=>[
                            $loc->address1,$loc->address2,$loc->address3,$loc->city,$loc->zip_code,$loc->country
                        ]
                    ),
                    "phone"=> $data[$r]->phone,
                    "display_phone"=> $data[$r]->display_phone,
                    "distance"=> (double)$dist['0']->jarak
                );
            }
            $records = array(
                "Bussiness"=>
                    $list, 
                
                "region" => array(
                    "center"=>array(
                        "latitude" => $latitude,
                        "longitude" => $longitude
                    )
                 ),
                 "total" => count($data)
             );              
         return response()->json($records);
        }else
        {
            $records = array(
                "Bussiness" => [],
                "region" => array(
                     "center"=>array(
                         "latitude" => $latitude,
                         "longitude" => $longitude
                     )
                 ),
                 "total" => 0
             );         
            return response()->json($records);
        }
    }

    public function deleteBussiness(Request $request)
    {
        $id = $request->id;
        $result = DB::Table('bussiness')->where('id',$id)->delete();
        if($result)
        {
            $exec = DB::Table('bussiness_location')->where('buss_id',$id)->delete();
            $exec = DB::Table('bussiness_review')->where('buss_id',$id)->delete();
            $exec = DB::Table('buss_category')->where('buss_id',$id)->delete();
            $exec = DB::Table('buss_trans')->where('buss_id',$id)->delete();
            if($exec)
            {
                return response()->json([
                    'code'=>200,
                    'success' => true,
                    'message' => 'Delete Data Success'
                ], 200);
            }
        }
        else
        {
            return response()->json([
                'code'=>400,
                'success' => false,
                'message' => 'Delete Data failed'
            ], 400);
        }
    }

    public function getBussiness(Request $request)
    {
        $buss = new BussinessModel;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $id = $request->id;
        if($latitude== null && $longitude == null)
        {
            $latitude = -6.188767871517751;
            $longitude = 106.79638043944114;
        }
        
        if($request->id)
        {
            // jika mengirimkan parameter id
            $data = $buss->byId($request->id);
        }else
        {
            // jika tidak mengirimkan parameter id
            $data = $buss->semua();
        }
        if($data)
        {      
            foreach($data as $r=>$v){
                
                $coordinat = $buss->coordinat($data[$r]->id);
                $location = $buss->location($data[$r]->id);
                $category = $buss->category($data[$r]->id);
                $transaksi = $buss->transaksi($data[$r]->id);
                $dist = $buss->jarak($latitude,$longitude,$data[$r]->id);
                $rev = $buss->review($data[$r]->id);
                
                if($location->IsNotEmpty()){
                    $loc = $location['0'];
                
                }
                $close = ($data[$r]->is_close == 1)? true : false;
                $list[]=array(
                    "id"=> $data[$r]->id,
                    "name"=> $data[$r]->name,
                    "alias"=> $data[$r]->alias,
                    "image_url"=>  $data[$r]->image_url,
                    "url"=>  $data[$r]->url,
                    "review_count"=> $rev['0']->review,
                    "category" => $category,
                    "is_close"=> $close,
                    "rating"=>(int)$data[$r]->rating,
                    "coordinates" =>$coordinat['0'],
                    "transaction"=>$transaksi,
                    "price"=> $data[$r]->price,
                    "location"=>array(
                        "address1"=> $loc->address1,
                        "address2"=> $loc->address2,
                        "address3"=> $loc->address3,
                        "city"=> $loc->city,
                        "zip_code"=> $loc->zip_code,
                        "country"=> $loc->country,
                        "state"=> $loc->state,
                        'display_address'=>[
                            $loc->address1,$loc->address2,$loc->address3,$loc->city,$loc->zip_code,$loc->country
                        ]
                    ),
                    "phone"=> $data[$r]->phone,
                    "display_phone"=> $data[$r]->display_phone,
                    "distance"=> (double)$dist['0']->jarak
                );
                
            }
            
            $records = array(
                "Bussiness"=>
                    $list, 
                
                "region" => array(
                    "center"=>array(
                        "latitude" => $latitude,
                        "longitude" => $longitude
                    )
                 ),
                 "total" => count($data)
             );              
         return response()->json($records);
        }else
        {
            $records = array(
                "Bussiness" => [],
                "region" => array(
                     "center"=>array(
                         "latitude" => $latitude,
                         "longitude" => $longitude
                     )
                 ),
                 "total" => 0
             );         
            return response()->json($records);
        }
         
    }

  
}
