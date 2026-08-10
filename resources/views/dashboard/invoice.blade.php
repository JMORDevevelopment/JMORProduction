@extends('layouts.dashboard')

@push('styles')
    <style>
        #print-modal {

            background: #FFF;
            position: absolute;
            left: 50%;
            margin: 0 0 0 -465px;
            padding: 0 68px;
            width: 794px;
            box-shadow: 0 0 20px #000;
            -moz-box-shadow: 0 0 20px #000;
            -webkit-box-shadow: 0 0 10px #000;
        }
    </style>
@endpush

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Invoice Order
        <small></small>
      </h1>

    </section>

    <!-- Main content -->

    <section class="invoice printableArea" id='DivIdToPrint'>
      <!-- title row -->

      <div class="row">
	    <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> INV-{{ $transaction->order_id }}
		 </h2>
        </div>
        <!-- /.col -->

      </div>

      <!-- info row -->
      <div class="row invoice-info">
			<div class="col-sm-4 invoice-col">
				  <b>Invoice # 	{{ $transaction->order_id }}</b><br>
				  <b>Date:</b> 	{{ \Illuminate\Support\Carbon::parse($transaction->published)->format('m-d-Y') }}<br>
				  <b>Customer Name:</b>  {{ trim(($customer->firstname ?? '').' '.($customer->lastname ?? '')) }}
			</div>
	</div>
	<div class="row invoice-info">
        <div class="col-lg-4 col-xs-6 col-sm-12 invoice-col  no-shadow" style="margin-top: 10px;">
			<div class="well well-sm">
				Billing Info
				@if($customer)
			      <address>
					<strong>{{ $customer->address }}</strong><br>
					<b>City</b>:   {{ $customer->city }}<br>
					<b>State</b>:  {{ $customer->state }}<br>
					<b>zip</b>:    {{ $customer->zip }}
				  </address>
				@endif
			</div>
        </div>
	   <div class="col-lg-4 col-xs-6 col-sm-12 invoice-col  no-shadow" style="margin-top: 10px;">
	   <div class="well well-sm">
		Other Info
		@foreach($otherInfo as $key => $value)
		 <p style="margin:0 0 0px;">  <b>{{ $key }}</b> : {{ $value }}</br></p>
		@endforeach
    </div>
 </div>
	    <div class="col-lg-4 col-xs-5 col-sm-12  invoice-col no-shadow" style="margin-top:10px; ">
		<div class=" well well-sm">
		Payment Info
	      <address>
            <strong>
				@if($transaction->checkout_type == 'Monthly') Transaction
				@elseif($transaction->checkout_type == 'Yearly') Subscription
				@endif
			#: {{ $transaction->transaction_id }}</strong><br>
			 <b>Date</b>: 	{{ \Illuminate\Support\Carbon::parse($transaction->published)->format('Y-m-d') }}<br>
			 <b>Status</b>:
				@if($order?->status == 1) Pending
				@elseif($order?->status == 2) Completed
				@endif
			<br>
			<b>Payment Method</b>: Credit Cards
		  </address>
		</div>
        </div>
	</div>
      <!-- /.row -->
	  <!-- Table row -->

      <div class="row">

        <div class="col-xs-12">

	      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">System Information</h3>
	    </div>
        <div class="box-body" style="overflow-x: auto;">
         <table class="table table-bordered">
			@foreach($systemInfo as $key => $value)
			<tr>
				@for ($x = 0; $x < count($value); $x++)
					@if($x % 2 == 0)
						<td style="background:#efefef;border-right:1px solid #000;border-left:1px solid #000;"><span style="font-weight:bold;text-transform:capitalize;">{{ str_replace('_', ' ', $key) }}</span> &nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;{{ $value[$x] }}</td>
					@else
						<td style="background:#f7f5f5;border-right:1px solid #000;border-left:1px solid #000;"><span style="font-weight:bold;text-transform:capitalize;">{{ str_replace('_', ' ', $key) }}</span> &nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;{{ $value[$x] }}</td>
					@endif
				@endfor
			</tr>
			@endforeach
		 </table>

        </div>
     </div>

	   </div>
        <!-- /.box-footer-->
      </div>

	 <div class="row">
		<div class="col-xs-12">
	   <div class="box">
	  <div class="box-header with-border">
          <h3 class="box-title">Order Detail</h3>


        </div>
        <div class="col-xs-12 table-responsive">

          <table class="table table-striped">
            <thead>
            <tr>
              <th>Item</th>
              <th>Type</th>
              <th>Price</th>
			  <th>Total</th>
            </tr>
            </thead>
            <tbody>
			@foreach($orderDetails as $detail)
            <tr>
			  <td>{{ $detail->item }}</td>
              <td>{{ $detail->type }}</td>
			  <td>${{ $detail->price }}</td>
              <td>${{ $detail->sub_total }}</td>
            </tr>
			@endforeach

            </tbody>
		<tfoot>
		<tr>
			<td colspan="3"></td>
			<td><b>Sub Total:</b></td>
			<td>
			<b>{{ !empty($order?->sub_total) ? '$'.$order->sub_total : '$'.$computedSubtotal }}</b>
			</td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td><b>Discount:</b></td>
			<td>
			<b>{{ !empty($order?->discount) ? '$'.$order->discount : '0' }}</b>
			</td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td><b>Grand Total:</b></td>
			<td><b>{{ !empty($order?->grand_total) ? '$'.$order->grand_total : '$'.$computedSubtotal }}</b></td>
		</tr>
		</tfoot>
		</table>
		</div>
		<!-- /.col --></div>
      <!-- /.row -->
	</div>
		<!-- /.col --></div>
      <!-- /.row -->



      <!-- /.row -->

      <!-- this row will not appear when printing -->

    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
  </div>
@endsection

@push('scripts')
    <script>
        function myFunction() {
            window.print();
        }
    </script>
@endpush
