@extends('layouts.app')

@section('title', 'Package Detail')

@include('partials.style_file')

@push('styles')
    <style>
        .yearly_price {
            display: none;
        }
        .form-inline .form-control {
            display: inline-block;
            width: 70px;
            vertical-align: middle;
            margin-left: 25px;
        }
    </style>
@endpush

@section('content')
    @if(!empty($package_data))
        @foreach($package_data as $cx => $pkg)
            <?php
                $pkg_description_full = html_entity_decode(html_entity_decode($pkg['description'] ?? '', ENT_QUOTES), ENT_QUOTES);
                $pkg_description_short = html_entity_decode(html_entity_decode(strip_tags($pkg['description'] ?? ''), ENT_QUOTES), ENT_QUOTES);
            ?>
            <section class="wt-section bg-gray text-center inner-page-header">
                <div class="container">
                    <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                        <div class="col-md-7">
                            <div class="text-center">
                                <h1 class="display-sm-4 display-lg-3">{{ $pkg['name'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <main role="main">
                <section class="wt-section">
                    <div class="container">
                        <div class="row justify-content-between">
                            <div class="col-lg-8">
                                <div class="blog-post">
                                    <a href="#">
                                        @if(!empty($pkg['image']))
                                            <img class="rounded mb-lg-4 mb-3" src="{{ asset($pkg['image']) }}" height="300px" alt="card image">
                                        @endif
                                    </a>
                                    <div class="meta font-lora mb-3" hidden>
                                        <a href="#">Post Date</a>
                                        <a href="#"></a>
                                    </div>
                                </div>
                                <p>{!! $pkg_description_full !!}</p>
                            </div>
                        @break {{-- We only need the first package for the main content --}}
                        @endforeach
                    @endif

                    <div class="col-lg-4">
                        <div class="row justify-content-center mb-3">
                            <div class="text-center">
                                @include('partials.billing_toggle')
                            </div>
                        </div>
                        @if(!empty($package_data))
                            @foreach($package_data as $c => $pkg)
                                <?php
                                    $pkg_description_short = html_entity_decode(html_entity_decode(strip_tags($pkg['description'] ?? ''), ENT_QUOTES), ENT_QUOTES);
                                ?>
                                <div class="monthly_price" id="monthly_price">
                                    <form class="form-inline" action="{{ url('home/addToCartPackages') }}" method="post">
                                        @csrf
                                        <div class="card text-center mb-md-0 mb-3">
                                            <div class="card-body">
                                                <div class="pricing-header">
                                                    <h5 class="font-weight-normal mb-3">{{ $pkg['name'] }}</h5>
                                                    <?php
                                                        $server_price = DB::table('package_price')
                                                            ->where('package_id', $pkg['id'])
                                                            ->where('from_qty', 1)
                                                            ->first();
                                                        $system_price = DB::table('system_price')
                                                            ->where('package_id', $pkg['id'])
                                                            ->where('from_qty', 1)
                                                            ->first();
                                                        $sever_format_num = $server_price ? number_format($server_price->pack_price, 2, '.', '') : '0.00';
                                                        $system_format_num = $system_price ? number_format($system_price->system_price, 2, '.', '') : '0.00';
                                                    ?>
                                                    <p class="text-muted">{{ Str::limit($pkg_description_short, 150) }}</p>
                                                    @if($server_price)
                                                        <div class="form-group">
                                                            <label for="server_qty">Server Qty(<span>${{ $sever_format_num }}</span>)</label>
                                                            <input type="number" class="form-control" min="1" size="2" name="server_qty" value="1">
                                                        </div>
                                                    @else
                                                        <input type="hidden" name="server_qty" value="0">
                                                    @endif
                                                    <div class="form-group">
                                                        <label for="system_qty">Qty WKSTNS(<span>${{ $system_format_num }}</span>) </label>
                                                        <input type="number" class="form-control" style="margin-top:5px; margin-left:20px;" min="1" size="2" name="system_qty" value="1">
                                                    </div>
                                                    <button type="submit" class="btn btn-pill btn-outline-primary mt-3">Buy Now</button>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" class="monthly_price" name="package_type" value="Monthly">
                                        <input type="hidden" name="package_id" value="{{ $pkg['id'] }}">
                                    </form>
                                </div>

                                <div class="yearly_price" id="yearly_price">
                                    <form class="form-inline" action="{{ url('home/addToCartPackages') }}" method="post">
                                        @csrf
                                        <div class="card text-center mb-md-0 mb-3">
                                            <div class="card-body">
                                                <div class="pricing-header">
                                                    <h5 class="font-weight-normal mb-3">{{ $pkg['name'] }}</h5>
                                                    <?php
                                                        $server_price = DB::table('package_price')
                                                            ->where('package_id', $pkg['id'])
                                                            ->where('from_qty', 1)
                                                            ->first();
                                                        $system_price = DB::table('system_price')
                                                            ->where('package_id', $pkg['id'])
                                                            ->where('from_qty', 1)
                                                            ->first();
                                                        $discount = $pkg['discount'];
                                                        if ($server_price) {
                                                            $discount_server_price = $server_price->pack_price - ($server_price->pack_price * $discount / 100);
                                                            $sever_format_number = number_format($discount_server_price, 2, '.', '');
                                                        } else {
                                                            $sever_format_number = '0.00';
                                                        }
                                                        if ($system_price) {
                                                            $discount_system_price = $system_price->system_price - ($system_price->system_price * $discount / 100);
                                                            $system_format_number = number_format($discount_system_price, 2, '.', '');
                                                        } else {
                                                            $system_format_number = '0.00';
                                                        }
                                                    ?>
                                                    <p class="text-muted">{{ Str::limit($pkg_description_short, 150) }}</p>
                                                    @if($server_price)
                                                        <div class="form-group">
                                                            <label for="server_qty_yearly">Server Qty(<span>${{ $sever_format_number }}</span>)</label>
                                                            <input type="number" class="form-control yearly_price" min="1" size="2" name="server_qty" value="1">
                                                        </div>
                                                    @else
                                                        <input type="hidden" class="form-control yearly_price" name="server_qty" value="0">
                                                    @endif
                                                    <div class="form-group">
                                                        <label for="system_qty_yearly">Qty WKSTNS(<span>${{ $system_format_number }}</span>) </label>
                                                        <input type="number" class="form-control yearly_price" style="margin-top:5px; margin-left:20px;" min="1" size="2" name="system_qty" value="1">
                                                    </div>
                                                    <button type="submit" class="btn btn-pill btn-outline-primary mt-3">Buy Now</button>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" class="yearly_price" name="package_type" value="Yearly">
                                        <input type="hidden" name="package_id" value="{{ $pkg['id'] }}">
                                    </form>
                                </div>
                            @endforeach
                        @else
                            <p>Package(s) not found...</p>
                        @endif
                    </div>
                </div>
            </section>
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
