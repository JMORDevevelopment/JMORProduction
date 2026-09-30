@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">JMOR Tech Talk Show Guest Application</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="wt-section">
        <main role="main" style="width: 60%; margin:auto; background: #dee2e6; padding: 12px; border-radius: 6px;">
            <h4 style="background-color: #0053a0; color: white; padding-left: 12px; border-radius: 5px;">ORDER SUMMARY
            </h4>

            @if (isset($added))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ $added }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (isset($paymentFailed))
                <div class="alert alert-danger" role="alert">
                    {{ $paymentFailed }}
                </div>
            @endif

            <h3 align="center">Price : {{ $price }}</h3>
            <form action="{{ route('talk-show.charge') }}" method="post">
                @csrf

                <div class="control-group">
                    <div class="form-group mb-4{{ isset($error['name']) ? ' has-error' : '' }}">
                        <input type="text" class="form-control form-control-lg" name="name"
                            placeholder="Full Name" value="{{ $name }}" required />
                        @if (isset($error['name']))
                            <span class="help-block text-danger">{{ $error['name'] }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group mb-4{{ isset($error['email']) ? ' has-error' : '' }}">
                    <div class="controls">
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Email"
                            id="email" value="{{ $email }}" required />
                        @if (isset($error['email']))
                            <span class="help-block text-danger">{{ $error['email'] }}</span>
                        @endif
                    </div>
                </div>

                <h4 align="center">Marketing Services</h4>
                <div class="form-group mb-4">
                    <div class="controls">
                        @foreach ($services as $ss)
                            <div class="form-control" style="display: table; margin-bottom: 12px;">
                                <h4 style="text-align: center; display: table-caption;">{{ $ss->name }}</h4>
                                <div style="float: left;">
                                    <label
                                        style="background-color: #0053a0; font-size: 11px; color: white; padding: 4px; border-radius: 6px;">Product
                                        code: {{ $ss->product_code }}</label>
                                    <br>
                                    <label
                                        style="background-color: #989c9f; font-size: 11px; color: black; border-radius: 6px; padding: 4px;">Description</label>
                                    <p style="font: message-box;">{{ $ss->description }}</p>
                                </div>
                                <div style="display: table-cell; vertical-align: middle; text-align: end;">
                                    <p style="display: inline-table;">Price: {{ $ss->price }}</p>
                                    <input type="checkbox" name="services[]" value="{{ $ss->product_code }}">
                                </div>
                            </div>
                            @if ($ss->question != '')
                                <p>Q: {{ $ss->question }}</p>
                                <textarea class="form-control" name="ans[]" placeholder="Your Answer... "></textarea>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="form-container">
                    <div class="col-md-12" style="padding-right: 0px; padding-left: 0px;">
                        <input class="form-control" id="input-field" type="text" name="number"
                            placeholder="Card Number" value="{{ $number }}" required />
                        @if (isset($error['number']))
                            <span class="help-block text-danger">{{ $error['number'] }}</span>
                        @endif
                    </div>
                    <div class="col-md-7" style="float: left; padding-top: 20px; padding-left: 0px;">
                        <input class="form-control" id="column-left" type="text" name="expiry" placeholder="MM / YY"
                            value="{{ $expiry }}" required />
                        @if (isset($error['expiry']))
                            <span class="help-block text-danger">{{ $error['expiry'] }}</span>
                        @endif
                    </div>
                    <div class="col-md-5" style="float: left; padding-top: 20px; padding-right: 0px;">
                        <input class="form-control" id="column-right" type="text" name="cvc" placeholder="CCV"
                            value="{{ $cvc }}" required />
                        @if (isset($error['cvc']))
                            <span class="help-block text-danger">{{ $error['cvc'] }}</span>
                        @endif
                    </div>
                    <div class="card-wrapper" style="display: none;"></div>

                    <input id="input-button" class="btn btn-primary insert_card" type="submit" value="Checkout"
                        style="width: 100%; margin-top: 20px;" />
                </div>
            </form>
        </main>
    </section>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/121761/card.js"></script>
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/121761/jquery.card.js"></script>
    <script type="text/javascript">
        $('form').card({
            container: '.card-wrapper',
            width: 280,
            formSelectors: {
                nameInput: 'input[name="first-name"], input[name="last-name"]'
            }
        });
    </script>
@endpush

@include('partials.script_file')
