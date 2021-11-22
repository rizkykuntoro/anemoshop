<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Daftar;

class Webinar extends Model
{
    protected $table = 'webinar';
    public $timestamps = false;

    public static function getList()
    { 
        $response = [];
        $list = new Webinar;
        $list = $list->orderBy('tanggal_mulai','asc');
        $list = $list->get();

        foreach ($list as $key => $value) {
            foreach ($value->attributes as $k => $v) {
                $response[$key][$k] = $v;
            }
        }

        return $response;
    }

    public function ceklinkWA()
    {
        $list = Daftar::where('link_wa',$this->link1)->where('flag_status','<>','expired')->where('id_webinar',$this->id);
        if ($list->count() < 150) {
            return $this->link1;
        }
        $list = Daftar::where('link_wa',$this->link2)->where('flag_status','<>','expired')->where('id_webinar',$this->id);
        if ($list->count() < 150) {
            return $this->link2;
        }

        return 'penuh';
    }
}