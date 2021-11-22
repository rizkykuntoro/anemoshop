@extends('admin.layout.master')
@section('content')	
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Webinar List</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Webinar List</h1>
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
					<a class="btn btn-primary btn-fw" href="{{ url('/admin/webinar/create') }}" style="margin-bottom: 10px;font-size: 15px;margin-bottom: 10px">Buat Webinar</a>
					  <table class="table table-bordered" id="table_id" style="font-size: 14px;">
					    <thead>
					      <tr>
					        <th>No.</th>
					        <th>Nama Webinar</th>
					        <th>Jenis</th>
					        <th>Total SKPR</th>
					        <th>Tanggal Mulai</th>
					        <th>Tanggal Selesai</th>
					        <th>Tempat</th>
					        <th>Panitia</th>
					        <th>Kontak</th>
					        <th>Biaya</th>
					        <th>Action</th>
					      </tr>
					    </thead>
					    <tbody>
					      @foreach($data as $key => $val)
					      @if(!empty($val))
					      <tr>
					        <td data-th="No."></td>
					        <td data-th="Nama Webinar">
					          {{$val['nama']}}
					        </td>
					        <td data-th="Jenis">
					          {{$val['jenis']}}
					        </td>
					        <td data-th="Total SKP">
					          {{$val['total_skp']}}
					        </td>
					        <td data-th="Tanggal Mulai">
					          {{$val['tanggal_mulai']}}
					        </td>
					        <td data-th="Tanggal Selesai">
					          {{$val['tanggal_selesai']}}
					        </td>
					        <td data-th="Tempat">
					          {{$val['tempat']}}
					        </td>
					        <td data-th="Panitia">
					          {{$val['panitia']}}
					        </td>
					        <td data-th="Kontak">
					          {{$val['kontak']}}
					        </td>
					        <td data-th="Biaya">
					          {{$val['biaya']}}
					        </td>
					        <td data-th="Action" style="text-align: center;">
         					  <a href="{{url('/admin/webinar/edit/')}}/{{$val['id']}}" title="Edit"><i class='fa fa-pencil-square-o' style="font-size: 20px !important;"></i></a>
					          <a href="{{url('/admin/webinar/delete/')}}/{{$val['id']}}" onclick='return confirm("Apakah Anda Yakin Menghapus Webinar ini?")' title="Delete"><i class='fa fa-trash-o icon-md' style="font-size: 20px !important;"></i></a>
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
					        "order": [[ 1, 'asc' ]],
					        "scrollX": true,
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