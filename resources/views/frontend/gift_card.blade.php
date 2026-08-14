@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Gift Card</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <div class="wt-section bg-light">
            <div class="container">
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <div class="row justify-content-center">
                    @if($pakage_data->isNotEmpty())
                        @foreach($pakage_data as $giftCard)
                            <div class="col-md-4">
                                <form action="{{ route('add.cart.gift') }}" method="post">
                                    @csrf
                                    <div class="card text-center mb-md-0 mb-3">
                                        <div class="card-body py-5">
                                            <div class="pricing-header mb-5">
                                                <h5 class="font-weight-normal mb-3">{{ $giftCard->name }}</h5>
                                                <h1>${{ $giftCard->price }}</h1>
                                                <p class="text-muted">{{ strip_tags(substr($giftCard->description, 0, 150)) }}</p>
                                            </div>
                                            <button type="submit" class="btn btn-pill btn-outline-primary mt-3">Buy Now</button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="gift_id" value="{{ $giftCard->id }}">
                                </form>
                            </div>
                        @endforeach
                    @else
                        <p>Gift(s) not found...</p>
                    @endif
                </div>
            </div>
        </div>
        @include('partials.before_footer')
    </main>
@endsection

@include('partials.script_file')
