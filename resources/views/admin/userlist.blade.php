@extends('admin.layout.master')
@section('content')	
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">List User</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">List User</h1>
			</div>
		</div><!--/.row-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
					<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.jqueryui.min.css"/>
					<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"/>
					<style type="text/css">
						table.dataTable.no-footer {
							     border-bottom: 0; 
							}
					</style>
					<div class="col-lg-12">
					<a class="btn btn-primary btn-fw" href="{{ url('/admin/user/create') }}" style="margin-bottom: 10px;font-size: 15px;margin-bottom: 10px">Buat User</a>
					  <table class="table table-bordered" id="table_id" style="font-size: 14px;">
					    <thead>
					      <tr>
					        <th>No.</th>
					        <th>Nama</th>
					        <th>Email</th>
					        <th>Created At</th>
					        <th>Action</th>
					      </tr>
					    </thead>
					    <tbody>
					      @foreach($list as $key => $val)
					      @if(!empty($val))
					      <tr>
					        <td data-th="No."></td>
					        <td data-th="Nama">
					          {{$val['name']}}
					        </td>
					        <td data-th="Email">
					          {{$val['email']}}
					        </td>
					        <td data-th="Created At">
					          {{$val['created_at']}}
					        </td>
					        <td data-th="Action" style="text-align: center;">
         					  <a href="{{url('/admin/user/edit/')}}/{{$val['id']}}" title="Edit"><i class='fa fa-pencil-square-o' style="font-size: 20px !important;"></i></a>
					          <a href="{{url('/admin/user/delete/')}}/{{$val['id']}}" onclick='return confirm("Apakah Anda Yakin Menghapus User ini?")' title="Delete"><i class='fa fa-trash-o icon-md' style="font-size: 20px !important;"></i></a>
					        </td>
					      </tr>
					      @endif
					      @endforeach
					    </tbody>
					  </table>
					</div>
					<script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
					<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" defer></script>
					<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.jqueryui.min.js" defer></script>
					<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js" defer></script>
					<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" defer></script>
					<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" defer></script>
					<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" defer></script>
					<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js" defer></script>
					<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js" defer></script>

					<script type="text/javascript">
					  $(document).ready(function() {
					    var t = $('#table_id').DataTable( {
					        "columnDefs": [ {
					            "searchable": false,
					            "orderable": false,
					            "targets": 0
					        } ],
					        "order": [[ 1, 'asc' ]]
					    } );
					    t.on( 'order.dt search.dt', function () {
					        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
					            cell.innerHTML = i+1;
					        } );
					    } ).draw();
					     // setTimeout(function(){ $( "p" ).removeClass( "myClass noClass" ).addClass( "yourClass" ); }, 500);
					  });
					</script>
					</div>
				</div>
			</div>
		</div><!--/.row-->
@endsection