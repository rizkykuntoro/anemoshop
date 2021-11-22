<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Daftar;
use App\Models\Webinar;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Http\Controllers\PaymentGatewayController;
use App\Helpers\Helper;

class AdminController extends Controller
{
    public function index() {
        $data = Daftar::getDashboard();
        // echo "<pre>";print_r($data); echo "</pre>";die();
        return view('admin.index',['data'=>$data]);
    }

    public function daftarpeserta(Request $request) {
        $data = Daftar::getListPeserta($request->all());
        return view('admin.daftarpeserta',['data'=>$data]);
    }

    public function createpeserta(Request $request)
    {
        $model = new Daftar;
        $data_req = $request->all();
        $listwebinar = Webinar::getList();
        if (isset($data_req['nama']) && $data_req['nama']!='') {
            $rules = [
                'email'                 => 'required|email',
                'nir'                   => 'required|min:11',
                'nohp'                  => 'required|min:11|max:13',
                'no_wa'                  => 'required|min:11|max:13',
            ];
            $messages = [
                'email.required'        => 'Email wajib diisi',
                'email.email'           => 'Email tidak valid (contoh : roger@gmail.com)',
                'nir.required'          => 'Nomor Induk Radiografer wajib diisi',
                'nir.integer'           => 'Nomor Induk Radiografer harus berupa angka (contoh : 0822xxxxxxxx)',
                'nohp.required'         => 'No Handphone wajib diisi',
                'nohp.integer'          => 'No Handphone harus berupa angka (contoh : 0822xxxxxxxx)',
                'no_wa.required'         => 'No WA wajib diisi',
                'no_wa.integer'          => 'No WA harus berupa angka (contoh : 0822xxxxxxxx)',
                'nohp.min'              => 'No HP Harus Lebih dari 11 digit',
                'no_wa.min'              => 'No WA Harus Lebih dari 10 digit',
                'nohp.max'               => 'No WA Harus Kurang dari 14 digit',
                'no_wa.max'               => 'No WA Harus Kurang dari 14 digit',
                'nir.size'              => 'NIR Harus Lebih dari 11 digit',
            ];
            $now = date('Y-m-d H:i:s');
            $model->nama =  $data_req['nama'];
            $model->email =  $data_req['email'];
            $model->nir =  $data_req['nir'];
            $model->nohp =  $data_req['nohp'];
            $model->no_wa =  $data_req['no_wa'];
            $model->nama_instansi_kerja =  $data_req['instasi'];
            $model->nama_pengcap =  $data_req['pengcap'];
            $model->date =  $now;
            $model->flag_status =  'Menunggu Pembayaran';
            $model->type_bayar =  'Lunas';
            $model->expired_daftar =  date('Y-m-d H:i:s',strtotime($now . "+1 days"));
            $model->pre_survey =  'Lainnya';

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
                $this->notif('true','Pendaftaran Gagal !',implode(', ', $error),'error');
                return view('admin.createpeserta',['model'=>$model,'listwebinar'=>$listwebinar]);
            }
            $cekNIR = Daftar::where('nir',$data_req['nir'])->whereNotIn('flag_status',['Gagal','Expired'])->whereIn('id_webinar',$data_req['id_webinar']);
            if ($cekNIR->count() > 0) {
                $response['notif'] = 'true';
                $response['title'] = 'Pendaftaran Gagal !';
                $response['message'] = 'Nomor Induk Radiografer Telah terdaftar.';
                $response['status'] = 'error';
                $response['data'] = [];
                $this->notif('true','Pendaftaran Gagal !','NIR : '.$data_req['nir'].' telah terdaftar di salah satu webinar yg dipilih.','error');
                return view('admin.createpeserta',['model'=>$model,'listwebinar'=>$listwebinar]);
            }

            foreach ($data_req['id_webinar']as $key => $value) {
                $model = new Daftar;
                $now = date('Y-m-d H:i:s');
                $model->nama =  $data_req['nama'];
                $model->email =  $data_req['email'];
                $model->nir =  $data_req['nir'];
                $model->nohp =  $data_req['nohp'];
                $model->no_wa =  $data_req['no_wa'];
                $model->nama_instansi_kerja =  $data_req['instasi'];
                $model->nama_pengcap =  $data_req['pengcap'];
                $model->date =  $now;
                $model->flag_status =  'Lunas';
                $model->type_bayar =  'DIRECT';
                $model->expired_daftar =  date('Y-m-d H:i:s',strtotime($now . "+1 days"));
                $model->pre_survey =  'Lainnya';
                $model->id_webinar =  $value;
                $webinar = Webinar::find($value);
                $model->link_wa =  $webinar->ceklinkWA();
                $model->save();

                $pembayaran = (new PaymentGatewayController)->Pembayaran($model);

                try {
                    $hash = urlencode(Hash::make('Webinar'.($pembayaran->id_webinar+1)));
                    $webinar = Webinar::find($pembayaran->id_webinar);
                    $daftar = Daftar::find($pembayaran->id_daftar);
                    $pecah_tgl_exp = explode(' ', $daftar->expired_daftar);
                    $subject = "Terima Kasih Sudah Melunasi Pembayaran Webinar #".$pembayaran->kode_pembayaran;
                    $nows = date('Y-m-d H:i:s');
                    $now = date('Y-m-d H:i:s',strtotime($nows . "+1 days"));
                    $tgl_exp = explode(' ', $now);
                    $tgl_exps = explode(' ', $nows);
                    $email_template = file_get_contents(url('/email2.html'));
                    $email_body = str_replace('###JudulWebinar###',  $webinar->nama, $email_template);
                    $email_body = str_replace('###TANGGAL_KADALUARSA###',  Helper::tanggalIndo($pecah_tgl_exp[0])." ".$pecah_tgl_exp[1]." WIB", $email_body);
                    $email_body = str_replace('###REALEXPIREDDATE###',  $daftar->expired_daftar, $email_body);
                    $email_body = str_replace('###TYPEBAYAR###',  $daftar->type_bayar, $email_body);
                    $email_body = str_replace('###VANUMBER###',  $pembayaran->va_numbers, $email_body);
                    $email_body = str_replace('###TOTALPEMBAYARAN###',  'Rp. '. number_format($pembayaran->total,2,",","."), $email_body);
                    $email_body = str_replace('###TANGGAL###',  Helper::tanggalIndo($tgl_exps[0]), $email_body);
                    $email_body = str_replace('###NAMA###',  $daftar->nama, $email_body);
                    $email_body = str_replace('###EMAIL###',  $daftar->email, $email_body);
                    $email_body = str_replace('###NOHP###',  $daftar->nohp, $email_body);
                    $email_body = str_replace('###NIR###',  $daftar->nir, $email_body);
                    $email_body = str_replace('###PENGCAP###',  $daftar->nama_pengcap, $email_body);
                    $email_body = str_replace('###INSTANSI###',  $daftar->nama_instansi_kerja, $email_body);
                    $email_body = str_replace('###KODE###',  $pembayaran->kode_pembayaran, $email_body);
                    $email_body = str_replace('###TANGGAL_PEMBAYARAN###',  $pembayaran->updated_at, $email_body);
                    $email_body = str_replace('###LINKWA###',  $daftar->link_wa, $email_body);
                    $email_body = str_replace('###LINKWEBNEXT###',  url('/webinar/view').'/'.($pembayaran->id_webinar+1).'?key='.$hash, $email_body);
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
            }
            $this->notif('true','Mendaftarkan Peserta Berhasil','Mendaftarkan Peserta Berhasil.','success');
            return redirect('/admin/daftar-peserta/');
        }
        return view('admin.createpeserta',['model'=>$model,'listwebinar'=>$listwebinar]);
    }

