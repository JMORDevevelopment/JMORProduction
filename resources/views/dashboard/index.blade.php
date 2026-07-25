@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-lg-4 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-blue">
					<div class="inner">
						<h4>Totol Order</h4>
						<p style="font-size:24px;text-align:right;  font-weight: bold;">
							{{ $totalOrders }}
						</p>
					</div>
					<div class="icon">
						<i class="ion ion-pie-graph"></i>
					</div>
					<a href="{{ url('dashboard/orders') }}"
							class="small-box-footer">More
						<i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>      <!-- ./col -->
			<div class="col-lg-4 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-green">
					<div class="inner">
						<h4>Processing Orders</h4>
						<p style="font-size:24px;text-align:right;  font-weight: bold;">
							{{ $processingOrders }}
						</p>
					</div>
					<div class="icon">
						<i class="ion ion-stats-bars"></i>

					</div>
					<a href="{{ url('dashboard/orders') }}" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

			<div class="col-lg-4 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<h4>Completed Orders</h4>
						<p style="font-size:24px;text-align:right;  font-weight: bold;">
							{{ $completedOrders }}
						</p>
					</div>
					<div class="icon">
						<i class="ion ion-stats-bars"></i>
					</div>
					<a href="{{ url('dashboard/orders') }}" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div></div><!-- /.row -->
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Recent Orders</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered">
							<tr>
								<th>Order Id</th>
								<th>Transaction Code</th>
								<th>Date</th>
								<th>Amount</th>
								<th>Status</th>
								<th>View Detail</th>
							</tr>
						</thead>
						<tbody>
							@foreach($recentTransactions as $nation)
							<tr>   <td>{{ $nation->order_id + 10000 }}</td>
								<td>{{ $nation->transaction_id }}</td>
								<td>{{ \Illuminate\Support\Carbon::parse($nation->published)->format('m-d-Y') }}</td>
								<td>{{ $nation->amount }}</td>
								<td>
									@php($status = $nation->order?->status)
									@if($status == 1)
										<span class="label label-danger">Pending</span>
									@elseif($status == 2)
										<span class="label label-info">Completed</span>
									@endif
								</td>
								<td>
									<div class="tools">
										<a href="{{ url('dashboard/order_invoice/'.$nation->order_id) }}" class="btn btn-primary" ><i class="fa fa-edit"></i></a>
									</div>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- /.content -->
</div>
@endsection
