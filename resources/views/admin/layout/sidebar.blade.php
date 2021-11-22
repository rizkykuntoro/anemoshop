	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<div class="profile-sidebar" style="text-align: center;">
			<div class="profile-userpic">
				<!-- <img src="http://placehold.it/50/30a5ff/fff" class="img-responsive" alt=""> -->
			</div>
			<div class="profile-usertitle" style="margin: 10px 0 0 30px;">
				<div class="profile-usertitle-name">{{$_SESSION['user']['name']}}</div>
				<div class="profile-usertitle-status"><span class="indicator label-success"></span>Online</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>
		<ul class="nav menu">
			<li class="{{Helpers::menuActiveChecker($_SERVER,url('/admin'))}}"><a href="{{url('/admin')}}"><em class="fa fa-dashboard"></em> Dashboard</a></li>
			<li class="{{Helpers::menuActiveChecker($_SERVER,url('/admin/webinar'))}}"><a href="{{url('/admin/webinar')}}"><em class="fa fa-calendar"></em> Webinar</a></li>
			<li class="{{Helpers::menuActiveChecker($_SERVER,url('/admin/daftar-peserta'))}}"><a href="{{url('/admin/daftar-peserta')}}"><em class="fa fa-calendar"></em> Daftar Peserta</a></li>
			<li class="{{Helpers::menuActiveChecker($_SERVER,url('/admin/user'))}}"><a href="{{url('/admin/user')}}"><em class="fa fa-user"></em> User Management</a></li>
			<li class="{{Helpers::menuActiveChecker($_SERVER,url('/admin/logout'))}}"><a href="{{url('/admin/logout')}}"><em class="fa fa-power-off"></em> Logout</a></li>
		</ul>
	</div><!--/.sidebar-->