@extends('layouts.app')

@section('title', 'Check Out')

@section('content')
    @include('partials.style_file')

    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Check Out</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <section class="wt-section">
            <div class="container">
                <div class="row text-center">
                    <div class="col-lg-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="30%">Item</th>
                                    <th width="30%">Type</th>
                                    <th width="15%">Price</th>
                                    <th width="20%">Subtotal</th>
                                    <th width="10%"></th>
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
                                        <?php
                                            $sub_total += $item['price'] * $item['qty'];
                                        ?>
                                        <tr id="{{ $loop->index }}">
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['type'] }}</td>
                                            <td>${{ number_format($item['price'], 2) }}</td>
                                            <td>${{ number_format($item['price'] * $item['qty'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="5"><p>Your cart is empty.....</p></td></tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" style="padding: 0px 15px!important;"></td>
                                    <td style="padding: 0px 15px!important;">Sub Total:</td>
                                    <td style="padding: 0px 15px!important;">
                                        <b>${{ number_format($sub_total, 2) }}</b>
                                    </td>
                                    <td style="padding: 0px 15px!important;"></td>
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
                                    <td style="border: 0px!important;padding: 5px 15px!important;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: 0px!important; padding:5px 15px!important;"></td>
                                    <td style="border: 0px!important; padding:5px 15px!important;">Grand Total:</td>
                                    <td style="border: 0px!important; padding:5px 15px!important;">
                                        <b>
                                            @php
                                                $grand_total = $sub_total - $discount_value;
                                            @endphp
                                            ${{ number_format($grand_total, 2) }}
                                        </b>
                                    </td>
                                    <td style="border: 0px!important; padding:5px 15px!important;"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="col-lg-6">
                        <h3 class="text-left">Submit Information</h3>
                        @if(session('error_login'))
                            <div class="alert alert-warning alert-dismissible">
                                <h4><i class="icon fa fa-warning"></i>Alert!</h4>{{ session('error_login')['global_error'] }}
                            </div>
                        @endif

                        <form action="{{ url('home/checkout_from_data') }}" class="well" method="post">
                            @csrf
                            <?php
                                // Get first cart item to determine package
                                $first_item = reset($cartItems);
                                if($first_item) {
                                    $pack_id = str_replace(['p','s'], '', $first_item['id']);
                                    $form = DB::table('checkout_meta')->where('package_id', $pack_id)->first();
                                    if($form) {
                                        $tab_datas = DB::table('checkout_form')->where('form_id', $form->id)->get()->toArray();
                                    } else {
                                        $tab_datas = [];
                                    }
                                } else {
                                    $tab_datas = [];
                                }
                            ?>
                            @if(!empty($tab_datas))
                                @foreach($tab_datas as $tab_data)
                                    <div class="control-group">
                                        <div class="form-group mb-4">
                                            @if($tab_data->types == 1)
                                                <input type="text" name="{{ $tab_data->name }}" {{ $tab_data->required == 1 ? 'required' : '' }} class="form-control form-control-lg" placeholder="{{ $tab_data->placeholder }}">
                                            @else
                                                <textarea name="{{ $tab_data->name }}" {{ $tab_data->required == 1 ? 'required' : '' }} class="form-control form-control-lg" placeholder="{{ $tab_data->placeholder }}"></textarea>
                                            @endif
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                @endforeach

                                @foreach($cartItems as $item)
                                    @for($p=0; $p<$item['qty']; $p++)
                                        <?php $x = $p+1; ?>
                                        <div class="control-group">
                                            <input type="hidden" name="system_number[]" value="{{ $x }}" class="form-control form-control-lg"/>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr><th colspan="2">System# {{ $x }}</th></tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $pack_id = str_replace(['p','s'], '', $item['id']);
                                                        $form = DB::table('checkout_meta')->where('package_id', $pack_id)->first();
                                                        if($form) {
                                                            $system_info = DB::table('system_information')->where('form_id', $form->id)->get()->toArray();
                                                        } else {
                                                            $system_info = [];
                                                        }
                                                    ?>
                                                    @if(!empty($system_info))
                                                        @foreach($system_info as $sys)
                                                            <tr style="padding: 0px 0px!important;">
                                                                <th style="padding: 15px 15px!important;">{{ $sys->s_label }}</th>
                                                                <td style="padding: 5px 5px !important;">
                                                                    @if($sys->s_types == 1)
                                                                        <input type="text" name="{{ $sys->s_name }}[]" class="form-control form-control-lg" placeholder="{{ $sys->s_placeholder }}">
                                                                    @else
                                                                        <textarea name="{{ $sys->s_name }}[]" class="form-control form-control-lg" placeholder="{{ $sys->s_placeholder }}"></textarea>
                                                                    @endif
                                                                    <p class="help-block"></p>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @endfor
                                @endforeach

                                <div class="text-right">
                                    <button class="btn btn-lg btn-primary py-3 mt-3 px-4 btn-pill" type="submit">Submit</button><br>
                                </div>
                            @else
                                <div class="alert alert-danger col-md-6">No submit form data</div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </section>
        @include('partials.before_footer')
    </main>

    @include('partials.script_file')
@endsection