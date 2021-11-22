@extends('admin.layout.master')
@section('content')	
	<style type="text/css">
		.form-check-label{
			font-weight: 100;
		}
	</style>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li><a href="{{ url('/admin/daftar-peserta') }}"> Daftar Peserta </a></li>
				@if(!isset($id_edit))
				<li class="active">Mendaftarkan Peserta</li>
				@else
				<li class="active">User Edit</li>
				@endif
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				@if(!isset($id_edit))
				<h1 class="page-header">Mendaftarkan Peserta</h1>
				@else
				<h1 class="page-header">User Edit</h1>
				@endif
			</div>
		</div><!--/.row-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
							@if(!isset($id_edit))
							<form action="{{ url('/admin/create-peserta') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
							@else
							<form action="{{ url('/admin/user/edit') }}/{{$id_edit}}" method="POST" enctype="multipart/form-data">
							@endif
  								{{ csrf_field() }}
								<div class="form-group">
									<label>Nama</label>
									<input class="form-control" name="nama" id="nama" placeholder="Nama Peserta" required>
								</div>
								<div class="form-group">
									<label>Nomor Induk Radiografer</label>
									<input class="form-control" name="nir" id="nir" type="number" placeholder="Nomor Induk Radiografer" required>
								</div>
								<div class="form-group">
									<label>Nomor Handphone</label>
									<input class="form-control" name="nohp" id="nohp" type="number" placeholder="Nomor Handphone" required>
								</div>
								<div class="form-group">
									<label>Nomor Whatsapp</label>
									<input class="form-control" name="no_wa" id="no_wa" type="number" placeholder="Nomor Whatsapp" required>
								</div>
								<div class="form-group">
									<label>Email</label>
									<input class="form-control" name="email" id="email" placeholder="Email" type="email" required>
								</div>
								<div class="form-group">
									<label>Nama Instansi</label>
									<input class="form-control" name="instasi" id="instasi" placeholder="Nama Instansi Peserta" required>
								</div>
								<div class="form-group">
									<label>Pengcab</label>
									<input class="form-control" name="pengcap" id="pengcap" placeholder="Pengcab" required>
								</div>
								<div class="form-group">
									<label>Pilih Webinar Yang ingin di ikuti :</label>
									@foreach($listwebinar as $value)
									<div class="form-check">
									  <label class="form-check-label">
									    <input type="checkbox" class="form-check-input" name="id_webinar[]" value="{{$value['id']}}"> Webinar 0{{$value['id']}} : {{$value['nama']}}
									  </label>
									</div>
									@endforeach
								</div>
								<button type="submit" class="btn btn-primary">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>

		<script type="text/javascript">
		  $( document ).ready(function() {
		      $('#nama').val("{{$model['nama']}}");
		      $('#nir').val("{{$model['nir']}}");
		      $('#nohp').val("{{$model['nohp']}}");
		      $('#no_wa').val("{{$model['no_wa']}}");
		      $('#email').val("{{$model['email']}}");
		      $('#instasi').val("{{$model['nama_instansi_kerja']}}");
		      $('#pengcap').val("{{$model['nama_pengcap']}}");
		  });
		</script>
@endsection