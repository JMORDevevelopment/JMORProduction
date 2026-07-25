@extends('layouts.dashboard')

@section('content')

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js">
</script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js">
</script>

<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">

 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
		Order Table
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
 	     <div class="box">
            <div class="box-header">
              <h3 class="box-title">Order Data</h3>
	        </div>
            <!-- /.box-header -->
            <div class="box-body">
             	<table id="example" class="table table-bordered table-striped text-center" style="width:100%">
				 <thead>
					<tr>
						<th>Order Id</th>
						<th>Transaction Code</th>
						<th>Amout</th>
						<th>Status</th>
						<th>Action</th>
				   </tr>
				</thead>
				<tbody>
				@forelse($transactions as $trans_data)
					<tr>
						<td>{{ $trans_data->id + 10000 }}</td>
						<td>{{ $trans_data->transaction_id }}</td>
						<td>{{ $trans_data->amount }} </td>
						<td>
							@php($status = $trans_data->order?->status)
							@if($status == 1)
								<span class="label label-danger">Pending</span>
							@elseif($status == 2)
								<span class="label label-info">Completed</span>
							@endif
						</td>
        				 <td>
							 <div class="tools">
									<a href="{{ url('dashboard/order_invoice/'.$trans_data->order_id) }}" class="btn btn-primary" title="Invoice"><i class="fa fa-edit"></i></a>
							 </div>
						</td>
					</tr>
				@empty
				@endforelse
				</tbody>
			</table>
			</div>
            <!-- /.box-body -->
          </div>
    </section>
    <!-- /.content -->
  </div>

<script>
$(document).ready( function () {
    $('#table_id').DataTable();
} );

$('#table_id').dataTable( {
  "ordering": false
} );

$(document).ready(function() {
    $('#example').DataTable();
} );
</script>

@endsection
