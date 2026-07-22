@extends('layouts.app')

@section('title', 'Checkout Confirm')

@section('content')
    <style>
        creditCardForm {
            max-width: 700px;
            background-color: #fff;
            margin: 100px auto;
            overflow: hidden;
            padding: 25px;
            color: #4c4e56;
        }
        .creditCardForm label {
            width: 100%;
            margin-bottom: 10px;
            text-align: left;
        }
        .creditCardForm .heading h1 {
            text-align: left;
            font-family: 'Open Sans', sans-serif;
            color: #4c4e56;
        }
        .creditCardForm .payment {
            float: left;
            font-size: 18px;
            padding: 10px 25px;
            margin-top: 20px;
            position: relative;
        }
        .creditCardForm .payment .form-group {
            float: left;
            margin-bottom: 15px;
        }
        .creditCardForm .payment .form-control {
            line-height: 40px;
            height: auto;
            padding: 0 16px;
        }
        .creditCardForm .owner {
            width: 53%;
            margin-right: 10px;
        }
        .creditCardForm .CVV {
            width: 30%;
        }
        .creditCardForm #card-number-field {
            width: 100%;
        }
        .creditCardForm #expiration-date {
            width: 70%;
        }
        .creditCardForm #credit_cards {
            width: 100%;
            margin-top: 25px;
            text-align: right;
        }
        .creditCardForm #pay-now {
            width: 100%;
            margin-top: 25px;
        }
        .creditCardForm .payment .btn {
            width: 100%;
            margin-top: 3px;
            font-size: 24px;
            background-color: #2ec4a5;
            color: white;
        }
        .creditCardForm .payment select {
            padding: 10px;
            margin-right: 15px;
        }
        .transparent {
            opacity: 0.2;
        }
        @media(max-width: 650px) {
            .creditCardForm .owner,
            .creditCardForm .CVV,
            .creditCardForm #expiration-date,
            .creditCardForm #credit_cards {
                width: 100%;
            }
            .creditCardForm #credit_cards {
                text-align: left;
            }
        }
    </style>

    @include('partials.style_file')

    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Checkout Confirm</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <section class="wt-section">
            <div class="container">
                <div class="heading">
                    <h1 style="text-align:center;">Confirm Purchase</h1>
                    <br>
                    @if(request()->has('failed'))
                        <p class="alert alert-danger">Transaction Failed, Unable to complete order.</p>
                    @endif
                </div>
                <div class="row text-center">
                    <div class="col-lg-7">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="30%">Item</th>
                                    <th width="30%">Type</th>
                                    <th width="5%">Price</th>
                                    <th width="20%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sub_total = 0;
                                    $discount_value = session()->get('discount_value', 0);
                                    $grand_total = 0;
                                ?>
                                @if(count($cartItems) > 0)
                                    @foreach($cartItems as $item)
                                        <?php $sub_total += $item['price'] * $item['qty']; ?>
                                        <tr>
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['type'] }}</td>
                                            <td>${{ number_format($item['price'], 2) }}</td>
                                            <td>${{ number_format($item['price'] * $item['qty'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="4"><p>Your cart is empty.....</p></td></tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" style="padding: 0px 15px!important;"></td>
                                    <td style="padding: 0px 15px!important;">Sub Total:</td>
                                    <td style="padding: 0px 15px!important;">
                                        <b>${{ number_format($sub_total, 2) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 0px!important; padding: 5px 15px!important;"></td>
                                    <td style="border: 0px!important;padding: 5px 15px!important;">Discount:</td>
                                    <td style="border: 0px!important;padding: 5px 15px!important;">
                                        <b>
                                            @if($discount_value > 0)
                                                ${{ number_format($discount_value, 2) }}
                                            @else
                                                0
                                            @endif
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 0px!important; padding:5px 15px!important;"></td>
                                    <td style="border: 0px!important; padding:5px 15px!important;">Grand Total:</td>
                                    <td style="border: 0px!important; padding:5px 15px!important;">
                                        <b>
                                            @php $grand_total = $sub_total - $discount_value; @endphp
                                            ${{ number_format($grand_total, 2) }}
                                        </b>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-lg-5">
                        <div class="creditCardForm">
                            <span id="messageShow"></span>
                            <form action="{{ url('home/chargeCreditCard') }}" method="post">
                                @csrf
                                <div class="form-container">
                                    <div class="col-md-12" style="padding-right: 0px; padding-left: 0px;">
                                        <input class="form-control" id="input-field" type="text" name="number" placeholder="Card Number" required>
                                    </div>
                                    <div class="col-md-7" style="float: left;padding-top: 20px; padding-left: 0px;">
                                        <input class="form-control" id="column-left" type="text" name="expiry" placeholder="MM / YY" required>
                                    </div>
                                    <div class="col-md-5" style="float: left;padding-top: 20px; padding-right: 0px;">
                                        <input class="form-control" id="column-right" type="text" name="cvc" placeholder="CCV" required>
                                    </div>
                                    <div class="card-wrapper" style="display: none;"></div>
                                    <input id="input-button" class="btn btn-primary insert_card" type="submit" value="Checkout" style="width: 100%;margin-top: 20px;" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('partials.before_footer')
    </main>

    @include('partials.script_file')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/121761/card.js'></script>
    <script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/121761/jquery.card.js'></script>
    <script type="text/javascript">
        $('form').card({
            container: '.card-wrapper',
            width: 280,
            formSelectors: {
                nameInput: 'input[name="first-name"], input[name="last-name"]'
            }
        });
    </script>
@endsection