<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Models\Daftar;
use App\Models\Webinar;
use App\Models\PromoWebinar;
use Illuminate\Support\Facades\DB;

class Helper
{
    public function tanggalIndo($tanggal) {
    	$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$hari = ['Monday' => 'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jum\'at','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
		$pecahkan = explode('-', $tanggal);
		if (!isset($pecahkan[3])) {
			$tgl = date('Y-m-d-l',strtotime($tanggal));
			$pecahkan = explode('-', $tgl);
		}
		return $hari[$pecahkan[3]] . ', '.$pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }

    public function tanggalIndotb($tanggal) {
    	$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$hari = ['Monday' => 'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jum\'at','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
		$pecahkan = explode('-', $tanggal);
		$pecahkan2 = explode(' ', $pecahkan[2]);
		$day = $pecahkan2[0];
	 
		return  $day. ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0].' '.$pecahkan2[1];
    }

    public function list_warna($key)
    {
    	$list_warna = ['#10ac84','#2e86de','#ee5253','#ff9f43','#353b48','#8c7ae6','#40407a','#10ac84','#2e86de','#ee5253','#ff9f43','#353b48','#8c7ae6','#40407a','#10ac84','#2e86de','#ee5253','#ff9f43','#353b48','#8c7ae6','#40407a','#10ac84','#2e86de','#ee5253','#ff9f43','#353b48','#8c7ae6','#40407a','#10ac84','#2e86de','#ee5253','#ff9f43','#353b48','#8c7ae6','#40407a','#10ac84','#2e86de','#ee5253','#ff9f43','#353b48','#8c7ae6','#40407a'];

    	return $list_warna[$key];
    }

    public function menuActiveChecker($server,$menu)
    {
    	$baseurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    	$baseurl = str_replace('httpss', 'https', $baseurl);

    	if (strtolower($baseurl)==strtolower($menu)) {
    		return 'active';
    	}
    	return "";
    }

    public function sisaKuotapay($id_webinar)
    {
    	$sisaKuotapay = Daftar::where('flag_status','Lunas')->where('id_webinar',$id_webinar)->groupBy('nir');
    	return $sisaKuotapay->get()->count();
    }
    
    public function sisaKuotaall($id_webinar)
    {
    	$sisaKuotaall = Daftar::whereNotIn('flag_status',['Gagal','Expired'])->where('id_webinar',$id_webinar)->groupBy('nir');
    	return $sisaKuotaall->get()->count();
    }

    public function cekstatuswebinar($id_webinar)
    {
    	$now = date('Y-m-d H:i:s');
        $wbinar = Webinar::find($id_webinar);
        if (isset($wbinar->tanggal_mulai)) {
            $tgl_mulai = $wbinar->tanggal_mulai;
            $before = date('Y-m-d H:i:s',strtotime($tgl_mulai . "-3 Hours"));
            if (strtotime($now)>strtotime($before)) {
                return 1;
            }
        }
        return 0;
    }

    public function cekopenwebinar($id_webinar_sebelumnya)
    {
        $now = date('Y-m-d H:i:s');
        $wbinar = Webinar::find($id_webinar_sebelumnya);
        if (isset($wbinar->tanggal_mulai)) {
            $tgl_mulai = $wbinar->tanggal_mulai;
            $before = date('Y-m-d 00:00:00',strtotime($tgl_mulai));
            if (strtotime($now)>=strtotime($before)) {
                return 1;
            }
        }
        return 0;
    }

    public function totalbiayapromo($id_webinar)
    {
    	$id_webinar = explode(',', $id_webinar);
    	$total_biaya = 0;
    	$harga_webinar = Webinar::whereIn('id',$id_webinar)->select(array(DB::raw('SUM(biaya) as harga')));
    	foreach ($harga_webinar->get() as $index => $data) {
			$attr = $data->getAttributes();
			$total_biaya = $attr['harga'];
		}
    	return $total_biaya;
    }

    public function sisakuotapromo()
    {
    	$peserta = Daftar::where('promo','true')->whereNotIn('flag_status',['Gagal','Expired'])->groupBy('nir')->get();
    	// return 50;
    	return $peserta->count();
    }
}
