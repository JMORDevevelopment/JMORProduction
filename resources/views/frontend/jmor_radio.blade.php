@extends('layouts.app')

@section('title', $title)

@include('partials.style_file')

@section('content')
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
        @media (min-width: 768px)
        .col-md-12 {
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
            padding:0px;
        }
    </style>

    <section class="wt-section bg-gray text-center inner-page-header">
        <div class="container">
            <div class="row justify-content-md-center align-items-center text-white py-4 py-lg-5">
                <div class="col-md-7">
                    <div class="text-center">
                        <h1 class="display-sm-4 display-lg-3">JMOR SHOWS</h1>
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
                        <form action="{{ route('search-shows') }}" autocomplete="off" class="form" method="post">
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
                        @foreach($shows as $show)
                            <div class="jmor_radio-post">
                                @if(!empty($show->image))
                                    <img style="width: 100%;" class="rounded mb-lg-4 mb-3 img-fluid" src="{{ asset($show->image) }}" alt="card image">
                                @else
                                    @php $catImage = \App\Models\CategoryRadioShow::find($show->category_id); @endphp
                                    @if($catImage)
                                        <img style="width: 100%;" class="rounded mb-lg-4 mb-3 img-fluid" src="{{ asset($catImage->image) }}" alt="card image">
                                    @endif
                                @endif
                                <h3><a href="{{ route('jmor-shows.detail', $show->link) }}">{{ $show->name }}</a></h3>
                                <div class="meta font-lora mb-3">
                                    Radio show date
                                    <span style="color:#007bff;">{{ date('m-d-Y', strtotime($show->show_date)) }}</span>
                                </div>
                                <p>{{ strip_tags(substr($show->description, 0, 300)) . '....' }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-lg-4">
                        <div class="jmor_radio-widget mb-4">
                            <h6 class="mb-4">Categories</h6>
                            @php $cYear = date('Y') - 1; @endphp
                            <select name="archive_year" id="archive_year">
                                <option value="{{ date('Y') }}">Current</option>
                                @for($i = 2020; $i <= $cYear; $i++)
                                    <option value="{{ $i }}">Archive {{ $i }}</option>
                                @endfor
                            </select>
                            <div class="show_types">
                                @foreach($categories as $category)
                                    <div class="">
                                        <div class="">
                                            <div class="col-8">
                                                <h6 class="my-2 font-size-14">
                                                    <a href="{{ route('category-jmor-shows', ['category' => $category->link, 'year' => date('Y')]) }}">{{ $category->title }}</a>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="jmor_radio-widget mb-4">
                            <h6 class="mb-4">Latest Post</h6>
                            @foreach($latestShows as $latestShow)
                                <div class="card border-0 bg-light mb-1">
                                    <div class="card-body row align-items-center">
                                        <div class="col-12">
                                            <h6 class="my-2 font-size-14">
                                                <a href="{{ route('jmor-shows.detail', $latestShow->link) }}">{{ $latestShow->name }}</a>
                                            </h6>
                                            <span class="font-size-14 text-muted">{{ date('m-d-Y', strtotime($latestShow->published)) }}</span>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script>
        $("input").on("change", function() {
            this.setAttribute(
                "data-date",
                moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format"))
            );
        });

        $('#archive_year').on('change', function(){
            var val = this.value;
            $.ajax({
                url: '{{ url("home/get_categories_list") }}',
                type: 'POST',
                data: {year: val, _token: '{{ csrf_token() }}'},
            }).then(function(data) {
                var objData = JSON.parse(data);
                $('.show_types').html(objData.data);
            });
        });
    </script>
@endsection

@include('partials.script_file')