    public function listwebinar() {
    	$data = Webinar::getList();
        return view('admin.webinarlist',['data'=>$data]);
    }

    public function createwebinar(Request $request) {
    	$model = new Webinar;
    	$data_req = $request->all();
    	if (isset($data_req['nama']) && $data_req['nama']!='') {
    		$model->nama = $data_req['nama'];
    		$model->jenis = $data_req['jenis'];
    		$model->total_skp = $data_req['total_skp'];
    		$model->tanggal_mulai = $data_req['tanggal_mulai'];
    		$model->tanggal_selesai = $data_req['tanggal_selesai'];
    		$model->tempat = $data_req['tempat'];
    		$model->panitia = $data_req['panitia'];
    		$model->kontak = $data_req['kontak'];
            $model->biaya = $data_req['biaya'];
            $model->link1 = $data_req['link1'];
    		$model->link2 = $data_req['link2'];
    		$model->konten = str_replace('<p>"</p>', '', $data_req['konten']);
    		$model->created_by = 1;
    		$model->updated_by = 1;
            if (isset($data_req['thumbnail'])) {
                $filename = '/thumbnail_'.date('ymdhis').'.'.$data_req['thumbnail']->getClientOriginalExtension();
                $target = '/public/image_webinar';
                if (move_uploaded_file($data_req['thumbnail']->getRealPath(), base_path().$target.$filename)) {
                    $model->thumbnail = $filename;
                }else{
                    $this->notif('true','Buat Webinar Gagal','Gambar Thumbnail tidak bisa diupload.','error');
        			return view('admin.webinarform',['model'=>$model]);
                }
            }
            if (isset($data_req['brosur'])) {
                $filename = '/brosur_'.date('ymdhis').'.'.$data_req['brosur']->getClientOriginalExtension();
                $target = '/public/image_webinar';
                if (move_uploaded_file($data_req['brosur']->getRealPath(), base_path().$target.$filename)) {
                    $model->brosur = $filename;
                }else{
                    $this->notif('true','Buat Webinar Gagal','Gambar Brosur tidak bisa diupload.','error');
        			return view('admin.webinarform',['model'=>$model]);
                }
            }
            if ($model->save()) {
                $this->notif('true','Buat Webinar Berhasil','Buat Webinar Berhasil.','success');
                return redirect('/admin/webinar/');
            }else{
                $this->notif('true','Buat Webinar Gagal','Cek Kembali isian anda.','error');
        		return view('admin.webinarform',['model'=>$model]);
            }
    	}
        return view('admin.webinarform',['model'=>$model]);
    }


