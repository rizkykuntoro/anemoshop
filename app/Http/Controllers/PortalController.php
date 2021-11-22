<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Helpers\Helper;
use App\Models\Webinar;
use App\Models\Daftar;
use App\Models\Pembayaran;
use App\Models\LogEmailSender;
use App\Models\PromoWebinar;
use Validator;
use App\Http\Controllers\PaymentGatewayController;
use Illuminate\Support\Facades\Hash;

class PortalController extends Controller
{
    public function index() {
        $listidwebinar = [];
    	$webinar = Webinar::getList();
        $promo = PromoWebinar::getList();
        foreach ($webinar as $key => $value) {
            $listidwebinar[$value['id']] = $value;
        }
        return view('portal.index',['webinar'=>$webinar,'promo'=>$promo,'listidwebinar'=>$listidwebinar]);
    }

    public function simulasi($jmlh)
    {
        $webinar = Webinar::find(1);
        $now = date('Y-m-d H:i:s');
        for ($i=0; $i <= $jmlh ; $i++) { 
            if ($i<10) {
            $kode = "0000000000".$i;
            }else
            if ($i<100) {
                $kode = "000000000".$i;
            }else
            if ($i<1000) {
                $kode = "00000000".$i;
            }else{
                $kode = "0000000".$i;
            }
            $daftar = new Daftar;
            $daftar->nama =  'Peserta Ke '.$i;
            $daftar->email = 'peserta'.$i.'@gmail.com';
            $daftar->nir =  $kode;
            $daftar->nohp =  '0822839384'.$kode;
            $daftar->no_wa =  '0822839384'.$kode;
            $daftar->nama_instansi_kerja =  'Rumah Sakit '.$i;
            $daftar->nama_pengcap = 'Pengcab '.$i;
            $daftar->id_webinar =  '1';
            $daftar->date =  $now;
            $daftar->flag_status =  'Lunas';
            $daftar->type_bayar =  'DIRECT';
            $daftar->expired_daftar =  date('Y-m-d H:i:s',strtotime($now . "+1 days"));
            $daftar->link_wa =  $webinar->ceklinkWA();
            $daftar->pre_survey =  'Lainnya';
            $daftar->save();
            $pembayaran = (new PaymentGatewayController)->Pembayaran($daftar);
        }
    }

    public function viewWebinar($id_webinar, Request $request) {
        $req = $request->all();
        $skip = false;
        if (isset($req['key'])) {
            if (Hash::check('Webinar'.($id_webinar),urldecode($req['key']))) {
                $skip = true;
            }
        }
        if ($id_webinar==1) {
            $skip = true;
        }
    	$webinar = Webinar::find($id_webinar);
    	$listwebinar = Webinar::getList();
        $status_webinar_now = Helper::cekstatuswebinar($id_webinar-1);
        if($status_webinar_now>0){
             $skip = true;
        }
        if (!$skip) {
            $sisakuota_pay = Helper::sisaKuotapay($id_webinar-1);
            $sisakuota_all = Helper::sisaKuotaall($id_webinar-1);
            if ($sisakuota_all>=300 || $sisakuota_pay>=150) {
                return view('portal.webinarview',['id_webinar'=>$id_webinar,'data'=>$webinar,'listwebinar'=>$listwebinar]);
            }else{
                return view('portal.notfound');
            }
        }
        return view('portal.webinarview',['id_webinar'=>$id_webinar,'data'=>$webinar,'listwebinar'=>$listwebinar]);
    }


