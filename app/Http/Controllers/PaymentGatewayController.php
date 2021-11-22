<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Pembayaran;
use App\Models\Daftar;
use App\Models\Webinar;
use App\Models\LogMidtrans;
use App\Models\LogEmailSender;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Hash;

class PaymentGatewayController extends Controller
{
    public function Pembayaran($pendaftaran,$promo='')
    {
        $pembayaran     			 = new Pembayaran;
        $pembayaran->id_webinar 	 = $pendaftaran->id_webinar;
        $pembayaran->id_daftar 		 = $pendaftaran->id;
        $pembayaran->kode_pembayaran = $this->kode_pembayaran($pendaftaran->type_bayar);
        if ($promo=='') {
            $pembayaran->total =  Webinar::find($pendaftaran->id_webinar)->biaya;
        }else{
            $pembayaran->total =  Helper::totalbiayapromo($promo);
        }
        $pembayaran->status =  ($pendaftaran->type_bayar != 'DIRECT')?'Pending':'settlement';
        $pembayaran->created_at =  $pendaftaran->date;
        $pembayaran->updated_at =  $pendaftaran->date;
        if ($pembayaran->save() && $pendaftaran->type_bayar != 'DIRECT') {
        	$request = $this->requestMaker($pendaftaran,$pembayaran);
        	$midtrans = $this->curl($request);
        	$log = new LogMidtrans;
        	$log->kode_pembayaran = $pembayaran->kode_pembayaran;
        	$log->json_request = $request;
        	$log->json_response = $midtrans;
        	$log->ket = 'create trx';
        	$log->save();
        	$midtrans = json_decode($midtrans,1);
        	if (isset($midtrans['status_code']) && $midtrans['status_code']=="201") {
        		if (in_array($pendaftaran->type_bayar, ['briVA','bniVA','bcaVA'])) {
	        		$pembayaran->va_numbers = isset($midtrans['va_numbers'][0]['va_number'])?$midtrans['va_numbers'][0]['va_number']:"";
	        		$pembayaran->transaction_id = isset($midtrans['transaction_id'])?$midtrans['transaction_id']:"";
	        		$pembayaran->save();
		        }else if ($pendaftaran->type_bayar=='permataVA') {
	        		$pembayaran->va_numbers = isset($midtrans['permata_va_number'])?$midtrans['permata_va_number']:"";
	        		$pembayaran->transaction_id = isset($midtrans['transaction_id'])?$midtrans['transaction_id']:"";
	        		$pembayaran->save();
		        }else if ($pendaftaran->type_bayar=='mandiriBILL') {
                    $bill_key = isset($midtrans['bill_key'])?$midtrans['bill_key']:"";
                    $biller_code = isset($midtrans['biller_code'])?$midtrans['biller_code']:"";
	        		$pembayaran->va_numbers = $bill_key.":".$biller_code;
	        		$pembayaran->transaction_id = isset($midtrans['transaction_id'])?$midtrans['transaction_id']:"";
	        		$pembayaran->save();
		        }
        	}else{
                if ($promo!='') {
                    $roll = Daftar::where('nir',$pendaftaran->nir);
                    foreach ($roll->get() as $key => $value) {
                        $rollback_daftar = Daftar::find($value->id);
                        $rollback_daftar->flag_status = 'Gagal';
                        $rollback_daftar->save();
                    };
                }else{
                    $rollback_daftar = Daftar::find($pendaftaran->id);
                    $rollback_daftar->flag_status = 'Gagal';
                    $rollback_daftar->save();
                }
        	}
        }	
        return $pembayaran;
    }

    public function kode_pembayaran($type_bayar)
    {
        $check =  Daftar::count();
        $total_user = ceil($check+1);
        if ($type_bayar=='briVA') {
        	$tipe_bayar = "BRI";
        }else if ($type_bayar=='permataVA') {
        	$tipe_bayar = "PERMATA";
        }else if ($type_bayar=='mandiriBILL') {
        	$tipe_bayar = "MANDIRI";
        }else if ($type_bayar=='bniVA') {
        	$tipe_bayar = "BNI";
        }else if ($type_bayar=='bcaVA') {
        	$tipe_bayar = "BCA";
        }else{
        	$tipe_bayar = "DIRECT";
        }
        if ($total_user<10) {
        	$kode = "PAY-".$tipe_bayar."-000".$total_user;
        }else
        if ($total_user<100) {
        	$kode = "PAY-".$tipe_bayar."-00".$total_user;
        }else
        if ($total_user<1000) {
        	$kode = "PAY-".$tipe_bayar."-0".$total_user;
        }else{
        	$kode = "PAY-".$tipe_bayar."-".$total_user;
        }

        return $kode;
    }

