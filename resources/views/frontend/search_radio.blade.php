@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Search Results</h1>
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
                        @forelse($main_blogs as $show)
                            <div class="jmor_radio-post">
                                @if(!empty($show->image))
                                    <img style="width: 100%;" class="rounded mb-lg-4 mb-3 img-fluid" src="{{ asset($show->image) }}" alt="card image">
                                @endif
                                <h3><a href="{{ route('jmor-shows.detail', $show->link) }}">{{ $show->name }}</a></h3>
                                <div class="meta font-lora mb-3">
                                    Radio show date
                                    <span style="color:#007bff;">{{ date('m-d-Y', strtotime($show->show_date)) }}</span>
                                </div>
                                <p>{{ strip_tags(substr($show->description, 0, 300)) . '....' }}</p>
                            </div>
                        @empty
                            <p>No results found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
        @include('partials.before_footer')
    </main>
@endsection

@include('partials.script_file')
