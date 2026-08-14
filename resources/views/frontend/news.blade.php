@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">News</h1>
                        <p class="sub-text">Aspernatur at enim excepturi facere in reiciendis</p>
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
                        @foreach($news as $new)
                            <div class="new-post">
                                <a href="{{ route('news.detail', $new['link']) }}">
                                    <img class="rounded mb-lg-4 mb-3 img-fluid" src="{{ asset($new['image']) }}" alt="card image">
                                </a>
                                <h3><a href="{{ route('news.detail', $new['link']) }}">{{ $new['name'] }}</a></h3>
                                <div class="meta font-lora mb-3">
                                    <a href="#">Post Date</a>
                                    <a href="#">{{ date('m-d-Y', strtotime($new['published'])) }}</a>
                                </div>
                                <p>{!! $new['description'] !!}</p>
                            </div>
                        @endforeach
                        <div class="row justify-content-between align-items-center mb-4">
                            <div class="col-lg">
                                <nav aria-label="Bootstrap Pagination Example">
                                    <ul class="pagination mb-0">
                                    </ul>
                                </nav>
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-5"><div>{{ $text_showing }}</div></div>
                                    <div class="col-sm-7"><div><ul class="pagination">{!! $pagination !!}</ul></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="blog-widget mb-4">
                            <h6 class="mb-4">Latest Post</h6>
                            @foreach($latestPosts as $latestPost)
                                <div class="card border-0 bg-light mb-1">
                                    <div class="card-body row align-items-center">
                                        <div class="col-4">
                                            <a href="{{ route('news.detail', $latestPost->link) }}"><img class="card-img" src="{{ asset($latestPost->image) }}" alt="card image"></a>
                                        </div>
                                        <div class="col-8">
                                            <h6 class="my-2 font-size-14"><a href="{{ route('news.detail', $latestPost->link) }}">{{ $latestPost->name }}</a></h6>
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
