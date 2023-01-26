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
                ->selectRaw("a.id,a.name,a.alias,a.image_url,a.price,a.url,a.phone,a.display_phone")
                ->selectRaw("(case when a.is_close=1 then true else false end) as is_close")
                ->selectRaw("ROUND(avg(d.rating),2) as rating,(select count(*) from bussiness_review x where x.buss_id=a.id ) as review_count")
                ->join('buss_trans AS b','b.buss_id','=','a.id','LEFT')
                ->join('mst_transaction AS c','c.id','=','b.trans_id','LEFT')
                ->join('bussiness_review AS d','d.buss_id','=','a.id','LEFT')
                ->groupBy('a.id')->get();
        return $sql;

    }

    public function search()
    {

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
}
