<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lumino - Dashboard</title>
	<link href="{{url('/')}}/ass-admin/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{url('/')}}/ass-admin/css/font-awesome.min.css" rel="stylesheet">
	<link href="{{url('/')}}/ass-admin/css/datepicker3.css" rel="stylesheet">
	<link href="{{url('/')}}/ass-admin/css/styles.css" rel="stylesheet">
    <link rel="icon" type="ico" href="{{url('/')}}/favicon.ico"/>
	
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
	@include('admin.layout.header')
	@include('admin.layout.sidebar')
    @yield('content')
	
	<script src="{{url('/')}}/ass-admin/js/jquery-1.11.1.min.js"></script>
	<script src="{{url('/')}}/ass-admin/js/bootstrap.min.js"></script>
	<script src="{{url('/')}}/ass-admin/js/chart.min.js"></script>
	<script src="{{url('/')}}/ass-admin/js/chart-data.js"></script>
	<script src="{{url('/')}}/ass-admin/js/easypiechart.js"></script>
	<script src="{{url('/')}}/ass-admin/js/easypiechart-data.js"></script>
	<script src="{{url('/')}}/ass-admin/js/bootstrap-datepicker.js"></script>
	<script src="{{url('/')}}/ass-admin/js/custom.js"></script>
		
	  @if(isset($_SESSION['notif']) && $_SESSION['notif'] =='true')         
	      <script type="text/javascript">
	        $( document ).ready(function() {
	          Swal.fire(
	            "{{ $_SESSION['title'] }}",
	            "{{ $_SESSION['message'] }}",
	            "{{ $_SESSION['status'] }}",
	          )
	        });
	      </script>       
	      @php
	        $_SESSION['notif']='false'
	      @endphp   
	  @else
	              
	  @endif
</body>
</html>