    public function promobuy(Request $request) {
        if (Helper::sisakuotapromo()==50) {
            $response['notif'] = 'true';
            $response['title'] = 'Kuota Promo Telah Habis !';
            $response['message'] = 'Silahkan hubungi panitia untuk info lebih lanjut.';
            $response['status'] = 'warning';
            $response['data'] = [];
            return json_encode($response,1);
        }
        $data_req   = $request->all();
        $daftar     = new Daftar;
        if (isset($data_req['nama']) && $data_req['nama']!='') {
            $rules = [
                'email'                 => 'required|email',
                'nir'                   => 'required|min:11',
                'nohp'                  => 'required|min:10|max:14',
                'nowa'                  => 'required|min:10|max:14',
            ];
            $messages = [
                'email.required'        => 'Email wajib diisi',
                'email.email'           => 'Email tidak valid (contoh : roger@gmail.com)',
                'nir.required'          => 'Nomor Induk Radiografer wajib diisi',
                'nir.integer'           => 'Nomor Induk Radiografer harus berupa angka (contoh : 0822xxxxxxxx)',
                'nohp.required'         => 'No Handphone wajib diisi',
                'nohp.integer'          => 'No Handphone harus berupa angka (contoh : 0822xxxxxxxx)',
                'nowa.required'         => 'No WA wajib diisi',
                'nowa.integer'          => 'No WA harus berupa angka (contoh : 0822xxxxxxxx)',
                'nohp.min'              => 'No HP Harus Lebih dari 10 digit',
                'nowa.min'             => 'No WA Harus Lebih dari 10 digit',
                'nohp.max'              => 'No WA Harus Kurang dari 15 digit',
                'nowa.max'             => 'No WA Harus Kurang dari 15 digit',
                'nir.min'               => 'NIR Harus Lebih dari 11 digit',
            ];

            $validator = Validator::make($data_req, $rules, $messages);
            $response  = [];
            if($validator->fails()){
                $error  = [];
                $err = json_decode($validator->errors(),1);
                foreach ($err as $key => $value) {
                    foreach ($value as $value2) {
                        $error[] = $value2;
                    }
                }
                $response['notif'] = 'true';
                $response['title'] = 'Pendaftaran Gagal !';
                $response['message'] = implode('<br>', $error);
                $response['status'] = 'error';
                $response['data'] = [];
                return json_encode($response,1);
            }
            $id_webinar = explode(',', $data_req['id_webinar']);
            foreach ($id_webinar as $key => $value) {
                $cekNIR = Daftar::where('nir',$data_req['nir'])->whereNotIn('flag_status',['Gagal','Expired'])->where('id_webinar',$value);
                if ($cekNIR->count() > 0) {
                    $response['notif'] = 'true';
                    $response['title'] = 'Pendaftaran Gagal !';
                    $response['message'] = 'Nomor Induk Radiografer Telah terdaftar di salah satu webinar.';
                    $response['status'] = 'error';
                    $response['data'] = [];
                    return json_encode($response,1);
                }
            }

            $length = count($id_webinar);
            $count = 1;
            foreach ($id_webinar as $key => $value) {
                $daftar = new Daftar;
                $data_req['id_webinar'] = $value;
                $webinar = Webinar::find($data_req['id_webinar']);
                $now = date('Y-m-d H:i:s');
                $daftar->nama =  $data_req['nama'];
                $daftar->email =  $data_req['email'];
                $daftar->nir =  $data_req['nir'];
                $daftar->nohp =  $data_req['nohp'];
                $daftar->no_wa =  $data_req['nowa'];
                $daftar->nama_instansi_kerja =  $data_req['instasi'];
                $daftar->nama_pengcap =  $data_req['pengcab'];
                $daftar->id_webinar =  $data_req['id_webinar'];
                $daftar->date =  $now;
                $daftar->flag_status =  'Pending';
                $daftar->type_bayar =  $data_req['payment'];
                $daftar->expired_daftar =  date('Y-m-d H:i:s',strtotime($now . "+1 days"));
                $daftar->link_wa =  $webinar->ceklinkWA();
                $daftar->pre_survey =  $data_req['presurvey'];
                $daftar->promo =  'true';
                $pecah_tgl_exp = explode(' ', $daftar->expired_daftar);
                if ($daftar->save()) {
                    if($count>=$length){
                        $pembayaran = (new PaymentGatewayController)->Pembayaran($daftar,$data_req['id_webinar_notbonus']);
                        $response['notif'] = 'true';
                        $response['title'] = 'Pendaftaran Berhasil !';
                        $response['message'] = 'Silahkan melakukan pembayaran.';
                        $response['status'] = 'success';
                        $response['data'] = $pembayaran;
                        $response['data']['expired_date'] = Helper::tanggalIndo($pecah_tgl_exp[0])." ".$pecah_tgl_exp[1]." WIB";
                        $response['data']['real_expired_date'] =  $daftar->expired_daftar;
                        $response['data']['total_pembayaran'] =  'Rp. '. number_format($pembayaran->total,2,",",".");
                        $response['data']['type_bayar'] =  $data_req['payment'];
                        if ($pembayaran->va_numbers != '') {
                            try {
                                $subject = "Menunggu Pembayaran Untuk Paket Webinar #".$pembayaran->kode_pembayaran;
                                $nows = date('Y-m-d H:i:s');
                                $now = date('Y-m-d H:i:s',strtotime($nows . "+1 days"));
                                $tgl_exp = explode(' ', $now);
                                $tgl_exps = explode(' ', $nows);
                                $email_template = file_get_contents(url('/email1.html'));
                                $email_body = str_replace('###JudulWebinar###',  "Paket Webinar Series", $email_template);
                                $email_body = str_replace('###TANGGAL_KADALUARSA###',  $response['data']['expired_date'], $email_body);
                                $email_body = str_replace('###REALEXPIREDDATE###',  $response['data']['real_expired_date'], $email_body);
                                $email_body = str_replace('###TYPEBAYAR###',  $response['data']['type_bayar'], $email_body);
                                $email_body = str_replace('###VANUMBER###',  $response['data']['va_numbers'], $email_body);
                                $email_body = str_replace('###TOTALPEMBAYARAN###',  $response['data']['total_pembayaran'], $email_body);
                                $email_body = str_replace('###TANGGAL###',  Helper::tanggalIndo($tgl_exps[0]), $email_body);
                                $email_body = str_replace('###NAMA###',  $daftar->nama, $email_body);
                                $email_body = str_replace('###EMAIL###',  $daftar->email, $email_body);
                                $email_body = str_replace('###NOHP###',  $daftar->nohp, $email_body);
                                $email_body = str_replace('###NIR###',  $daftar->nir, $email_body);
                                $email_body = str_replace('###PENGCAP###',  $daftar->nama_pengcap, $email_body);
                                $email_body = str_replace('###INSTANSI###',  $daftar->nama_instansi_kerja, $email_body);
                                $email_body = str_replace('###KODE###',  $pembayaran->kode_pembayaran, $email_body);
                                (new PaymentGatewayController)->sendMail($daftar->email,$subject,$email_body,$daftar->id);
                            } catch (\Exception $e) {
                                $log = new LogEmailSender;
                                $log->id_daftar = $daftar->id;
                                $log->email = $daftar->email;
                                $log->subject = $subject;
                                $log->body = $email_body;
                                $log->status = 'Gagal';
                                $log->info_error = json_encode($e->getMessage(),1);
                                $log->date = date('Y-m-d H:i:s');
                                $log->save();
                            }

                            return json_encode($response,1);
                        }else{
                            $response = [];
                            $response['notif'] = 'true';
                            $response['title'] = 'Pendaftaran Gagal !';
                            $response['message'] = 'Virtual Account gagal dibuat,silahkan ganti bank yang lain.';
                            $response['status'] = 'error';
                            $response['data'] = [];
                            return json_encode($response,1);
                        }
                    }
                }else{
                    $response['notif'] = 'true';
                    $response['title'] = 'Pendaftaran Gagal !';
                    $response['message'] = 'Data Tidak Sesuai.';
                    $response['status'] = 'error';
                    $response['data'] = [];
                    return json_encode($response,1);
                }
                $count++;
            }
        }
        $response['notif'] = 'true';
        $response['title'] = 'Pendaftaran Gagal !';
        $response['message'] = 'Data Tidak Sesuai.';
        $response['status'] = 'error';
        $response['data'] = [];
        return json_encode($response,1);
    }

