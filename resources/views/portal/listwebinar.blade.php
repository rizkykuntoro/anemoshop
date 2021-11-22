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
    </style>

<style type="text/css">
  header{
    top: 0;
    position: fixed;
  }
</style>
    <!-- <div class="banner header-text"> -->
    <!-- </div> -->

    <div class="latest-products" style="margin-top: 100px;">
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

    <div class="call-to-action">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="inner-content">
              <div class="row">
                <div class="col-md-8">
                  <h4 style="margin-top: 35px;">PARI PENGDA-PENGCAB DKI JAKARTA</h4>
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
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
          
        </div>
      </div>
    </div

    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $( document ).ready(function() {
            var product_width = $('.product-item').width();
            $('.locked').css("width",product_width+2);

            for (let i = 1; i <= 6; i++) {
              var product_height = $('#item_'+i).height();
              $('#webinar_'+i).css("height",product_height+2);
            }
        });
    </script>
@endsection