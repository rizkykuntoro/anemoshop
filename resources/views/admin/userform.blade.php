@extends('admin.layout.master')
@section('content')	
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li><a href="{{ url('/admin/user') }}"> User List </a></li>
				@if(!isset($id_edit))
				<li class="active">User Create</li>
				@else
				<li class="active">User Edit</li>
				@endif
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				@if(!isset($id_edit))
				<h1 class="page-header">User Create</h1>
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
							<form action="{{ url('/admin/user/create') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
							@else
							<form action="{{ url('/admin/user/edit') }}/{{$id_edit}}" method="POST" enctype="multipart/form-data">
							@endif
  								{{ csrf_field() }}
								<div class="form-group">
									<label>Nama</label>
									<input class="form-control" name="name" id="nama" placeholder="Nama User" required>
								</div>
								<div class="form-group">
									<label>Email</label>
									<input class="form-control" name="email" id="email" placeholder="Email" type="email" required>
								</div>
								<div class="form-group">
									<label>Password</label>
									<input class="form-control" name="password" id="password" type="password" required>
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
		      $('#nama').val("{{$model['name']}}");
		      $('#email').val("{{$model['email']}}");
		      $('#password').val("{{$model['password']}}");
		  });
		</script>
@endsection