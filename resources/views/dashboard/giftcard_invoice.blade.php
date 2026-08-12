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
                    <b>Invoice # {{ $transaction->order_id }}</b><br>
                    <b>Date:</b> {{ \Illuminate\Support\Carbon::parse($transaction->published)->format('Y-m-d') }}<br>
                    <b>Customer Name:</b> {{ trim(($customer->firstname ?? '').' '.($customer->lastname ?? '')) }}
                </div>
            </div>

            <div class="row invoice-info">
                <div class="col-lg-4 col-xs-6 col-sm-12 invoice-col no-shadow" style="margin-top: 10px;">
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

                <div class="col-lg-6 col-xs-6 col-sm-12 invoice-col pull-right no-shadow" style="margin-top: 10px;">
                    <div class="well well-sm">
                        Coupon Number
                        @if($couponCheckout)
                            <img src="{{ asset('uploads/gift_card/gift_card-'.$couponCheckout->id.'.png') }}" width="100%">
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 col-xs-5 col-sm-12 invoice-col no-shadow" style="margin-top:10px; ">
                    <div class="well well-sm">
                        Payment Info
                        <address>
                            <strong>Transaction #: {{ $transaction->transaction_id }}</strong><br>
                            <b>Date</b>: {{ \Illuminate\Support\Carbon::parse($transaction->published)->format('Y-m-d') }}<br>
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
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
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
