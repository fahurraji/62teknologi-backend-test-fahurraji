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
        if($request->id)
        {
            // jika mengirimkan parameter id
            $data = DB::Table('bussiness')->where('id',$request->id)->get();
        }else
        {
            // jika tidak mengirimkan parameter id
            $data = $this->semua();
        }
        if($data)
        {
            foreach($data as $r=>$v){
                $coordinat = $this->coordinat($data[$r]->id);

                $location="";
                $category = "";
                $transaksi = "";
            }
            $records = array(
                "Bussiness" => $data,
                "region" => array(
                     "center"=>array(
                         "latitude" => $coordinat,
                         "longitude" => ""
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
                         "latitude" => "",
                         "longitude" => ""
                     )
                 ),
                 "total" => 0
             );         
            return response()->json($records);
        }
    }


    public function editBussiness()
    {

    }

    public function deleteBussiness()
    {

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
            // $data = $buss->jarak($request->latitude,$request->longitude,$request->id);
            // print($data);exit;
        }else
        {
            // jika tidak mengirimkan parameter id
            $data = $buss->semua();
            // print_r($data);exit;
        }
        if($data)
        {
           
            
            foreach($data as $r=>$v){
                
                $coordinat = $buss->coordinat($data[$r]->id);
                $location = $buss->location($data[$r]->id);
                $category = $buss->category($data[$r]->id);
                $transaksi = $buss->transaksi($data[$r]->id);
                $dist = $buss->jarak($latitude,$longitude,$data[$r]->id);
                
                if($location->IsNotEmpty()){
                    $loc = $location['0'];
                
                }
                
                $list[]=array(
                    "id"=> $data[$r]->id,
                    "name"=> $data[$r]->name,
                    "alias"=> $data[$r]->alias,
                    "category" => $category,
                    "image_url"=>  $data[$r]->image_url,
                    "price"=> $data[$r]->price,
                    "url"=>  $data[$r]->url,
                    "phone"=> $data[$r]->phone,
                    "display_phone"=> $data[$r]->display_phone,
                    "is_close"=> $data[$r]->is_close,
                    "coordinates" =>$coordinat['0'],
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
                    "transaction"=>$transaksi,
                    "distance"=> $dist['0']->jarak
                );
                // print_r($list);exit;
            }
            // exit;
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

   
    private function Unautorazion()
    {
        $data = array(
            "error"=>array(
                "code"=> "UNAUTHORIZED_ACCESS_TOKEN",
                "description"=> "The access token provided is not currently able to query this endpoint."
            )
        );
    }
    // error 400 invalid request
    private function Invalid_request()
    {
        $data = array(
            "error"=>array(
                "code"=>"INVALID_REQUEST",
                "description"=>"invalid request"
            )
        );
        return $data;
        // $response = $response()->json($data);
    }
    // error 500 
    private function Internal_error()
    {
        $data = array(
            "error"=>array(
                "code"=> "INTERNAL_ERROR",
                "description"=> "Something went wrong internally, please try again later."            
            )
        );
        $response = $response()->json($data);
    }
    // error 401 token invalid
    private function Invalid_token()
    {
        $data = array(
            "error"=>array(
                "code"=> "TOKEN_INVALID",
                "description"=> "Invalid access token or authorization header."
            )
        );
    }
    // error 403
    private function Aut_error()
    {
        $data = array(
            "error"=>array(
                "code"=> "AUTHORIZATION_ERROR",
                "description"=> "Authorization Error"
            )
        );
    }
    // error 404
    private function Resource_notfound()
    {
        $data = array(
            "error"=>array(
                "code"=> "AUTHORIZATION_ERROR",
                "description"=> "Authorization Error"
            )
        );
    }
    // error 413
    private function Payload_too_large()
    {
        $data = array(
            "error"=>array(
                "code"=> "PAYLOAD_TOO_LARGE",
                "description"=> "Payload Too Large"
            )
        );
    }
    // error 429
    private function Request_large()
    {
        $data = array(
            "error"=>array(
                "code"=> "TOO_MANY_REQUESTS_PER_SECOND",
                "description"=> "You have exceeded the queries-per-second limit for this endpoint. Try reducing the rate at which you make queries."
            )
        );
    }
    // error 503
    private function Unuvailable()
    {
        $data = array(
            "error"=>array(
                "code"=> "SERVICE_UNAVAILABLE",
                "description"=> "Service Unavailable"
            )
        );
    }

    private function getDistance($lat1,$lon1)
    {

    }

}
