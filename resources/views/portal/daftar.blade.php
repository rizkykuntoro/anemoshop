@extends('portal.layout.mastermini')
@section('content')
<style type="text/css">
  body { 
      width:450px; margin:0 auto !important;
      line-height:1; 
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
    padding: 20px;
    background-color: #ffffff;
  }
  .m-t-40 {
    margin-top: 40px !important;
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
</style>
<body class="mobile widescreen" style="overflow: visible;">
    <section>
      <div class="container-alt">
        <div class="row">
          <div class="col-lg-12">
            <div class="wrapper-page">
              <div class="m-t-40 account-pages">
                <div class="text-center account-logo-box">
                    <a class="navbar-brand" href="{{url('/')}}"><h2>Artificial <em>Intelligence</em></h2></a>
                </div>
                <div class="account-content">
                  <form method="post" action="{{url('/daftar')}}" id="ngregister" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="form-group ">
                      <div class="col-lg-12">
                        <input class="form-control" type="text" name="nama" required="" placeholder="Nama Lengkap">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-12">
                        <input class="form-control" type="text" name="telp" required="" placeholder="No. Telp / Handphone">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-12">
                        <input class="form-control" type="text" name="email" required="" placeholder="Email">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-12">
                        <input class="form-control" type="password" name="password" required="" placeholder="Password">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-12">
                        <input class="form-control" type="password" name="password2" required="" placeholder="Konfirmasi Password">
                      </div>
                    </div>
                    <div class="form-group account-btn text-center m-t-10">
                      <div class="col-lg-12">
                        <button type="submit" id="btnSubmit" class="btn w-md btn-bordered btn-danger" onclick="cekpass();return false;">Submit</button>
                      </div>
                    </div>
                  </form>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="row m-t-50" style="margin-bottom: 10px">
                <div class="col-sm-12 text-center">
                  <p class="text-muted">Sudah punya Akun?<a href="https://seminardokter.id/login" class="text-primary m-l-5"><b>Login</b></a></p>
                </div>
              </div>
            </div>
            <!-- end wrapper -->
          </div>
        </div>
      </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $("#btnSubmit").click(function () {
                var password = $("#txtPassword").val();
                var confirmPassword = $("#txtConfirmPassword").val();
                if (password != confirmPassword) {
                    alert("Passwords do not match.");
                    return false;
                }
                return true;
            });
    </script>
  </body>
@endsection