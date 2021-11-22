<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Webinar;

class Daftar extends Model
{
    protected $table = 'tbl_daftar';
    public $timestamps = false;

    public static function getList($request)
    { 
        $response = [];
        $list = new Daftar;

        if (isset($request['nama'])&&$request['nama']!='') {
            $list = $list->where('nama', 'like', '%' . $request['nama'] . '%');
        }

        if (isset($request['nir'])&&$request['nir']!='') {
            $list = $list->where('nir', 'like', '%' . $request['nir'] . '%');
        }

        if (isset($request['nohp'])&&$request['nohp']!='') {
            $list = $list->where('nohp', 'like', '%' . $request['nohp'] . '%');
        }

        if (isset($request['status'])&&$request['status']!=''&&$request['status']!='Semua Status') {
            $list = $list->where('flag_status', 'like', '%' . $request['status'] . '%');
        }

        if (isset($request['tipe_bayar'])&&$request['tipe_bayar']!=''&&$request['tipe_bayar']!='Semua Tipe') {
            $list = $list->where('type_bayar', 'like', '%' . $request['tipe_bayar'] . '%');
        }

        $list = $list->get();

        foreach ($list as $key => $value) {
            foreach ($value->attributes as $k => $v) {
                $response[$key][$k] = $v;
            }
        }

        return $response;
    }


    public static function getListPeserta($request)
    { 
        $response = [];
        $list = new Daftar;

        if (isset($request['nama'])&&$request['nama']!='') {
            $list = $list->where('nama', 'like', '%' . $request['nama'] . '%');
        }

        if (isset($request['nir'])&&$request['nir']!='') {
            $list = $list->where('nir', 'like', '%' . $request['nir'] . '%');
        }

        if (isset($request['status'])&&$request['status']!=''&&$request['status']!='Semua Status') {
            $list = $list->where('flag_status', 'like', '%' . $request['status'] . '%');
        }

        if (isset($request['tipe_bayar'])&&$request['tipe_bayar']!=''&&$request['tipe_bayar']!='Semua Tipe') {
            $list = $list->where('type_bayar', 'like', '%' . $request['tipe_bayar'] . '%');
        }

        $list = $list->leftJoin('tbl_pembayaran', 'tbl_pembayaran.id_daftar', '=', 'tbl_daftar.id');
        $list = $list->select(array(DB::raw('tbl_daftar.*,kode_pembayaran')));
        $list = $list->get();

        $nir_order = [];
        foreach ($list as $key => $value) {
            if ($value->promo=='true' && $value->kode_pembayaran!='') {
                if ($value->type_bayar=='permataVA') {
                    if (strpos($value->kode_pembayaran , 'PERMATA') !== false) {
                        $nir_order[$value->nir.$value->type_bayar] = $value->kode_pembayaran;
                    }
                }
                if ($value->type_bayar=='mandiriBILL') {
                    if (strpos($value->kode_pembayaran , 'MANDIRI') !== false) {
                        $nir_order[$value->nir.$value->type_bayar] = $value->kode_pembayaran;
                    }
                }
            }
        }

        foreach ($list as $key => $value) {
            foreach ($value->attributes as $k => $v) {
                $response[$key][$k] = $v;
            }
            if ($value->promo=='true' && isset($nir_order[$value->nir.$value->type_bayar])) {
                $response[$key]['kode_pembayaran'] = $nir_order[$value->nir.$value->type_bayar];
            }
            if (isset($request['order_id'])&& $request['order_id'] !='' && strpos($response[$key]['kode_pembayaran'] , $request['order_id']) === false) {
                unset($response[$key]);
            }
            if (isset($request['id_webinar']) && !in_array($request['id_webinar'], ['','Semua Webinar']) && $request['id_webinar'] != $response[$key]['id_webinar']) {
                unset($response[$key]);
            }
        }

        return $response;
    }

    public function getDashboard()
    {
        $response = [];

        $list = Webinar::select(array(DB::raw('id as webinar')));
        $list = $list->get();
        foreach ($list as $key => $value) {
            $response['row_1'][$value['webinar']]['jumlah_peserta'] = 0;
            $response['row_1'][$value['webinar']]['webinar'] = $value['webinar'];
        }

        $list = Daftar::where('flag_status','<>','Gagal');
        $list->groupBy('id_webinar');
        $list->select(array(DB::raw('count(DISTINCT nir) as jumlah_peserta,id_webinar as webinar')));

        $list = $list->get();
        foreach ($list as $key => $value) {
            foreach ($value->attributes as $k => $v) {
                $response['row_1'][$value['webinar']][$k] = $v;
            }
        }

        return $response;
    }

}