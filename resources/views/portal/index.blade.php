@extends('portal.layout.master')
@section('content')
    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <style type="text/css">
      .locked{
        background-color: #b6b6b6ed;
        position: absolute;
        z-index: 100;
        text-align: center;
        font-size: 60px;
      }
      .banner-item-01 .banner-item-02 .banner-item-03 {
        height: 716px;
      }
      @media only screen and (max-width: 1002px) {
        .banner-item-01 .banner-item-02 .banner-item-03 { 
            height: auto;
            width: 100% !important;  
        }
      }
      .center {
      border: 5px solid #FFFF00;

      display: flex;
      justify-content: center;
      }
    </style>
    <div class="banner header-text">
      <div class="owl-banner owl-carousel">
        <div class="banner-item-01">
          <div class="text-content">
            <h4></h4>
            <h2></h2>
          </div>
        </div>
        <div class="banner-item-02">
          <div class="text-content">
            <h4>WEBINAR</h4>
            <h2></h2>
          </div>
        </div>
        <div class="banner-item-03">
          <div class="text-content">
            <h4>WEBINAR</h4>
            <h2></h2>
          </div>
        </div>
      </div>
    </div>
    <!-- Banner Ends Here -->

    <div class="latest-products">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-heading">
              <h2>Webinar Series</h2>
              <a href="{{url('/webinar/list')}}">view all webinar <i class="fa fa-angle-right"></i></a>
            </div>
          </div>
          @php
          $index = 1;
          @endphp
          @foreach($webinar as $key => $value)
            @php
            $konten = strip_tags($value['konten']);
            $tgl = date('Y-m-d-l',strtotime($value['tanggal_mulai']));
            $sisakuota_pay = Helpers::sisaKuotapay($value['id']);
            $sisakuota_all = Helpers::sisaKuotaall($value['id']);

            $kunci = false;
            $disabled = false;
            $status = 'belum';
            $sisakuota_all_last = 0;
            if (isset($webinar_sebelum)){
                $status_webinar_sebelumnya = Helpers::cekstatuswebinar($webinar_sebelum);
                if($status_webinar_sebelumnya>0){
                  $status = 'sedang';
                  $cek_open_webinar_now = Helpers::cekopenwebinar($webinar_sebelum);
                  if(!$cek_open_webinar_now){
                      $disabled = true;
                  }
                }else{
                  $sisakuota_pay_last = Helpers::sisaKuotapay($webinar_sebelum);
                  $sisakuota_all_last = Helpers::sisaKuotaall($webinar_sebelum);
                  if ($sisakuota_pay_last<=150) {
                      $disabled = true;
                  }else if($sisakuota_all_last<=300){
                      $disabled = true;
                  }
                }
            }

            $status_webinar_now = Helpers::cekstatuswebinar($value['id']);
            if($status_webinar_now>0){
                $status = 'sudah';
            }
            @endphp
            <div class="col-md-4">
              <!-- <div class="locked" id="webinar_{{$value['id']}}" style="@if($kunci)display: none;@endif">
                <i class="fa fa-lock" style="margin-top: 50%;color: #3c3c3c;"></i>
                <h5>Menunggu Syarat Kuota Peserta Terpenuhi di Webinar 0{{$index-1}}</h5>
              </div> -->
              @if(!$disabled)
              <div class="product-item" id="item_{{$value['id']}}">
                <a href="{{url('/webinar/view')}}/{{$value['id']}}"><img src="{{url('/')}}/image_webinar/{{$value['thumbnail']}}" alt="" height=" 225px"></a>
                <div class="down-content" style="min-height: 240px">
                  <a href="{{url('/webinar/view')}}/{{$value['id']}}"><h4>Webinar 0{{$index}}</h4></a>
                  <h6 style="font-size: 14px;right: 10px;top: 32px;">( {{ Helpers::tanggalIndo($tgl) }})</h6>
                  <p>{{$value['nama']}}</p>
                  <span style="font-size: 15px;bottom: 20px;left: 30px;">Rp. {{ number_format($value['biaya'],2,",",".") }} </span><span style="font-size: 15px;bottom: 20px"> Sisa Kuota ({{300-$sisakuota_all}})</span>
                </div>
              </div>
              @else
              <div class="product-item" id="item_{{$value['id']}}" style="cursor: not-allowed;" data-toggle="modal" data-target="#myModal">
                <img src="{{url('/')}}/image_webinar/{{$value['thumbnail']}}" alt="" height=" 225px">
                <div class="down-content" style="min-height: 240px">
                  <h4>Webinar 0{{$index}}</h4>
                  <h6 style="font-size: 14px;right: 10px;top: 32px;">( {{ Helpers::tanggalIndo($tgl) }})</h6>
                  <p>{{$value['nama']}}</p>
                  <span style="font-size: 15px;bottom: 20px;left: 30px;">Rp. {{ number_format($value['biaya'],2,",",".") }} </span><span style="font-size: 15px;bottom: 20px"> @if($status=='sudah')Total Peserta ({{$sisakuota_all}})@elseif($status=='belum') Kuota Peserta (300)@endif</span>
                </div>
              </div>
              @endif
            </div>
          @php
          $webinar_sebelum = $value['id'];
          $index++;
          @endphp
          @endforeach
        </div>
      </div>
    </div>

    <div class="best-features">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-heading">
              <h2>PROMO WEBINAR</h2>
            </div>
          </div>
          @foreach($promo as $key => $value)
          @php
            $list_promo_id = explode(',',$value['id_webinar']);
            $list_bonus_id = explode(',',$value['id_bonus_webinar']);
            $idpromo = $value['id'];
          @endphp
            <div class="col-md-12 table-bordered">
                <img src="{{url('/')}}/image_promo/{{$value['thumbnail']}}" width="100%" height="auto" style="padding: 10px;cursor: pointer;"  data-toggle="modal" data-target="#myModalpromo{{$value['id']}}">
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="best-features">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-heading">
              <h2>TUJUAN KEGIATAN</h2>
            </div>
          </div>
          <div class="col-md-6">
            <div class="left-content">
              <!-- <h4>Looking for the best products?</h4> -->
              <p>1. Untuk memenuhi perolehan Satuan Kredit Profesi Radiografer (SKPR) yang telah di tetapkan.
                <br>
                <br>
                2. Radiografer dapat mengetahui tentang Teknik Radiografi Imajing.
                <br>
                <br>
                3. Untuk meningkatkan kompetensi radiografer dalam mengetahui update teknologi terkini.
              </p>
              <a href="{{url('/tentang-kami')}}" class="filled-button">Selengkapnya</a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="right-image" style="text-align: center;">
              <img src="{{url('/')}}/ass-portal/assets/images/poster.jpg" alt="" style="height: 400px;width: auto;">
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="call-to-action">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="inner-content">
              <div class="row">
                <div class="col-md-8">
                  <h4 style="margin-top: 35px;">Kerjasama-PP PARI-dengan-PARI PENGDA-DKI JAKARTA</h4>
                </div>
                <div class="col-md-4" style="text-align: center;">
                  <img src="{{url('/')}}/ass-portal/assets/images/logo-pari.png" alt="" height=" 100px">
                </div>
              </div>
            </div>
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
            <span class="modal-title" style="text-align: center;"><h5 style="margin-top: 5px">Ketentuan dan Peraturan</h5 style="margin-top: 5px"></span>
            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: right;">&times;</button>
            <!-- <button type="button" class="close" data-dismiss="modal" style="float: right;font-size: 25px">&times;</button> -->
          </div>
          
          <!-- Modal body -->
          <div class="modal-body">
            <p>
            Aturan : <br>
              - Masing-masing event berkuota maksimum 300 participant.<br>
              - Masing-masing event di arrange agar kuota pesertanya optimum.<br>
              - Link Event yang aktif adalah link event yang tayang lebih awal dan event yang terdekat.<br>
              - Link event lain yang belum aktif calon peserta belum bisa melakukan registrasi.<br><b style="font-weight: 500">
              - Adapun syarat link event lain aktif jika kuota registrasi 300 calon peserta.<br>
              - Dan atau penyelesaian adminitrasi peserta di event sebelumnya 150 participant.<br>
              - Calon peserta juga bisa mendaftarkan semua event di menu PROMO WEBINAR (joint 5 event + 1 free event) *<br></b>
              <br>
              <br>
            *Syarat dan ketentuan berlaku :<br>
             - Promo Webinar sesuai dengan kuota yang ada dalam aplikasi/ website.<br>
            </p>
          </div>
          
          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
          </div>
          
        </div>
      </div>
    </div>

     <!-- The Modal -->

    @foreach($promo as $key => $value)
    @php
      $list_promo_id = explode(',',$value['id_webinar']);
      $list_bonus_id = explode(',',$value['id_bonus_webinar']);
      $idpromo = $value['id'];
    @endphp
    <div class="modal fade" id="myModalpromo{{$idpromo}}"  style="z-index: 1050;">
      <div class="modal-dialog">
        <div class="modal-content">
        
          <!-- Modal Header -->
          <div class="modal-header">
            <span class="modal-title" style="text-align: center;width: 100%;"><font style="font-size: 20px">Form Pendaftaran</font><br>Promo : {{$value['nama_promo']}} <br> 
            Harga : Rp. {{ number_format(Helpers::totalbiayapromo($value['id_webinar']),2,",",".") }}<br>
            Sisa Kuota : {{50-Helpers::sisakuotapromo()}}</span>
            <button type="button" class="btn btn-danger" data-dismiss="modal">&times;</button>
          </div>
          
          <!-- Modal body -->
          <div class="modal-body">
            <style type="text/css">
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
                              <form method="post" action="{{url('/promobuy')}}" id="ngregister" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-group ">
                                  <div class="col-lg-12">
                                    <input class="form-control" type="hidden" name="id_webinar" value="{{$value['id_webinar'].','.$value['id_bonus_webinar']}}">
                                    <input class="form-control" type="hidden" name="id_webinar_notbonus" value="{{$value['id_webinar']}}">
                                    <input class="form-control" type="text" required="" name="nama"  placeholder="Nama Lengkap">
                                  </div>
                                </div>
                                <div class="form-group " style="height: 30px;">
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
                                    <input type="text" required="" name="" id="capcay" value="{{$varA}} + {{$varB}}" readonly="" style="background-color: #ddd;width: 60px;text-align: center;"><br>
                                    <label class="labelSurvey" for="jawaban">Jawaban : </label>
                                    <input class="form-control" type="text" required="" name="jawaban" id="jawaban" style="    font-size: 18px;">
                                    <input class="form-control" type="hidden" required="" value="{{$varA+$varB}}" id="jawaban_benar">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="row" style="padding-left: 15px;padding-right: 15px;">
                                    <div class="col-md-12" style="padding-left: 15px;padding-right: 15px;padding-bottom: 10px">
                                      <span>Pilih Metode Pembayaran :</span><br>
                                      <span style="font-size: 12px">Transfer Virtual Account</span>
                                    </div>
                                    <div class="col-md-12 radio-toolbar" style="text-align: center;">
                                      <!-- <input type="radio" required="false" name="payment" id="briVA" value="briVA">
                                      <label class="labelradio" for="briVA">
                                          <div class="col-md-12" style="text-align: center;">
                                            <img src="{{url('/')}}/ass-portal/logo-bank/bank-bri.png" height="25px">
                                          </div>
                                          Bank BRI
                                      </label> -->
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
                                      </label><!-- 
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
                                      </label> -->
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
                  <div class="col-md-12">
                    <span id='expand_rule_open' style="font-size: 14px;color: #307fe2;cursor: pointer;" onclick="$('#rules').slideDown(500);$('#expand_rule_open').hide();$('#expand_rule_close').show();">[KETENTUAN DAN TATA TERTIB PESERTA WEBINAR] <br></span>
                    <span id='expand_rule_close' style="font-size: 14px;color: #307fe2;cursor: pointer;display: none;" onclick="$('#rules').slideUp();$('#expand_rule_open').show();$('#expand_rule_close').hide();">[KETENTUAN DAN TATA TERTIB PESERTA WEBINAR] <br></span>
                    <p style="text-align: justify;display: none;margin-top: 10px;" id="rules">Berikut disampaikan ketentuan dan tata tertib selama pelaksanaan webinar :<br><br>
                     1. Peserta tidak diperkenankan menyebarluaskan tautan zoom meeting dan WAJIB bergabung dalam Zoom meeting selama kegiatan.<br>
                     2. Peserta wajib menggunakan Nama akun zoom yang sama dengan nama yang didaftarkan dan Nomor peserta yang diperoleh dari panitia.<br>
                     contoh: EJKP-1801_Darma. Panitia tidak akan &lsquo;admit&rsquo; bagi ID di luar ketentuan panitia.<br>
                     3. Peserta dapat bergabung dan masuk ke dalam ruang pertemuan pada pukul 08.30 WIB.<br>
                     4. Peserta wajib menonaktifkan suara (MUTE) saat bergabung zoom meeting.<br>
                     5. Peserta wajib mengaktifkan video (VIDEO ON) saat kegiatan berlangsung menggunakan Virtual Background sesuai dengan ketentuan.<br>
                     6. Pertanyaan-pertanyaan terkait materi webinar diajukan secara tertulis via tautan yang akan diberikan saat webinar berlangsung.<br>
                     7. Peserta mengisi daftar kehadiran webinar sekaligus pre dan post test pada tautan google form yang akan diberikan saat webinar berlangsung.<br>
                     8. Setelah sesi diskusi dan tanya jawab, peserta wajib mengikuti evaluasi sebagai syarat untuk memperoleh sertifikat.<br>
                     9. E-Sertifikat hanya akan diberikan pada peserta yang mengikuti webinar sampai selesai, mengisi daftar hadir, mengerjakan pre dan post test serta lulus evaluasi dari panitia.<br>
                     10. E-Sertifikat akan dikirim melalui akun siap.pari.or.id masing-masing peserta paling lambat H+14.</p>
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
                        url: "{{url('/promobuy')}}",
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
    @endforeach

    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $( document ).ready(function() {
            var product_width = $('.product-item').width();
            $('.locked').css("width",product_width+2);

            var left_promo1 = $('#left_promo1').height();
            $('#left_promo2').css("min-height",left_promo1+2);

            for (let i = 1; i <= 6; i++) {
              var product_height = $('#item_'+i).height();
              $('#webinar_'+i).css("height",product_height+2);
            }
        });
    </script>
@endsection