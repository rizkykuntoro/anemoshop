<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Daftar;

class PromoWebinar extends Model
{
    protected $table = 'promo_webinar';
    public $timestamps = false;

    public static function getList()
    { 
        $response = [];
        $list = new PromoWebinar;
        $list = $list->orderBy('created_at','desc');
        $list = $list->get();

        foreach ($list as $key => $value) {
            foreach ($value->attributes as $k => $v) {
                $response[$key][$k] = $v;
            }
        }

        return $response;
    }
}