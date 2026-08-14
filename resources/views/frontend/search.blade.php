@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">Search Result</h1>
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
                        @if(!empty($main_blogs))
                            @foreach($main_blogs as $main_blog)
                                <div class="blog-post">
                                    @if(!empty($main_blog->image))
                                        <a href="{{ url($main_blog->link) }}"><img class="rounded mb-lg-4 mb-3" src="{{ asset($main_blog->image) }}" height="350px" alt="card image"></a>
                                    @endif
                                    <h3><a href="{{ url($main_blog->link) }}">{{ $main_blog->name }}</a></h3>
                                    <p>{{ strip_tags(substr($main_blog->description, 0, 300)) . '....' }}</p>
                                </div>
                            @endforeach
                        @else
                            <h3 class="text-danger">Nothing Record Found</h3>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <div class="blog-widget mb-4">
                            <h6 class="mb-4">Latest Post</h6>
                            @foreach($latestPosts as $latestPost)
                                <div class="card border-0 bg-light mb-1">
                                    <div class="card-body row align-items-center">
                                        <div class="col-4">
                                            <a href="{{ route('blog.detail', Str::after($latestPost->link, 'blog/')) }}"><img class="card-img" src="{{ asset($latestPost->image) }}" alt="card image"></a>
                                        </div>
                                        <div class="col-8">
                                            <h6 class="my-2 font-size-14"><a href="{{ route('blog.detail', Str::after($latestPost->link, 'blog/')) }}">{{ $latestPost->name }}</a></h6>
                                            <span class="font-size-14 text-muted">{{ date('m-d-Y', strtotime($latestPost->published)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('partials.before_footer')
    </main>
@endsection

@include('partials.script_file')
