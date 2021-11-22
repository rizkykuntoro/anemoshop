@extends('admin.layout.master')
@section('content')	
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Daftar Peserta</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Daftar Peserta</h1>
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
					<div class="col-lg-12">
						<a class="btn btn-primary btn-fw" href="{{url('/admin/create-peserta')}}" style="float: right; margin-bottom: 10px;font-size: 15px;margin-bottom: 10px">Daftarkan Peserta</a>
						<a class="btn btn-success btn-fw" href="#" id="buka" onclick="$('#Tutup').show();$('#buka').hide();$('#filter').show(500);return false;" style="float: left;margin-bottom: 10px;font-size: 15px;margin-bottom: 10px">Filter</a>
						<a class="btn btn-success btn-fw" href="#" id="Tutup" onclick="$('#Tutup').hide();$('#buka').show();$('#filter').hide(500);return false;" style="display: none;float: left;margin-bottom: 10px;font-size: 15px;margin-bottom: 10px">Tutup Filter</a>
					</div>
					<form method="get" class="col-lg-12" id="filter" style="display: none;">
						<div class="form-group" style="width: 30%;">
							<label>Order ID</label>
							<input class="form-control" name="order_id" id="order_id" placeholder="ORDER ID" style="height: 30px;" >
						</div>
						<div class="form-group" style="width: 30%;">
							<label>Nama</label>
							<input class="form-control" name="nama" id="nama" placeholder="Nama Peserta" style="height: 30px;" >
						</div>
						<div class="form-group" style="width: 30%;">
							<label>ID Webinar</label>
							<select class="form-control" name="id_webinar" id="id_webinar" aria-label="Default select example">
							  <option selected>Semua Webinar</option>
							  <option value="1">Webinar 01</option>
							  <option value="2">Webinar 02</option>
							  <option value="3">Webinar 03</option>
							  <option value="4">Webinar 04</option>
							  <option value="5">Webinar 05</option>
							  <option value="6">Webinar 06</option>
							</select>
						</div>
						<div class="form-group" style="width: 30%;">
							<label>NIR</label>
							<input class="form-control" name="nir" id="nir" placeholder="NIR" style="height: 30px;" >
						</div>
						<div class="form-group" style="width: 30%;">
							<label>Flag Status</label>
							<select class="form-control" name="status" id="status" aria-label="Default select example">
							  <option selected>Semua Status</option>
							  <option value="Gagal">Gagal</option>
							  <option value="Pending">Pending</option>
							  <option value="Lunas">Lunas</option>
							  <option value="Expired">Expired</option>
							</select>
						</div>
						<div class="form-group" style="width: 30%;">
							<label>Tipe Bayar</label>
							<select class="form-control" name="tipe_bayar" id="tipe_bayar" aria-label="Default select example">
							  <option selected>Semua Tipe</option>
							  <option value="mandiriBILL">mandiriBILL</option>
							  <!-- <option value="bcaVA">bcaVA</option>
							  <option value="bniVA">bniVA</option>
							  <option value="briVA">briVA</option> -->
							  <option value="permataVA">permataVA</option>
							</select>
						</div>
						<button type="submit" class="btn btn-primary" style="margin-bottom: 10px;">Submit</button>
					</form>
					  <table class="table table-bordered" id="table_id" style="font-size: 14px;">
					    <thead>
					      <tr>
					        <th>No.</th>
					        <th>Order ID</th>
					        <th>Nama</th>
					        <th>Email</th>
					        <th>NIR</th>
					        <th>No HP</th>
					        <th>No WA</th>
					        <th>Instansi</th>
					        <th>Pengcab</th>
					        <th>ID Webinar</th>
					        <th>Type Bayar</th>
					        <th>Flag Status</th>
					        <th>Action</th>
					      </tr>
					    </thead>
					    <tbody>
					      @foreach($data as $key => $val)
					      @if(!empty($val))
					      <tr>
					        <td data-th="No."></td>
					        <td data-th="Order ID">
					          {{$val['kode_pembayaran']}}
					        </td>
					        <td data-th="Nama">
					          {{$val['nama']}}
					        </td>
					        <td data-th="Email">
					          {{$val['email']}}
					        </td>
					        <td data-th="NIR">
					          {{$val['nir']}}
					        </td>
					        <td data-th="No HP">
					          {{$val['nohp']}}
					        </td>
					        <td data-th="No WA">
					          {{$val['no_wa']}}
					        </td>
					        <td data-th="Instansi">
					          {{$val['nama_instansi_kerja']}}
					        </td>
					        <td data-th="Pengcab">
					          {{$val['nama_pengcap']}}
					        </td>
					        <td data-th="ID Webinar">
					          {{$val['id_webinar']}}
					        </td>
					        <td data-th="Type Bayar">
					          {{$val['type_bayar']}}
					        </td>
					        <td data-th="Flag Status">
					          {{$val['flag_status']}}
					        </td>
					        <td data-th="Action" style="text-align: center;">
         					  
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
					        dom: 'Bfrtip',
					        buttons: [
					            { extend: 'excel', text: 'Export Data to Excel',title: 'Report_Peserta_'+'{{ date("YmdHis") }}' }
					        ],
					        "columnDefs": [ {
					            "searchable": false,
					            "orderable": false,
					            "targets": 0
					        } ],
					        "order": [[ 1, 'asc' ]],
					        "scrollX": true,
					        "bFilter":false,
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