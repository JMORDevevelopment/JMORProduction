@extends('layouts.app')

@section('title', 'Packages Detail')

@section('content')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #2196F3;
        }
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
        .yearly_price {
            display: none;
        }
    </style>

    @include('partials.style_file')

    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3" style="text-transform: capitalize;">{{ $top_title ?? '' }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <div class="wt-section bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="text-center">
                        <span class="beforeinput text-success">Monthly</span>
                        <label class="switch">
                            <input id="toggle-event" type="checkbox" data-toggle="toggle">
                            <span class="slider round"></span>
                        </label>
                        <span class="afterinput">Yearly</span>
                    </div>
                </div>

                <div class="row justify-content-center">
                    @if(!empty($package_data))
                        @foreach($package_data as $pkg)
                            <?php
                                $server_price = DB::table('package_price')->where('package_id', $pkg->id)->where('from_qty', 1)->first();
                                $system_price = DB::table('system_price')->where('package_id', $pkg->id)->where('from_qty', 1)->first();
                            ?>
                            <div class="col-md-4 monthly_price" id="monthly_price">
                                <div class="card text-center mb-md-0 mb-3">
                                    <div class="card-body">
                                        <div class="pricing-header">
                                            <h5 class="font-weight-normal mb-3">{{ $pkg->name }}</h5>
                                            @if($server_price)
                                                <span style="font-size:22px; color:#1b1e24;">Server Price</span>
                                                <h3>${{ number_format($server_price->pack_price, 2) }} <small style="font-size:16px;">Each</small></h3>
                                            @endif
                                            <span style="font-size:22px; color:#1b1e24;">WKSTNS Price</span>
                                            <h3>${{ number_format($system_price->system_price ?? 0, 2) }} <small style="font-size:16px;">Each</small></h3>
                                            <p class="text-muted">{{ Str::limit(strip_tags($pkg->description), 150) }}</p>
                                            <a href="{{ url('home/single_package/'.$pkg->id) }}" class="btn btn-pill btn-outline-primary mt-3">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 yearly_price" id="yearly_price">
                                <div class="card text-center mb-md-0 mb-3">
                                    <div class="card-body">
                                        <div class="pricing-header">
                                            <h5 class="font-weight-normal mb-3">{{ $pkg->name }}</h5>
                                            @if($server_price)
                                                <?php
                                                    $discount_server = $server_price->pack_price * ($pkg->discount / 100);
                                                    $total_server = $server_price->pack_price - $discount_server;
                                                ?>
                                                <span style="font-size:22px; color:#1b1e24;">Server Price</span>
                                                <h3>${{ number_format($total_server, 2) }} <small style="font-size:16px;">Each</small></h3>
                                            @endif
                                            <?php
                                                if($system_price) {
                                                    $discount_system = $system_price->system_price * ($pkg->discount / 100);
                                                    $total_system = $system_price->system_price - $discount_system;
                                                } else {
                                                    $total_system = 0;
                                                }
                                            ?>
                                            <span style="font-size:22px; color:#1b1e24;">WKSTNS Price</span>
                                            <h3>${{ number_format($total_system, 2) }} <small style="font-size:16px;">Each</small></h3>
                                            <p class="text-muted">{{ Str::limit(strip_tags($pkg->description), 150) }}</p>
                                            <a href="{{ url('home/single_package/'.$pkg->id) }}" class="btn btn-pill btn-outline-primary mt-3">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>Package(s) not found...</p>
                    @endif
                </div>
            </div>
        </div>
        @include('partials.before_footer')
    </main>

    @include('partials.script_file')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $(".monthly_price").show();
            $(".yearly_price").hide();
            $('input[type="checkbox"]').click(function() {
                if($(this).prop("checked") == true) {
                    $(".monthly_price").hide();
                    $(".yearly_price").show();
                } else {
                    $(".monthly_price").show();
                    $(".yearly_price").hide();
                }
            });
        });
    </script>
@endsection