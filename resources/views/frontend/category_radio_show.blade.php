@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@push('styles')
    <style>
        input {
            position: relative;
            width: 150px; height: 20px;
            color: white;
        }
        input:before {
            position: absolute;
            top: 3px; left: 3px;
            content: attr(data-date);
            display: inline-block;
            color: black;
        }
        input::-webkit-datetime-edit, input::-webkit-inner-spin-button, input::-webkit-clear-button {
            display: none;
        }
        input::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 3px;
            right: 0;
            color: black;
            opacity: 1;
        }
        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
            box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
        }
        @media (min-width: 768px) .col-md-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .input-group {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -ms-flex-align: stretch;
            align-items: stretch;
            width: 100%;
            padding: 0px;
        }
    </style>
@endpush

@section('content')
    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">{{ $top_title }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main role="main">
        <section class="wt-section">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-8 pull-center well">
                        <form action="{{ route('search.radio') }}" autocomplete="off" class="form" method="post">
                            @csrf
                            <div class="col-md-12">
                                <div class="input-group custom-search-form">
                                    <input type="date" class="form-control" data-date="" data-date-format="MM-DD-YYYY" id="datepicker" name="show_date" placeholder="date">
                                    <input type="text" class="form-control" placeholder="Search..." name="search" style="margin-left:20px;">
                                    <span class="input-group-btn" style="margin-left:10px;">
                                        <button class="btn btn-primary pull-right" type="submit" style="color: #fff;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-8">
                        @foreach($show_datas as $show_data)
                            <div class="jmor_radio-post">
                                <a href="{{ route('jmor-shows.detail', $show_data->link) }}">
                                    @if(!empty($show_data->image))
                                        <img style="width:100%" class="rounded mb-lg-4 mb-3 img-fluid" src="{{ asset($show_data->image) }}" alt="{{ $show_data->name }}">
                                    @else
                                        <img style="width:100%" class="rounded mb-lg-4 mb-3 img-fluid" src="{{ asset($show_data->category->image ?? '') }}" alt="{{ $show_data->name }}">
                                    @endif
                                </a>
                                <h3><a href="{{ route('jmor-shows.detail', $show_data->link) }}">{{ $show_data->name }}</a></h3>
                                <div class="meta font-lora mb-3">
                                    <a href="#">Post Date</a>
                                    <a href="#">{{ date('m-d-Y', strtotime($show_data->published)) }}</a>
                                </div>
                                <p>{{ strip_tags(substr($show_data->description, 0, 300)) . '....' }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-lg-4">
                        <div class="jmor_radio-widget mb-4">
                            <h6 class="mb-4">Categories</h6>
                            <select name="archive_year" id="archive_year">
                                <option value="{{ date('Y') }}">Current</option>
                                @for($i = 2020; $i <= date('Y') - 1; $i++)
                                    <option @if(isset($currentYear) && $currentYear == $i) selected @endif value="{{ $i }}">Archive  {{ $i }}</option>
                                @endfor
                            </select>
                            <div class="show_types">
                                @include('frontend.radio_categories', ['categories' => $categories, 'year' => $currentYear ?: date('Y')])
                            </div>
                        </div>
                        <div class="jmor_radio-widget mb-4">
                            <h6 class="mb-4">Latest Post</h6>
                            @foreach($latestPosts as $latestPost)
                                <div class="card border-0 bg-light mb-1">
                                    <div class="card-body row align-items-center">
                                        <div class="col-12">
                                            <h6 class="my-2 font-size-14"><a href="{{ route('jmor-shows.detail', $latestPost->link) }}">{{ $latestPost->name }}</a></h6>
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        $("input").on("change", function () {
            this.setAttribute(
                "data-date",
                moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format"))
            );
        });

        $('#archive_year').on('change', function () {
            var url = '{{ route('radio.categories') }}';
            var val = this.value;
            $.ajax({
                url: url,
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { year: val },
            }).then(function (data) {
                var objData = jQuery.parseJSON(data);
                $('.show_types').html(objData.data);
            });
        });
    </script>
@endpush