    public function curl($request)
    {
    	$key = base64_encode(env('MIDTRANS_KEY',''));
        $url = env('URL_MIDTRANS','');
        $curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>$request,
		  CURLOPT_HTTPHEADER => array(
		    "Accept: application/json",
		    "Content-Type: application/json",
		    "Authorization: Basic $key"
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
    }

    public function requestMaker($pendaftaran,$pembayaran)
    {
    	if ($pendaftaran->type_bayar == 'bniVA') {
    		$request = [];
    		$request['payment_type'] = 'bank_transfer';
    		$request['transaction_details'] = ['gross_amount'=>(int)$pembayaran->total,'order_id'=>$pembayaran->kode_pembayaran];
    		$request['bank_transfer'] = ['bank'=>'bni'];
    	}else if ($pendaftaran->type_bayar == 'briVA') {
    		$request = [];
    		$request['payment_type'] = 'bank_transfer';
    		$request['transaction_details'] = ['gross_amount'=>(int)$pembayaran->total,'order_id'=>$pembayaran->kode_pembayaran];
    		$request['bank_transfer'] = ['bank'=>'bri'];
    	}else if ($pendaftaran->type_bayar == 'bcaVA') {
    		$request = [];
    		$request['payment_type'] = 'bank_transfer';
    		$request['transaction_details'] = ['gross_amount'=>(int)$pembayaran->total,'order_id'=>$pembayaran->kode_pembayaran];
    		$request['bank_transfer'] = ['bank'=>'bca'];
    	}else if ($pendaftaran->type_bayar == 'permataVA') {
    		$request = [];
    		$request['payment_type'] = 'permata';
    		$request['transaction_details'] = ['gross_amount'=>(int)$pembayaran->total,'order_id'=>$pembayaran->kode_pembayaran];
    	}else if ($pendaftaran->type_bayar == 'mandiriBILL') {
    		$request = [];
    		$request['payment_type'] = 'echannel';
    		$request['transaction_details'] = ['gross_amount'=>(int)$pembayaran->total,'order_id'=>$pembayaran->kode_pembayaran];
            $request['echannel'] = ['bill_info1'=>'Payment For:','bill_info2'=>'Webinar'];
    	}

    	return json_encode($request,1);
    }

    public function callback(Request $request)
    {
    	$data_req = $request->all();
    	if (isset($data_req['transaction_id'])) {
	        $log = new LogMidtrans;
	        $log->kode_pembayaran = $data_req['order_id'];
	        $log->json_response = json_encode($data_req);
	        $log->ket = 'callback trx';
	        $log->save();
    		$pembayaran = Pembayaran::where('transaction_id',$data_req['transaction_id'])->select('id')->limit(1);
    		$id_pembayaran = 0;
    		foreach ($pembayaran->get() as $key => $value) {
    			$id_pembayaran = $value->id;
    		}
    		$pembayaran = Pembayaran::find($id_pembayaran);
    		$pembayaran->status = $data_req['transaction_status'];
    		$pembayaran->updated_at = date('Y-m-d H:i:s');
    		$pembayaran->save();

    		$daftar = Daftar::find($pembayaran->id_daftar);
            if ($daftar->promo=='true') {
                $roll = Daftar::where('nir',$daftar->nir);
                $LINKWA = [];
                $index = 1;
                foreach ($roll->get() as $key => $value) {
                    $daftar_pro = Daftar::find($value->id);
                    if (in_array($pembayaran->status, ['capture','settlement'])) {
                        $daftar_pro->flag_status = "Lunas";
                        $daftar_pro->save();
                    }
                    if (in_array($pembayaran->status, ['pending','authorize'])) {
                        $daftar_pro->flag_status = "Pending";
                    }
                    if (in_array($pembayaran->status, ['deny','cancel'])) {
                        $daftar_pro->flag_status = "Gagal";
                    }
                    if (in_array($pembayaran->status, ['expire'])) {
                        $daftar_pro->flag_status = "Expired";
                    }
                    $daftar_pro->save();
                    $LINKWA[$index] = $daftar_pro->link_wa;
                    $index++;
                };
            }
    		if (in_array($pembayaran->status, ['capture','settlement'])) {
    			$daftar->flag_status = "Lunas";
                $daftar->save();
                try {
                    $hash = urlencode(Hash::make('Webinar'.($pembayaran->id_webinar+1)));
                    $webinar = Webinar::find($pembayaran->id_webinar);
                    $daftar = Daftar::find($pembayaran->id_daftar);
                    $pecah_tgl_exp = explode(' ', $daftar->expired_daftar);
                    $subject = "Terima Kasih Sudah Melunasi Pembayaran Webinar #".$data_req['order_id'];
                    $nows = date('Y-m-d H:i:s');
                    $now = date('Y-m-d H:i:s',strtotime($nows . "+1 days"));
                    $tgl_exp = explode(' ', $now);
                    $tgl_exps = explode(' ', $nows);
                    if ($daftar->promo=='true') {
                        $email_template = file_get_contents(url('/email3.html'));
                        $email_body = str_replace('###JudulWebinar###',  'Paket Webinar Series', $email_template);
                    }else{
                        $email_template = file_get_contents(url('/email2.html'));
                        $email_body = str_replace('###JudulWebinar###',  $webinar->nama, $email_template);
                    }
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

                    if ($daftar->promo=='true') {
                        foreach ($LINKWA as $key => $value) {
                            $email_body = str_replace('###LINKWA'.$key.'###',  $value, $email_body);
                        }
                    }else{
                        $email_body = str_replace('###LINKWA###',  $daftar->link_wa, $email_body);
                        $email_body = str_replace('###LINKWEBNEXT###',  url('/webinar/view').'/'.($pembayaran->id_webinar+1).'?key='.$hash, $email_body);
                    }
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
    		if (in_array($pembayaran->status, ['pending','authorize'])) {
    			$daftar->flag_status = "Pending";
    		}
    		if (in_array($pembayaran->status, ['deny','cancel'])) {
    			$daftar->flag_status = "Gagal";
    		}
    		if (in_array($pembayaran->status, ['expire'])) {
    			$daftar->flag_status = "Expired";
    		}
    		$daftar->save();
    	}
    	return 'OKE';
    }


    public static function sendMail($email,$subject,$body,$id_daftar='')
    {
        try {
            $start = microtime(true);
            $mail = new PHPMailer(); // create a new object
            $mail->IsSMTP(); // enable SMTP
            // $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true; // authentication enabled
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // secure transfer enabled REQUIRED for Gmail
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587; // or 587
            $mail->Username = 'parijakpus@gmail.com';                 // SMTP username
            $mail->Password = 'parijakpus123';                           // SMTP password

            //Recipients
            $mail->setFrom('parijakpus@gmail.com', 'PARI DKI JAKARTA PUSAT');
            $mail->addReplyTo('parijakpus@gmail.com', 'PARI DKI JAKARTA PUSAT');
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);// Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;

            if ($mail->send()) {
                $messages = 'Message has been sent';
            	$log = new LogEmailSender;
            	$log->id_daftar = $id_daftar;
            	$log->email = $email;
            	$log->subject = $subject;
            	$log->body = $body;
            	$log->status = 'Terkirim';
            	$log->date = date('Y-m-d H:i:s');
            	$log->save();
            }else{
                $messages = 'Email Not Send';
            	$log = new LogEmailSender;
            	$log->id_daftar = $id_daftar;
            	$log->email = $email;
            	$log->subject = $subject;
            	$log->body = $body;
            	$log->status = 'Gagal';
            	$log->info_error = json_encode($mail->ErrorInfo,1);
            	$log->date = date('Y-m-d H:i:s');
            	$log->save();
                // $messages = 'Email Not Send.'. ;
            }                                 
        } 
        catch (\Exception $e) 
        {
            $messages = 'Config is not valid, Not Send Mail';
            $messages = 'Email Not Send';
            $log = new LogEmailSender;
            $log->id_daftar = $id_daftar;
            $log->email = $email;
            $log->subject = $subject;
            $log->body = $body;
            $log->status = 'Gagal';
            $log->info_error = json_encode($e->getMessage(),1);
            $log->date = date('Y-m-d H:i:s');
            $log->save();
        }
        return $messages;
    }
}
