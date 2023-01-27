<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BussinessModel extends Model
{
    use HasFactory;

    protected $table = "bussiness";
    protected $primaryKey = "id";

    public function semua()
    {
        $sql = DB::Table('bussiness AS a')
                ->selectRaw("a.id,a.name,a.alias,a.image_url,a.price,a.url,a.phone,a.display_phone,a.is_close")
                ->selectRaw("ROUND(avg(d.rating),2) as rating,(select count(*) from bussiness_review x where x.buss_id=a.id ) as review_count")
                ->join('buss_trans AS b','b.buss_id','=','a.id','LEFT')
                ->join('mst_transaction AS c','c.id','=','b.trans_id','LEFT')
                ->join('bussiness_review AS d','d.buss_id','=','a.id','LEFT')
                ->groupBy('a.id')->get();
        return $sql;

    }

    public function search($term=null,$price=null,$rating=null,$phone=null,$limit=null,$offset=null,$orderBy=null)
    {
        
        $sql = DB::Table('bussiness AS a')
                ->selectRaw("a.id,a.name,a.alias,a.image_url,a.price,a.url,a.phone,a.display_phone,a.is_close")
                ->selectRaw("ROUND(avg(d.rating),2) as rating,(select count(*) from bussiness_review x where x.buss_id=a.id ) as review_count")
                ->join('buss_trans AS b','b.buss_id','=','a.id','LEFT')
                ->join('mst_transaction AS c','c.id','=','b.trans_id','LEFT')
                ->join('bussiness_review AS d','d.buss_id','=','a.id','LEFT')
                ->when($term, function ($query, $term) {
                    return $query->whereRaw("a.name like '%$term%' or a.alias like '%$term%' ");
                })
                ->when($price, function ($query, $price) {
                    return $query->where("a.price",$price);
                })
                ->when($phone, function ($query, $phone) {
                    return $query->whereRaw(" a.phone like '%.$phone.%' ");
                })
                ->groupBy('a.id')
                ->when($rating, function ($query, $rating) {
                    return $query->having('rating','>=', $rating);
                })                
                ->when($orderBy, function ($query, $orderBy) {
                    return $query->orderBy(" a.created_at '.$orderBy.'");
                })
                ->when($offset, function ($query, $offset) {
                    return $query->offset($offset);
                })
                ->when($limit, function ($query, $limit) {
                    return $query->limit($limit);
                })
                ->get();
        return $sql;
    }

    public function review($id)
    {
        $sql = DB::Table('bussiness_review AS a')
                ->selectRaw('count(a.comment) as review')
                ->where('a.id',$id)
                ->get();
        return $sql;
    }

    public function coordinat($id)
    {
        $sql = DB::Table('bussiness AS a')
                ->selectRaw('b.latitude,b.longitude')
                ->join('bussiness_location AS b','a.id','=','b.buss_id',"left")
                ->where('a.id',$id)
                ->get();
        return $sql;
    }

    public function location($id)
    {
        $sql = DB::Table('bussiness_location')
                ->where('buss_id',$id)
                ->get();
        return $sql;
    }

    public function category($id)
    {
        $sql = DB::Table('bussiness AS a')
                ->selectRaw('c.title,c.alias')
                ->join('buss_category AS b','b.buss_id','=','a.id','LEFT')
                ->join('mst_category AS c','c.id','=','b.cat_id','LEFT')
                ->where('a.id',$id)        
                ->get();
        return $sql;
    }

    public function transaksi($id)
    {
        $sql = DB::Table('bussiness AS a')
                ->selectRaw('c.description')
                ->join('buss_trans AS b','b.buss_id','=','a.id','LEFT')
                ->join('mst_transaction AS c','c.id','=','b.trans_id','LEFT')
                ->where('a.id',$id)
                ->get();
        return $sql;
    }

    public function jarak($latitude,$longitude,$id)
    {
        
        $sql = DB::Table('bussiness AS a')
                ->selectRaw("(SELECT ROUND((6371 * ACOS (COS ( RADIANS($latitude) ) * COS( RADIANS(b.latitude) ) * COS( RADIANS(b.longitude) - RADIANS($longitude) )+ SIN ( RADIANS($latitude) ) * SIN( RADIANS(b.latitude) ))),2)) AS jarak ")
                ->join('bussiness_location AS b','b.buss_id','=','a.id','LEFT')
                ->where('a.id',$id)
                ->get();
        return $sql;
    }

    public function byId($id)
    {
        $sql = DB::Table('bussiness AS a')
                ->selectRaw("a.id,a.name,a.alias,a.image_url,a.price,a.url,a.phone,a.display_phone")
                ->selectRaw("(case when a.is_close=1 then true else false end) as is_close")
                ->selectRaw("ROUND(avg(d.rating),2) as rating,(select count(*) from bussiness_review x where x.buss_id=a.id ) as review_count")
                ->join('buss_trans AS b','b.buss_id','=','a.id','LEFT')
                ->join('mst_transaction AS c','c.id','=','b.trans_id','LEFT')
                ->join('bussiness_review AS d','d.buss_id','=','a.id','LEFT')
                ->where('a.id',$id)
                ->groupBy('a.id')->get();
        return $sql;
    }

    public function insertBussiness($act ="add", $input=array())
    {
        // $code = Str::uuid()->toString(16);
        if ($act == "add") {
            $insert =DB::Table('bussiness')
                        ->insert($input);
            return $insert;
        } 
    }

    public function  insertCategory($act ="add", $input=array())
    {
        if($act=="add"){
            $insert = DB::Table('buss_category')->insert(
                $input
            );
            return $insert;
        }
    }

    public function insertTransaksi($act ="add", $input=array())
    {
        if($act=="add")
        {
            $insert = DB::Table('buss_trans')->insert(
                $input
            );
            return $insert;
        }
    }

    public function insertLocation($act ="add", $input=array())
    {
        if($act=="add")
        {
            $insert = DB::Table('bussiness_location')->insert(
                $input
            );
            return $insert;
        }
    }
}
