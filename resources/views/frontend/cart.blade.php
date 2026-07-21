@extends('layouts.app')

@section('title', 'Cart')

@section('content')
    @include('partials.style_file')

    <main role="main">
        <section class="wt-section">
            <div class="container">
                <div class="row cart">
                    <h2>Cart</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="25%">Item</th>
                                <th width="25%">Type</th>
                                <th width="10%">Qty</th>
                                <th width="15%">Price</th>
                                <th width="15%">Total</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($cartItems) > 0)
                                @foreach($cartItems as $rowid => $item)
                                    <tr id="{{ $rowid }}">
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['type'] }}</td>
                                        <td>
                                            <input class="input form-control change_qty" qty-id="{{ $rowid }}" name="change_qty" style="max-width:80px;" type="number" value="{{ $item['qty'] }}">
                                        </td>
                                        <td>${{ number_format($item['price'], 2) }}</td>
                                        <td>
                                            <span id="total-{{ $rowid }}">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                                        </td>
                                        <td class="text-left">
                                            <a href="{{ url('cart/removeItem/'.$rowid) }}" data-did="{{ $rowid }}" onclick="return confirm('Are you sure to delete this record ?')" class="btn btn-danger delete_class"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="6"><p>Your cart is empty.....</p></td></tr>
                            @endif
                        </tbody>
                        @if(count($cartItems) > 0)
                            <?php
                                $sub_total = 0;
                                foreach($cartItems as $item) {
                                    $sub_total += $item['price'] * $item['qty'];
                                }
                                $discount_value = session()->get('discount_value', 0);
                                $grand_total = $sub_total - $discount_value;
                                $has_gift = false;
                                foreach($cartItems as $item) {
                                    if($item['type'] == 'Gift Card') {
                                        $has_gift = true;
                                        break;
                                    }
                                }
                            ?>
                            <tfoot>
                                <tr>
                                    <td colspan="3" style="padding: 0px 15px!important;"></td>
                                    <td style="padding: 0px 15px!important;">Sub Total:</td>
                                    <td style="padding: 0px 15px!important;">
                                        <b>${{ number_format($sub_total, 2) }}</b>
                                    </td>
                                    <td style="padding: 0px 15px!important;"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="border: 0px!important; padding: 5px 15px!important;"></td>
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
                                    <td style="border: 0px!important;padding: 5px 15px!important;"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="border: 0px!important; padding:5px 15px!important;"></td>
                                    <td style="border: 0px!important; padding:5px 15px!important;">Grand Total:</td>
                                    <td style="border: 0px!important; padding:5px 15px!important;">
                                        <b>${{ number_format($grand_total, 2) }}</b>
                                    </td>
                                    <td style="border: 0px!important; padding:5px 15px!important;"></td>
                                </tr>
                                @if(!session()->has('coupon_code'))
                                <tr id="coupon_tr">
                                    <td colspan="4" style="border: 0px!important;"></td>
                                    <td style="border: 0px!important;">
                                        <input class="input form-control" style="font-size:14px;" placeholder="Enter Gift Card" name="code" id="coupon_code" style="max-width:350px;" type="text">
                                        <span id="code_emp" class="text-danger"></span>
                                        <span id="code_wrong" class="text-danger"></span>
                                    </td>
                                    <td style="border: 0px!important;">
                                        <a href="javascript:void(0)" id="check_code" style="background:#fad6a5; color:white;" class="btn btn-primary btn-block">Apply</a>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="5" style="border: 0px!important;"></td>
                                    @if($has_gift)
                                        <td id="checkBtn" style="border: 0px!important;">
                                            <a href="{{ url('home/placeOrderGiftcard') }}" class="btn btn-success btn-block">Checkout</a>
                                        </td>
                                    @else
                                        <td id="checkBtn" style="border: 0px!important;">
                                            <a href="{{ url('home/placeOrder') }}" class="btn btn-success btn-block">Checkout</a>
                                        </td>
                                    @endif
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </section>
        @include('partials.before_footer')
    </main>

    @include('partials.script_file')

    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script>
        // Quantity update – keyboard (enter)
        $("input.change_qty").keyup(function(e) {
            if(e.keyCode == 13) {
                var rowid = $(this).attr('qty-id');
                var value = $(this).val();
                $.ajax({
                    url: "{{ url('cart/updateItemQty') }}/" + rowid + '/' + value,
                    type: "post",
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(data) {
                        if(data == 'ok') {
                            location.reload();
                        } else {
                            alert('Qty No Update');
                        }
                    },
                    error: function(data) {
                        alert('Error updating quantity');
                    }
                });
            }
        });

        // Quantity update – on change
        $('input.change_qty').on('change', function() {
            var rowid = $(this).attr('qty-id');
            var value = $(this).val();
            $.ajax({
                url: "{{ url('cart/updateItemQty') }}/" + rowid + '/' + value,
                type: "post",
                data: { _token: '{{ csrf_token() }}' },
                success: function(data) {
                    if(data == 'ok') {
                        location.reload();
                    } else {
                        alert('Qty No Update');
                    }
                },
                error: function(data) {
                    alert('Error updating quantity');
                }
            });
        });

        // Apply coupon
        $(document).ready(function() {
            $("#check_code").click(function() {
                var code = $("#coupon_code").val();
                if(code == '') {
                    $('#code_emp').html('Coupon Code field must be Enter');
                } else {
                    $('#code_emp').html('');
                    $.ajax({
                        url: "{{ url('cart/couponCode') }}/" + code,
                        type: "post",
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(data) {
                            if(data == 'ok') {
                                $('#coupon_tr').toggle();
                                location.reload();
                            } else {
                                $('#code_wrong').html('Invalid Gift Card');
                            }
                        },
                        error: function(data) {
                            alert('Error applying coupon');
                        }
                    });
                }
            });
        });
    </script>
@endsection