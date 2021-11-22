@extends('portal.layout.master')
@section('content')
<style type="text/css">
  header{
    top: 0;
  }
  table,tr,td{
    border:0.2px solid gray;
    font-size: 12px;
    text-align: left;
    padding-left: 5px;
  }
  .isi-table{
    font-size: 12px;
  }
  .brosur_image { 
      /*height: 500px;width:  auto !important;*/
      height: auto;
      width: 100% !important;   
  }
  @media only screen and (max-width: 1002px) {
    .brosur_image { 
        height: auto;
        width: 100% !important;    
        padding-bottom: 20px;
    }
  }
</style>
    <div class="section-heading" style="margin-top: 80px;background: #3093cc;">
      <h2 style="padding: 20px 10%;margin-bottom: 0; font-size: 20px; font-weight: 700;color: white;">{{$data['nama']}}</h2>
    </div>
    <div class="best-features">
      <div class="container" style="margin-left: 10%;margin-right: 5%;max-width: 85%;">
        <div class="row">
          <div class="col-md-8">
            <div class="left-content">
              <div class="center-content" style="text-align: center;">
                <img class="brosur_image" src="{{url('/')}}/image_webinar/{{$data['brosur']}}" style="">
              </div>
              <div class="center-content">
                {!! $data['konten'] !!}
                <!-- <h5 style="text-align: center;padding-top: 20px">Harga : Rp. {{ number_format($data['biaya'],2,",",".") }}</h5> -->
              </div>
            </div>
          </div>
          <div class="col-md-1">
            
          </div>

          @php
            $sisakuota_all = Helpers::sisaKuotaall($data['id']);
            $sisa_kuota = ceil(300-$sisakuota_all);
            $disabled = false;
            if($sisa_kuota==0){
              $disabled = true;
            }
            $status_webinar_now = Helpers::cekstatuswebinar($data['id']);
            if($status_webinar_now > 0){
              $disabled = true;
            }
          @endphp

          <div class="col-md-3 table-bordered" style="text-align: center;height: fit-content;padding-bottom: 10px">
            @if($disabled)
              <button type="button" class="btn btn-primary" style="cursor: not-allowed;background-color: #7c7f7c;font-weight: 500;margin-bottom: 10px;margin-top: 10px;">
               <i class="fa fa-lock" style="color: white;"></i> Form Pendaftaran <i class="fa fa-lock" style="color: white;"></i>
              </button>
            @else
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="background-color: #4ac353;font-weight: 500;margin-bottom: 10px;margin-top: 10px;">
               Form Pendaftaran
              </button>
            @endif
              <table style="width: 100%;margin-top: 5px;" class="table table-bordered">
                <tr>
                  <td style="min-width: 105px;">Jenis : </td>
                  <td class="isi-table"><span class="badge badge-danger">{{strtoupper($data['jenis'])}}</span></td>
                </tr>
                <tr>
                  <td style="min-width: 105px;">Total SKPR : </td>
                  <td class="isi-table">{{$data['total_skp']}}</td>
                </tr>
                <tr>
                  <td style="min-width: 105px;">Mulai : </td>
                  <td class="isi-table">{{Helpers::tanggalIndotb(date('Y-m-d H:i',strtotime($data['tanggal_mulai'])))}}</td>
                </tr>
                <tr>
                  <td style="min-width: 105px;">Selesai : </td>
                  <td class="isi-table">{{Helpers::tanggalIndotb(date('Y-m-d H:i',strtotime($data['tanggal_selesai'])))}}</td>
                </tr>
                <tr>
                  <td style="min-width: 105px;">Tempat : </td>
                  <td class="isi-table">{{$data['tempat']}}</td>
                </tr>
                <tr>
                  <td style="min-width: 105px;">Moderator : </td>
                  <td class="isi-table">{{$data['panitia']}}</td>
                </tr>
                <tr>
                  @if($disabled)
                  <td style="min-width: 105px;">Peserta : </td>
                  <td class="isi-table">{{Helpers::sisaKuotaall($data['id'])}}</td>
                  @else
                  <td style="min-width: 105px;">Sisa Kuota : </td>
                  <td class="isi-table">{{300-Helpers::sisaKuotaall($data['id'])}}</td>
                  @endif
                </tr>
                <tr>
                  <td style="min-width: 105px">Kontak : </td>
                  <td class="isi-table">{{$data['kontak']}}</td>
                </tr>
              </table>
              <br>
                <h6 style="text-align: left;float: left;border-bottom: 2px solid #3093cc;padding-bottom: 10px;margin-bottom: 10px;">Webinar Series</h6>
              @foreach($listwebinar as $key => $value)
              @php
                $kunci = false;
                if (isset($webinar_sebelum)){
                  $sisakuota_pay_last = Helpers::sisaKuotapay($webinar_sebelum);
                  $sisakuota_all_last = Helpers::sisaKuotaall($webinar_sebelum);
                  if ($sisakuota_pay_last>=150) {
                      $kunci = true;
                  }else if($sisakuota_all_last>=300){
                      $kunci = true;
                  }
                  $status_webinar_sebelumnya = Helpers::cekstatuswebinar($webinar_sebelum);
                  if($status_webinar_sebelumnya > 0){
                    $kunci = true;
                  }
                }else{
                  $kunci = true;
                }

                $webinar_sebelum = $value['id'];
              @endphp
              <a href="@if($kunci){{url('/webinar/view')}}/{{$value['id']}}@else # @endif" style="@if(!$kunci)cursor: not-allowed;@endif" @if(!$kunci)data-toggle="modal" data-target="#myModalRules"@endif>
                <div style="background: {{Helpers::list_warna($key)}};float: left;padding: 15px;@if($key!=0)margin-top: 10px;@endif">
                  <i class="fa fa-lock" style="color: white;@if($kunci)display: none;@endif"></i>
                  <h6 style="color: white;text-align: center;">{{$value['nama']}}</h6>
                </div>
              </a>
              @endforeach
              <br>
          </div>
        </div>
      </div>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="myModal"  style="z-index: 1050;">
      <div class="modal-dialog">
        <div class="modal-content">
        
          <!-- Modal Header -->
          <div class="modal-header">
            <span class="modal-title" style="text-align: center;"><font style="font-size: 20px">Form Pendaftaran</font><br>Webinar : {{$data['nama']}} <br> 
            Harga : Rp. {{ number_format($data['biaya'],2,",",".") }}</span>
            <button type="button" class="btn btn-danger" data-dismiss="modal">&times;</button>
            <!-- <button type="button" class="close" data-dismiss="modal" style="float: right;font-size: 25px">&times;</button> -->
          </div>
          
          <!-- Modal body -->
          <div class="modal-body">
            <style type="text/css">
              body { 
                  /*width:450px; margin:0 auto !important;*/
                  /*line-height:1; */
              }
              section {
                box-shadow: 0 0px 24px 0 rgb(0 0 0 / 6%), 0 1px 0px 0 rgb(0 0 0 / 2%);
                border-radius: 5px;
              }
              .account-logo-box {
                background-color: #505458;
                padding: 10px;
                border-radius: 5px 5px 0 0;
              }
              .account-pages .account-content {
                background-color: #ffffff;
              }
              .form-control {
                resize: none;
                border: 0;
                background-color: transparent;
                border-bottom: 1px solid rgba(152, 152, 152, 0.8);
                border-radius: 0 !important;
                padding: 7px 12px 7px 0;
                height: 40px;
                max-width: 100%;
                -webkit-box-shadow: none;
                box-shadow: none;
                -webkit-transition: all 300ms linear;
                -moz-transition: all 300ms linear;
                -o-transition: all 300ms linear;
                transition: all 300ms linear;
                font-size: 12px;
              }
              .radio-toolbar {
                margin: 10px;
              }

              .radio-toolbar input[type="radio"] {
                opacity: 0;
                position: fixed;
                width: 0;
              }

              .radio-toolbar label {
                  display: inline-block;
                  background-color: #ddd;
                  padding: 10px 20px;
                  font-family: sans-serif, Arial;
                  font-size: 16px;
                  border: 2px solid #444;
                  border-radius: 4px;
                  text-align: center;
              }

              .radio-toolbar label:hover {
                background-color: #dfd;
                cursor: pointer;
              }

              .radio-toolbar input[type="radio"]:focus + label {
                  border: 2px dashed #444;
              }

              .radio-toolbar input[type="radio"]:checked + label {
                  background-color: #bfb;
                  border-color: #4c4;
              }
              .labelSurvey{
                font-size: 13px;
              }
            </style>
            <style>
              .loader {
                border: 16px solid #f3f3f3;
                border-radius: 50%;
                border-top: 16px solid #dc3545;
                width: 120px;
                height: 120px;
                -webkit-animation: spin 2s linear infinite; /* Safari */
                animation: spin 2s linear infinite;
                display: block;
                margin-left: auto;
                margin-right: auto;
              }

              /* Safari */
              @-webkit-keyframes spin {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
              }

              @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
              }
            </style>
            <body class="mobile widescreen" style="overflow: visible;">
                <section id="form_pendaftaran">
                  <div class="container-alt">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="wrapper-page">
                          <div class="m-t-40 account-pages">
                            <div class="account-content">
                              <form method="post" action="{{url('/daftar')}}" id="ngregister" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-group ">
                                  <div class="col-lg-12">
                                    <input class="form-control" type="hidden" name="id_webinar" value="{{$data['id']}}">
                                    <input class="form-control" type="text" required="" name="nama"  placeholder="Nama Lengkap">
                                  </div>
                                </div>
                                <div class="form-group ">
                                  <div class="col-lg-12">
                                    <input class="form-control" type="number" required="" name="nir"  placeholder="Nomor Induk Radiografer" style="width: 75%;float: left;">
                                    <a href="https://siap.pari.or.id/anggota" class="btn w-md btn-bordered btn-secondary" target="_blank" style="float: right;">Cek NIR</a>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12">
                                    <input class="form-control" type="number" required="" name="nohp"  placeholder="No. HP (081xxxxxxxxx)">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12">
                                    <input class="form-control" type="number" required="" name="nowa"  placeholder="No. WA (081xxxxxxxxx)">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12">
                                    <input class="form-control" type="text" required="" name="email"  placeholder="Email">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12">
                                    <input class="form-control" type="text" required="" name="instasi"  placeholder="Nama Instansi">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-lg-12">
                                    <input class="form-control" type="text" required="" name="pengcab"  placeholder="Nama Pengcab">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="row" style="padding-left: 15px;padding-right: 15px;">
                                    <div class="col-md-12" style="padding-left: 15px;padding-right: 15px;padding-bottom: 10px">
                                      <span>Pre Survey</span><br>
                                      <span style="font-size: 12px">Darimana anda mengetahui acara kegiatan ini ?</span>
                                        <div class="col-md-7">
                                          <input type="radio" required="false" name="presurvey" id="pilihA" value="Media Sosial Siberkreasi">
                                          <label class="labelSurvey" for="pilihA"> A. Media Sosial Siberkreasi</label>
                                        </div>
                                        <div class="col-md-7">
                                          <input type="radio" required="false" name="presurvey" id="pilihB" value="Broadcast Whatsapp">
                                          <label class="labelSurvey" for="pilihB"> B. Broadcast Whatsapp</label>
                                        </div>
                                        <div class="col-md-7">
                                          <input type="radio" required="false" name="presurvey" id="pilihC" value="Teman/Keluarga">
                                          <label class="labelSurvey" for="pilihC"> C. Teman/Keluarga</label>
                                        </div>
                                        <div class="col-md-7">
                                          <input type="radio" required="false" name="presurvey" id="pilihD" value="Komunitas">
                                          <label class="labelSurvey" for="pilihD"> D. Komunitas</label>
                                        </div>
                                        <div class="col-md-7">
                                          <input type="radio" required="false" name="presurvey" id="pilihE" value="Lainnya">
                                          <label class="labelSurvey" for="pilihE"> E. Lainnya</label>
                                        </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <div class="col-lg-12">
                                    @php
                                     $varA = rand(1,9);
                                     $varB = rand(1,9);
                                    @endphp
                                    <label class="labelSurvey" for="capcay">Captcha</label><br>
                                    <input type="text" required="" name="" id="capcay" value="{{$varA}} x {{$varB}}" readonly="" style="background-color: #ddd;width: 50px;text-align: center;"><br>
                                    <label class="labelSurvey" for="jawaban">Jawaban : </label>
                                    <input class="form-control" type="text" required="" name="jawaban" id="jawaban" style="    font-size: 18px;">
                                    <input class="form-control" type="hidden" required="" value="{{$varA*$varB}}" id="jawaban_benar">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="row" style="padding-left: 15px;padding-right: 15px;">
                                    <div class="col-md-12" style="padding-left: 15px;padding-right: 15px;padding-bottom: 10px">
                                      <span>Pilih Metode Pembayaran :</span><br>
                                      <span style="font-size: 12px">Transfer Virtual Account</span>
                                    </div>
                                    <div class="col-md-12 radio-toolbar" style="text-align: center;">
                                      <input type="radio" required="false" name="payment" id="briVA" value="briVA">
                                      <label class="labelradio" for="briVA">
                                          <div class="col-md-12" style="text-align: center;">
                                            <img src="{{url('/')}}/ass-portal/logo-bank/bank-bri.png" height="25px">
                                          </div>
                                          Bank BRI
                                      </label>
                                      <input type="radio" required="" name="payment" id="permataVA" value="permataVA">
                                      <label class="labelradio" for="permataVA">
                                          <div class="col-md-12" style="text-align: center;">
                                            <img src="{{url('/')}}/ass-portal/logo-bank/permata.png" height="25px">
                                          </div>
                                          Bank Permata
                                      </label>
                                      <input type="radio" required="" name="payment" id="mandiriBILL" value="mandiriBILL">
                                      <label class="labelradio" for="mandiriBILL">
                                          <div class="col-md-12" style="text-align: center;">
                                            <img src="{{url('/')}}/ass-portal/logo-bank/mandiri.png" height="25px">
                                          </div>
                                          Bank Mandiri
                                      </label>
                                      <input type="radio" required="" name="payment" id="bniVA" value="bniVA">
                                      <label class="labelradio" for="bniVA">
                                          <div class="col-md-12" style="text-align: center;">
                                            <img src="{{url('/')}}/ass-portal/logo-bank/bni.png" height="25px">
                                          </div>
                                          Bank BNI
                                      </label>
                                      <input type="radio" required="" name="payment" id="bcaVA" value="bcaVA">
                                      <label class="labelradio" for="bcaVA">
                                          <div class="col-md-12" style="text-align: center;">
                                            <img src="{{url('/')}}/ass-portal/logo-bank/bca.png" height="25px">
                                          </div>
                                          Bank BCA
                                      </label>
                                    </div>
                                  </div>
                                </div>

                               
                                <div class="form-group account-btn text-center m-t-10">
                                  <div class="col-lg-12" style="text-align: center;">
                                    <button type="submit" id="btnSubmit" class="btn w-md btn-bordered btn-danger" >Daftar Webinar</button>
                                  </div>
                                </div>
                              </form>
                              <div class="clearfix"></div>
                            </div>
                          </div>
                        </div>
                        <!-- end wrapper -->
                      </div>
                    </div>
                  </div>
                </section>
                <section id="loading" style="display: none;">
                  <div class="loader"></div>
                  <br>
                  <div style="text-align: center;"><h5>Mohon Tunggu</h5></div>
                  <div style="text-align: center;"><h6>Sedang Memproses Transaksi</h6></div>
                </section>
                <section id="payment_form" style="display: none;">
                  <div class="col-md-12" style="text-align: center;">
                    <img src="{{url('/')}}/ass-portal/assets/images/payment-waiting.png" height="200px">
                  </div>
                  <div class="col-md-12">
                    <span style="font-size: 12px;">Lakukan Pembayaran Sebelum : <br> <font style="font-size: 15px" id="tgl_kadaluarsa"></font><font id='countdown' style="font-size: 14px;float: right;color: #f33030">23:59:59</font></span>
                  </div>
                  <hr>
                  <div class="col-md-12">
                    Metode Pembayaran 
                  </div>
                  <div class="col-md-12" style="padding-top: 10px;">
                    <span style="display: none;" id="logo_bcaVA"><img src="{{url('/')}}/ass-portal/logo-bank/bca.png" height="25px"> <font style="padding-left: 25px;font-size: 12px;font-weight: 700;">BCA</font></span>
                    <span style="display: none;" id="logo_bniVA"><img src="{{url('/')}}/ass-portal/logo-bank/bni.png" height="25px"> <font style="padding-left: 25px;font-size: 12px;font-weight: 700;">BNI</font></span>
                    <span style="display: none;" id="logo_mandiriBILL"><img src="{{url('/')}}/ass-portal/logo-bank/mandiri.png" height="25px"> <font style="padding-left: 25px;font-size: 12px;font-weight: 700;">MANDIRI</font></span>
                    <span style="display: none;" id="logo_permataVA"><img src="{{url('/')}}/ass-portal/logo-bank/permata.png" height="25px"> <font style="padding-left: 25px;font-size: 12px;font-weight: 700;">PERMATA</font></span>
                    <span style="display: none;" id="logo_briVA"><img src="{{url('/')}}/ass-portal/logo-bank/bank-bri.png" height="25px"> <font style="padding-left: 25px;font-size: 12px;font-weight: 700;">BRI</font></span>
                  </div>
                  <div class="col-md-12" style="padding-bottom: 10px">
                    <span style="font-size: 12px;">Kode Pembayaran <br> <input id="va_number" type="text" value="" readonly="" style="font-size: 15px;font-weight: 500;"><font id='salin' style="font-size: 14px;float: right;color: #307fe2;cursor: pointer;" onclick="copy_text();">salin</font></span>
                  </div>
                  <hr>
                  <div class="col-md-12">
                    <span style="font-size: 12px;">Total Pembayaran <br> <font style="font-size: 15px;color: #f33030;" id="total_pembayaran"></font>
                  </div>
                  <hr>
                  <div class="col-md-12" style="text-align: center;border: 3px solid red;padding: 10px;">
                     Selalu cek email anda jika sudah melakukan pembayaran, jika tidak ada di pesan masuk silahkan lihat pada bagian spam.
                  </div>
                </section>
                <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
                <script type="text/javascript">
                  $(document).ready(function () {
                    $("form").submit(function (event) {
                      var jawab = $('#jawaban').val()
                      var jawaban = $('#jawaban_benar').val()
                      if (jawab!=jawaban) {
                          Swal.fire(
                            'Jawaban Captcha Salah !',
                            'Silahkan isi dengan Benar.',
                            'warning',
                          )
                          return false;
                      }
                      var formData = $('#ngregister').serialize();
                      var title = $('.modal-title').html();
                      $('.modal-title').text('Menunggu Pembayaran');
                      $('#loading').show();
                      $('#form_pendaftaran').hide();
                      $.ajax({
                        type: "POST",
                        url: "{{url('/daftar')}}",
                        data: formData,
                        dataType: "json",
                        encode: true,
                      }).done(function (data) {
                        if (data.status=='success' && data.data.va_numbers!='') {
                          $('#loading').hide();
                          $('#payment_form').show();
                          $('#tgl_kadaluarsa').text(data.data.expired_date);
                          $('#va_number').val(data.data.va_numbers);
                          $('#total_pembayaran').html(data.data.total_pembayaran);
                          $('#logo_'+data.data.type_bayar).show();
                          countdown(data.data.real_expired_date);
                        }else{
                          Swal.fire(
                            data.title,
                            data.message,
                            data.status,
                          )
                          $('#loading').hide();
                          $('#payment_form').hide();
                          $('#form_pendaftaran').show();
                          $('.modal-title').html(title);
                        }
                      });

                      event.preventDefault();
                    });
                  });

                  function copy_text() {
                      var valueText = $("#va_number").select().text();
                      document.execCommand("copy");
                      alert('Kode Pembayaran Telah disalin');
                  }

                  function countdown(date) {
                    // Set the date we're counting down to "Jan 5, 2022 15:37:25"
                    var countDownDate = new Date(date).getTime();

                    // Update the count down every 1 second
                    var x = setInterval(function() {

                      // Get today's date and time
                      var now = new Date().getTime();

                      // Find the distance between now and the count down date
                      var distance = countDownDate - now;

                      // Time calculations for days, hours, minutes and seconds
                      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                      // Display the result in the element with id="demo"
                      document.getElementById("countdown").innerHTML = hours + ":"
                      + minutes + ":" + seconds;

                      // If the count down is finished, write some text
                      if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("countdown").innerHTML = "EXPIRED";
                      }
                    }, 1000);
                  }
                </script>
              </body>
          </div>
          
          <!-- Modal footer -->
          <div class="modal-footer">
            <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
          </div>
          
        </div>
      </div>
    </div>

     <!-- The Modal -->
    <div class="modal fade" id="myModalRules"  style="z-index: 1050;">
      <div class="modal-dialog">
        <div class="modal-content">
        
          <!-- Modal Header -->
          <div class="modal-header">
            <span class="modal-title" style="text-align: center;"><h5 style="margin-top: 5px">Ketentuan dan Peraturan</h5 style="margin-top: 5px"></span>
            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: right;">&times;</button>
            <!-- <button type="button" class="close" data-dismiss="modal" style="float: right;font-size: 25px">&times;</button> -->
          </div>
          
          <!-- Modal body -->
          <div class="modal-body">
          </div>
          
          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
          
        </div>
      </div>
    </div>
@endsection