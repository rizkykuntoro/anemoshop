@extends('admin.layout.master')
@section('content')	
    <script src="{{url('/')}}/ass-admin/js/tinymce/tinymce.min.js"></script>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li><a href="{{ url('/admin/webinar') }}"> Webinar List </a></li>
				@if(!isset($id_edit))
				<li class="active">Webinar Create</li>
				@else
				<li class="active">Webinar Edit</li>
				@endif
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				@if(!isset($id_edit))
				<h1 class="page-header">Webinar Create</h1>
				@else
				<h1 class="page-header">Webinar Edit</h1>
				@endif
			</div>
		</div><!--/.row-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
							@if(!isset($id_edit))
							<form action="{{ url('/admin/webinar/create') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
							@else
							<form action="{{ url('/admin/webinar/edit') }}/{{$id_edit}}" method="POST" enctype="multipart/form-data">
							@endif
  								{{ csrf_field() }}
								<div class="form-group">
									<label>Nama Webinar</label>
									<input class="form-control" name="nama" id="nama" placeholder="Nama Webinar" required>
								</div>
								<div class="form-group">
									<label>Jenis</label>
									<select class="form-control" name="jenis" id="jenis" required>
										<option value="webinar">Webinar</option>
									</select>
								</div>
								<div class="form-group">
									<label>Total SKPR</label>
									<input class="form-control" name="total_skp" id="total_skp" placeholder="Total SKPR"  autocomplete="false"  required>
								</div>
								<div class="form-group">
									<label>Tanggal Mulai</label>
									<input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="datetime-local" required>
								</div>
								<div class="form-group">
									<label>Tanggal Selesai</label>
									<input class="form-control" name="tanggal_selesai" id="tanggal_selesai" type="datetime-local" required>
								</div>
								<div class="form-group">
									<label>Tempat</label>
									<input class="form-control" name="tempat" id="tempat" placeholder="Tempat"  required>
								</div>
								<div class="form-group">
									<label>Panitia</label>
									<input class="form-control" name="panitia" id="panitia" placeholder="Panitia"  required>
								</div>
								<div class="form-group">
									<label>Kontak</label>
									<input class="form-control" name="kontak" id="kontak" placeholder="Kontak"  required>
								</div>
								<div class="form-group">
									<label>Biaya</label>
									<input class="form-control" name="biaya" id="biaya" placeholder="Biaya" required>
								</div>
								<div class="form-group">
									<label>Link GROUP WA</label>
									<input class="form-control" name="link1" id="link1" placeholder="Link GROUP WA" required>
								</div>
								<div class="form-group">
									<label>Link GROUP WA2</label>
									<input class="form-control" name="link2" id="link2" placeholder="Link GROUP WA2" required>
								</div>
								<div class="form-group">
									<label>Thumbnail</label>
									<input type="file" name="thumbnail" accept="image/*" id="thumbnail">
									<p class="help-block">Silahkan Pilih gambar dengan ukuran maks.3Mb</p>
								</div>
								<div class="form-group">
									<label>Brosur</label>
									<input type="file" name="brosur" accept="image/*" id="brosur">
									<p class="help-block">Silahkan Pilih gambar dengan ukuran maks.3Mb</p>
								</div>
								<div class="form-group">
									<label>Konten</label>
									<textarea class="form-control" rows="8" name="konten" id="konten"></textarea>
								</div>
								<button type="submit" class="btn btn-primary">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
        <script type='text/javascript'> 
        tinymce.init({ selector:'textarea', menubar:'', theme: 'modern',entity_encoding : "raw"});
        </script>
		<script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" crossorigin="anonymous"></script>





		<script type="text/javascript">
		  $( document ).ready(function() {
		      $('#nama').val("{{$model['nama']}}");
		      $('#jenis').val("{{$model['jenis']}}").trigger('change');
		      $('#total_skp').val("{{$model['total_skp']}}");
		      $('#tanggal_mulai').val(moment("{{$model['tanggal_mulai']}}").format("YYYY-MM-DDTkk:mm"));
		      $('#tanggal_selesai').val(moment("{{$model['tanggal_selesai']}}").format("YYYY-MM-DDTkk:mm"));
		      $('#tempat').val("{{$model['tempat']}}");
		      $('#panitia').val("{{$model['panitia']}}");
		      $('#kontak').val("{{$model['kontak']}}");
		      $('#biaya').val("{{$model['biaya']}}");
		      $('#link1').val("{{$model['link1']}}");
		      $('#link2').val("{{$model['link2']}}");
		      $('#konten').val("{{json_encode($model['konten'],1)}}");
		  });
		</script>
@endsection