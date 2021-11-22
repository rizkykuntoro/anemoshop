<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LogMidtrans extends Model
{
    protected $table = 'response_midtrans_log';
    public $timestamps = false;

    public static function getList()
    { 
        $response = [];
        $list = new LogMidtrans;
        $list = $list->get();

        foreach ($list as $key => $value) {
            foreach ($value->attributes as $k => $v) {
                $response[$key][$k] = $v;
            }
        }

        return $response;
    }

}