    public function editwebinar($id,Request $request) {
    	$model = Webinar::find($id);
    	$data_req = $request->all();
    	if (isset($data_req['nama']) && $data_req['nama']!='') {
    		$model->nama = $data_req['nama'];
    		$model->jenis = $data_req['jenis'];
    		$model->total_skp = $data_req['total_skp'];
    		$model->tanggal_mulai = $data_req['tanggal_mulai'];
    		$model->tanggal_selesai = $data_req['tanggal_selesai'];
    		$model->tempat = $data_req['tempat'];
    		$model->panitia = $data_req['panitia'];
    		$model->kontak = $data_req['kontak'];
    		$model->biaya = $data_req['biaya'];
            $model->link1 = $data_req['link1'];
            $model->link2 = $data_req['link2'];
    		$model->konten = str_replace('<p>"</p>', '', $data_req['konten']);
    		$model->created_by = 1;
    		$model->updated_by = 1;
            if (isset($data_req['thumbnail'])) {
            	$foto_lama = $model->thumbnail;
                $filename = '/thumbnail_'.date('ymdhis').'.'.$data_req['thumbnail']->getClientOriginalExtension();
                $target = '/public/image_webinar';
                if (move_uploaded_file($data_req['thumbnail']->getRealPath(), base_path().$target.$filename)) {
                    $model->thumbnail = $filename;
                    if (file_exists($target.$foto_lama)) {
					    unlink($target.$foto_lama);
					} 
                }else{
                    $this->notif('true','Edit Webinar Gagal','Gambar Thumbnail tidak bisa diupload.','error');
        			return view('admin.webinarform',['model'=>$model,'id_edit'=>$id]);
                }
            }
            if (isset($data_req['brosur'])) {
            	$foto_lama = $model->brosur;
                $filename = '/brosur_'.date('ymdhis').'.'.$data_req['brosur']->getClientOriginalExtension();
                $target = '/public/image_webinar';
                if (move_uploaded_file($data_req['brosur']->getRealPath(), base_path().$target.$filename)) {
                    $model->brosur = $filename;
                    if (file_exists($target.$foto_lama)) {
					    unlink($target.$foto_lama);
					} 
                }else{
                    $this->notif('true','Edit Webinar Gagal','Gambar Brosur tidak bisa diupload.','error');
        			return view('admin.webinarform',['model'=>$model,'id_edit'=>$id]);
                }
            }
            if ($model->save()) {
                $this->notif('true','Edit Webinar Berhasil','Edit Webinar Berhasil.','success');
                return redirect('/admin/webinar/');
            }else{
                $this->notif('true','Edit Webinar Gagal','Cek Kembali isian anda.','error');
        		return view('admin.webinarform',['model'=>$model,'id_edit'=>$id]);
            }
    	}
        return view('admin.webinarform',['model'=>$model,'id_edit'=>$id]);
    }