    public function listwebinar() {
    	$webinar = Webinar::getList();
        return view('portal.listwebinar',['webinar'=>$webinar]);
    }

    public function daftar(Request $request) {
        $data_req   = $request->all();
        $daftar     = new Daftar;
        if (isset($data_req['nama']) && $data_req['nama']!='') {
            $rules = [
                'email'                 => 'required|email',
                'nir'                   => 'required|min:11',
                'nohp'                  => 'required|min:10|max:14',
                'nowa'                  => 'required|min:10|max:14',
            ];
            $messages = [
                'email.required'        => 'Email wajib diisi',
                'email.email'           => 'Email tidak valid (contoh : roger@gmail.com)',
                'nir.required'          => 'Nomor Induk Radiografer wajib diisi',
                'nir.integer'           => 'Nomor Induk Radiografer harus berupa angka (contoh : 0822xxxxxxxx)',
                'nohp.required'         => 'No Handphone wajib diisi',
                'nohp.integer'          => 'No Handphone harus berupa angka (contoh : 0822xxxxxxxx)',
                'nowa.required'         => 'No WA wajib diisi',
                'nowa.integer'          => 'No WA harus berupa angka (contoh : 0822xxxxxxxx)',
                'nohp.min'              => 'No HP Harus Lebih dari 10 digit',
                'nowa.min'             => 'No WA Harus Lebih dari 10 digit',
                'nohp.max'              => 'No WA Harus Kurang dari 15 digit',
                'nowa.max'             => 'No WA Harus Kurang dari 15 digit',
                'nir.min'               => 'NIR Harus Lebih dari 11 digit',
            ];

            $validator = Validator::make($data_req, $rules, $messages);
            $response  = [];
            if($validator->fails()){
                $error  = [];
                $err = json_decode($validator->errors(),1);
                foreach ($err as $key => $value) {
                    foreach ($value as $value2) {
                        $error[] = $value2;
                    }
                }
                $response['notif'] = 'true';
                $response['title'] = 'Pendaftaran Gagal !';
                $response['message'] = implode('<br> ', $error);
                $response['status'] = 'error';
                $response['data'] = [];
                return json_encode($response,1);
            }
            $cekNIR = Daftar::where('nir',$data_req['nir'])->whereNotIn('flag_status',['Gagal','Expired'])->where('id_webinar',$data_req['id_webinar']);
            if ($cekNIR->count() > 0) {
                $response['notif'] = 'true';
                $response['title'] = 'Pendaftaran Gagal !';
                $response['message'] = 'Nomor Induk Radiografer Telah terdaftar.';
                $response['status'] = 'error';
                $response['data'] = [];
                return json_encode($response,1);
            }

            $webinar = Webinar::find($data_req['id_webinar']);
            $now = date('Y-m-d H:i:s');
            $daftar->nama =  $data_req['nama'];
            $daftar->email =  $data_req['email'];
            $daftar->nir =  $data_req['nir'];
            $daftar->nohp =  $data_req['nohp'];
            $daftar->no_wa =  $data_req['nowa'];
            $daftar->nama_instansi_kerja =  $data_req['instasi'];
            $daftar->nama_pengcap =  $data_req['pengcab'];
            $daftar->id_webinar =  $data_req['id_webinar'];
            $daftar->date =  $now;
            $daftar->flag_status =  'Pending';
            $daftar->type_bayar =  $data_req['payment'];
            $daftar->expired_daftar =  date('Y-m-d H:i:s',strtotime($now . "+1 days"));
            $daftar->link_wa =  $webinar->ceklinkWA();
            $daftar->pre_survey =  $data_req['presurvey'];
            $pecah_tgl_exp = explode(' ', $daftar->expired_daftar);
            if ($daftar->save()) {
                $pembayaran = (new PaymentGatewayController)->Pembayaran($daftar);
                $response['notif'] = 'true';
                $response['title'] = 'Pendaftaran Berhasil !';
                $response['message'] = 'Silahkan melakukan pembayaran.';
                $response['status'] = 'success';
                $response['data'] = $pembayaran;
                $response['data']['expired_date'] = Helper::tanggalIndo($pecah_tgl_exp[0])." ".$pecah_tgl_exp[1]." WIB";
                $response['data']['real_expired_date'] =  $daftar->expired_daftar;
                $response['data']['total_pembayaran'] =  'Rp. '. number_format($pembayaran->total,2,",",".");
                $response['data']['type_bayar'] =  $data_req['payment'];
                if ($pembayaran->va_numbers != '') {
                    try {
                        $subject = "Menunggu Pembayaran Untuk Webinar #".$pembayaran->kode_pembayaran;
                        $nows = date('Y-m-d H:i:s');
                        $now = date('Y-m-d H:i:s',strtotime($nows . "+1 days"));
                        $tgl_exp = explode(' ', $now);
                        $tgl_exps = explode(' ', $nows);
                        $email_template = file_get_contents(url('/email1.html'));
                        $email_body = str_replace('###JudulWebinar###',  $webinar->nama, $email_template);
                        $email_body = str_replace('###TANGGAL_KADALUARSA###',  $response['data']['expired_date'], $email_body);
                        $email_body = str_replace('###REALEXPIREDDATE###',  $response['data']['real_expired_date'], $email_body);
                        $email_body = str_replace('###TYPEBAYAR###',  $response['data']['type_bayar'], $email_body);
                        $email_body = str_replace('###VANUMBER###',  $response['data']['va_numbers'], $email_body);
                        $email_body = str_replace('###TOTALPEMBAYARAN###',  $response['data']['total_pembayaran'], $email_body);
                        $email_body = str_replace('###TANGGAL###',  Helper::tanggalIndo($tgl_exps[0]), $email_body);
                        $email_body = str_replace('###NAMA###',  $daftar->nama, $email_body);
                        $email_body = str_replace('###EMAIL###',  $daftar->email, $email_body);
                        $email_body = str_replace('###NOHP###',  $daftar->nohp, $email_body);
                        $email_body = str_replace('###NIR###',  $daftar->nir, $email_body);
                        $email_body = str_replace('###PENGCAP###',  $daftar->nama_pengcap, $email_body);
                        $email_body = str_replace('###INSTANSI###',  $daftar->nama_instansi_kerja, $email_body);
                        $email_body = str_replace('###KODE###',  $pembayaran->kode_pembayaran, $email_body);
                        (new PaymentGatewayController)->sendMail($daftar->email,$subject,$email_body,$daftar->id);
                    } catch (\Exception $e) {
                        $log = new LogEmailSender;
                        $log->id_daftar = $daftar->id;
                        $log->email = $daftar->email;
                        $log->subject = $subject;
                        $log->body = $email_body;
                        $log->status = 'Gagal';
                        $log->info_error = json_encode($e->getMessage(),1);
                        $log->date = date('Y-m-d H:i:s');
                        $log->save();
                    }

                    return json_encode($response,1);
                }else{
                    $response = [];
                    $response['notif'] = 'true';
                    $response['title'] = 'Pendaftaran Gagal !';
                    $response['message'] = 'Virtual Account gagal dibuat,silahkan ganti bank yang lain.';
                    $response['status'] = 'error';
                    $response['data'] = [];
                    return json_encode($response,1);
                }
            }else{
                $response['notif'] = 'true';
                $response['title'] = 'Pendaftaran Gagal !';
                $response['message'] = 'Data Tidak Sesuai.';
                $response['status'] = 'error';
                $response['data'] = [];
                return json_encode($response,1);
            }
        }
        $response['notif'] = 'true';
        $response['title'] = 'Pendaftaran Gagal !';
        $response['message'] = 'Data Tidak Sesuai.';
        $response['status'] = 'error';
        $response['data'] = [];
        // $now = date('Y-m-d H:i:s');
        // $now = date('Y-m-d H:i:s',strtotime($now . "+1 days"));
        // $pecah_tgl_exp = explode(' ', $now);
        // $response['data']['expired_date'] = Helper::tanggalIndo($pecah_tgl_exp[0])." ".$pecah_tgl_exp[1]." WIB";
        // $response['data']['real_expired_date'] =  $now;
        // $response['data']['va_numbers'] =  '9888231384127717';
        // $response['data']['total_pembayaran'] =  'Rp. '. number_format('200000',2,",",".");
        // $response['data']['type_bayar'] =  'bniVA';
        return json_encode($response,1);
    }

    public function kontak_kami()
    {
        return view('portal.kontak_kami');
    }

    public function tentang_kami()
    {
        return view('portal.tentang_kami');
    }

    public function test_email()
    {
        $data = LogEmailSender::find(34);
        (new PaymentGatewayController)->sendMail($data->email,$data->subject,$data->body,$data->id_daftar);
    }
}
