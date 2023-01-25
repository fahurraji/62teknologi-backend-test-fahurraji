<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BussinessController extends Controller
{
    //
    public function search()
    {
                
    }

    public function addBussiness()
    {

    }

    public function editBussiness()
    {

    }

    public function deleteBussiness()
    {

    }

    public function getBussiness()
    {
        $data = DB::Table('bussiness')->get();
        // dd($data);
        if($data)
        {
            foreach($data as $r=>$v){
                $coordinat = "";
                $location="";
                $category = "";
                $transaksi = "";
            }
            $records = array(
                "Bussiness" => $data,
                "region" => array(
                     "center"=>array(
                         "latitude" => "",
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

    public function Success()
    {
        $records = array(
            "id"=> "H4jJ7XB3CetIr1pg56CczQ",
            "alias"=> "levain-bakery-new-york",
            "name"=> "Levain Bakery",
            "image_url"=> "https://s3-media3.fl.yelpcdn.com/bphoto/DH29qeTmPotJbCSzkjYJwg/o.jpg",
            "is_closed"=> false,
            "url"=> "https://www.yelp.com/biz/levain-bakery-new-york?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
            "review_count"=> 9427,
            // "categories"=> [
            //     {
            //         "alias": "bakeries",
            //         "title": "Bakeries"
            //     }
            // ],
            "rating"=> 4.5,
            // "coordinates"=> {
            //     "latitude": 40.779961,
            //     "longitude": -73.980299
            // },
            // "transactions"=> [],
            "price"=> "$$",
            // "location"=> {
            //     "address1": "167 W 74th St",
            //     "address2": "",
            //     "address3": "",
            //     "city": "New York",
            //     "zip_code": "10023",
            //     "country": "US",
            //     "state": "NY",
            //     "display_address": [
            //         "167 W 74th St",
            //         "New York, NY 10023"
            //     ]
            // },
            "phone"=> "+19174643769",
            "display_phone"=> "(917) 464-3769",
            "distance"=> 8115.903194093832
        );     
    }

    public function Unautorazion()
    {
        $data = array(
            "error"=>array(
                "code"=> "UNAUTHORIZED_ACCESS_TOKEN",
                "description"=> "The access token provided is not currently able to query this endpoint."
            )
        );
    }
    // error 400 invalid request
    public function Invalid_request()
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
    public function Internal_error()
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
    public function Invalid_token()
    {
        $data = array(
            "error"=>array(
                "code"=> "TOKEN_INVALID",
                "description"=> "Invalid access token or authorization header."
            )
        );
    }
    // error 403
    public function Aut_error()
    {
        $data = array(
            "error"=>array(
                "code"=> "AUTHORIZATION_ERROR",
                "description"=> "Authorization Error"
            )
        );
    }
    // error 404
    public function Resource_notfound()
    {
        $data = array(
            "error"=>array(
                "code"=> "AUTHORIZATION_ERROR",
                "description"=> "Authorization Error"
            )
        );
    }
    // error 413
    public function Payload_too_large()
    {
        $data = array(
            "error"=>array(
                "code"=> "PAYLOAD_TOO_LARGE",
                "description"=> "Payload Too Large"
            )
        );
    }
    // error 429
    public function Request_large()
    {
        $data = array(
            "error"=>array(
                "code"=> "TOO_MANY_REQUESTS_PER_SECOND",
                "description"=> "You have exceeded the queries-per-second limit for this endpoint. Try reducing the rate at which you make queries."
            )
        );
    }
    // error 503
    public function Unuvailable()
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
