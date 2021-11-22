<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lumino - Login</title>
	<link href="{{url('/')}}/ass-admin/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{url('/')}}/ass-admin/css/datepicker3.css" rel="stylesheet">
	<link href="{{url('/')}}/ass-admin/css/styles.css" rel="stylesheet">
</head>
<body>
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">Log in</div>
				<div class="panel-body">
					<form role="form" action="{{url('/admin/login')}}" method="post">
						<fieldset>
							{{ csrf_field() }}
							<div class="form-group">
								<input class="form-control" placeholder="E-mail" name="email" type="email" autofocus="">
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="Password" name="password" type="password" value="">
							</div>
							<div class="checkbox">
								<label>
									<input name="remember" type="checkbox" value="Remember Me">Remember Me
								</label>
							</div>
							<button class="btn btn-primary">Login</button></fieldset>
					</form>
				</div>
			</div>
		</div><!-- /.col-->
	</div><!-- /.row -->	
	

	<script src="{{url('/')}}/ass-admin/js/jquery-1.11.1.min.js"></script>
	<script src="{{url('/')}}/ass-admin/js/bootstrap.min.js"></script>
</body>
</html>
