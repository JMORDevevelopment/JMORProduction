@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
    <!-- Event snippet for Package Signup conversion page -->
    <script>
        gtag('event', 'conversion', {'send_to': 'AW-1071011104/dtidCPbW-dQBEKCq2f4D'});
    </script>

    @include('partials.style_file')

    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Order Confirmed</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <section class="wt-section">
            <div class="container">
                <div class="row text-center">
                    <div class="success-page">
                        <img src="http://share.ashiknesin.com/green-checkmark.png" class="center" alt="" />
                        <h2>Payment Successful !</h2>
                        <p>We are delighted to inform you that we received your payments</p>
                    </div>
                </div>
            </div>
        </section>
        @include('partials.before_footer')
    </main>

    @include('partials.script_file')
@endsection