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
                        @forelse($main_blogs as $result)
                            <div class="jmor_radio-post">
                                @if(!empty($result->image))
                                    <a href="{{ route('blog.detail', Str::after($result->link, 'blog/')) }}">
                                        <img class="rounded mb-lg-4 mb-3 img-fluid" src="{{ asset($result->image) }}" alt="card image">
                                    </a>
                                @endif
                                <h3><a href="{{ route('blog.detail', Str::after($result->link, 'blog/')) }}">{{ $result->name }}</a></h3>
                                <div class="meta font-lora mb-3">
                                    <a href="#">Post Date</a>
                                    <a href="#">{{ date('m-d-Y', strtotime($result->published ?? $result->created_at)) }}</a>
                                </div>
                                <p>{{ strip_tags(substr($result->description, 0, 300)) . '....' }}</p>
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
