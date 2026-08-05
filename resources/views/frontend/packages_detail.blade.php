@extends('layouts.app')

@section('title', 'Packages Detail')

@include('partials.style_file')

@push('styles')
    <style>
        .yearly_price {
            display: none;
        }
    </style>
@endpush

@section('content')
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
                        @include('partials.billing_toggle')
                    </div>
                </div>

                <div class="row justify-content-center">
                    @if(!empty($package_data))
                        @foreach($package_data as $pkg)
                            <?php
                                $server_price = DB::table('package_price')->where('package_id', $pkg->id)->where('from_qty', 1)->first();
                                $system_price = DB::table('system_price')->where('package_id', $pkg->id)->where('from_qty', 1)->first();
                                $pkg_description = html_entity_decode(html_entity_decode(strip_tags($pkg->description ?? ''), ENT_QUOTES), ENT_QUOTES);
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
                                            <p class="text-muted">{{ Str::limit($pkg_description, 150) }}</p>
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
                                            <p class="text-muted">{{ Str::limit($pkg_description, 150) }}</p>
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
@endsection

@include('partials.script_file')

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
        document.querySelectorAll('.billing-toggle').forEach(function (toggle) {
            toggle.addEventListener('click', function (e) {
                var btn = e.target.closest('.billing-toggle__btn');
                if (!btn) return;

                var isYearly = btn.getAttribute('data-billing') === 'yearly';

                toggle.querySelectorAll('.billing-toggle__btn').forEach(function (b) {
                    b.classList.toggle('active', b === btn);
                });

                var scope = toggle.closest('.wt-section') || document;

                scope.querySelectorAll('div.monthly_price').forEach(function (el) {
                    el.style.display = isYearly ? 'none' : 'block';
                });

                scope.querySelectorAll('div.yearly_price').forEach(function (el) {
                    el.style.display = isYearly ? 'block' : 'none';
                });
            });
        });
    </script>
@endpush
