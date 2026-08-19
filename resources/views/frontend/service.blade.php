@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Our Services</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <section class="bg-light wt-section">
            <div class="container">
                <div class="row">
                    @foreach($services as $service)
                        <div class="col-lg-3 mb-4 mb-lg-0">
                            <div class="display-3 text-primary mb-2">
                                <div class="mb-3" style="height:150px;">
                                    <img src="{{ asset($service->image) }}" alt=""/>
                                </div>
                            </div>
                            <a href="{{ route('service.detail', $service->link) }}" style="color:#0053a0; text-align:center;">
                                <h4 class="h5">{{ $service->title }}</h4>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @include('partials.before_footer')
    </main>
@endsection

@include('partials.script_file')
