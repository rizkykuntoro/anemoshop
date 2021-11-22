<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LogEmailSender extends Model
{
    protected $table = 'email_sender_log';
    public $timestamps = false;

    public static function getList()
    { 
        $response = [];
        $list = new LogEmailSender;
        $list = $list->get();

        foreach ($list as $key => $value) {
            foreach ($value->attributes as $k => $v) {
                $response[$key][$k] = $v;
            }
        }

        return $response;
    }

}