    public function deletewebinar($id)
    {
    	$model = Webinar::find($id);
        if (empty($model)) {
            return redirect('/admin/webinar/');
        }
        $foto_lama = $model->thumbnail;
        $foto_lama1 = $model->brosur;
        $target = '/public/image_webinar';
        if ($model->delete()) {
            if (file_exists($target.$foto_lama)) {
			    unlink($target.$foto_lama);
			} 
            if (file_exists($target.$foto_lama1)) {
			    unlink($target.$foto_lama1);
			} 
        }
        $this->notif('true','Delete Webinar Berhasil','Delete Webinar Berhasil.','success');
        return redirect('/admin/webinar/');
    }

    public function logout()
    {
        Session::flush();
        session_destroy();
        return redirect('/admin/login')->with('alert','Kamu sudah logout');
    }


    public function listuser() {
        $List = User::getList();
        return view('admin.userlist',['list'=>$List]);
    }
    
    public function createuser(Request $request) {
        $data = $request->all();
        $model = new User;
        if (!empty($data) && isset($data['_token'])) {
            $model->name = $data['name'];
            $model->email = $data['email'];
            $model->password = Hash::make($data['password']);
            $user = User::where('email',$data['email'])->first();
            if ($user) {
                $this->notif('true','Buat User Baru Gagal','Email Telah digunakan,silahkan gunakan email yang lain.','error');
                return view('admin.userform',['model'=>$model]);
            }
            if ($model->save()) {
                $this->notif('true','Buat User Baru Berhasil','User Baru berhasil disimpan.','success');
                return redirect('/admin/user');
            }else{
                $this->notif('true','Buat User Baru Gagal','User Baru gagal disimpan,silahkan cek kembali.','error');
                return view('admin.userform',['model'=>$model]);
            }
        }
        return view('admin.userform',['model'=>$model]);
    }


    public function edituser($id,Request $request) {
        $data = $request->all();
        $model = User::find($id);
        if (!empty($data) && isset($data['_token'])) {
            $model->name = $data['name'];
            $model->email = $data['email'];
            if (!Hash::check($data['password'], $model->password)) {
                $model->password = Hash::make($data['password']);
            }
            if ($model->save()) {
                $this->notif('true','Edit user Berhasil','user berhasil diedit.','success');
                return redirect('/admin/user');
            }else{
                $this->notif('true','Edit user Gagal','user gagal diedit,silahkan cek kembali.','error');
                return view('admin.userform',['model'=>$model,'id_edit'=>$id]);
            }
        }
        return view('admin.userform',['model'=>$model,'id_edit'=>$id]);
    }

    public function deleteuser($id)
    {
        $model = User::find($id);
        if ($model->delete()) {
            $this->notif('true','User Berhasil Dihapus','User Berhasil dihapus.','success');
            return redirect('/management/user/list');
        }else{
            $this->notif('true','User Gagal Dihapus','User gagal dihapus,silahkan cek kembali.','error');
            return redirect('/management/user/list');
        }
    }

}
