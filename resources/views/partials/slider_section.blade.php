<!--Carousel Wrapper-->
<div id="multi-item-example" class="carousel slide carousel-multi-item" data-bs-ride="carousel">

    <!--Controls-->
    <div class="controls-top text-center">
        <a class="btn-floating" href="#multi-item-example" data-bs-slide="prev">
            <i class="fa fa-angle-left" style="font-size:40px; color:white;" aria-hidden="true"></i>
        </a>
        <a class="btn-floating" href="#multi-item-example" data-bs-slide="next">
            <i class="fa fa-angle-right" aria-hidden="true" style="font-size:40px; color:white;"></i>
        </a>
    </div>
    <!--/.Controls-->

    <div class="carousel-inner" role="listbox">
        <div class="carousel-item active">
            @php $count = 0; @endphp

            @foreach ($mainSliders as $mainSlider)
                @if ($count === 3)
                    </div>
                    <div class="carousel-item">
                    @php $count = 0; @endphp
                @endif

                <div class="col-lg-4 col-md-4 col-sm-4 col-xm-4" style="float:left;">
                    <div class="card mb-2">
                        <img class="card-img-top img-responsive" style="width: 100%;"
                             src="{{ url($mainSlider['slider_image']) }}"
                             alt="{{ $mainSlider['slider_name'] }}">
                        <div class="card-body">
                            <h4 class="card-title">{{ $mainSlider['slider_name'] }}</h4>
                            @if (!empty($mainSlider['slider_link']))
                                <button type="button" class="btn btn-primary">
                                    <a href="{{ $mainSlider['slider_link'] }}" style="color:#fff;">View Details</a>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                @php $count++; @endphp
            @endforeach
        </div>
    </div>
</div>
<!--/.Carousel Wrapper-->

<style>
    .btn-floating i {
        display: inline-block;
        width: inherit;
        color: #fff;
        text-align: center;
        font-size: 1.25rem;
        line-height: 47px;
    }

    .btn-floating {
        position: relative;
        z-index: 1;
        display: inline-block;
        padding: 0;
        margin: 10px;
        overflow: hidden;
        vertical-align: middle;
        cursor: pointer;
        border-radius: 50%;
        box-shadow: 0 5px 11px 0 rgba(0,0,0,0.18), 0 4px 15px 0 rgba(0,0,0,0.15);
        transition: all .2s ease-in-out;
        width: 47px;
        height: 47px;
    }

    .waves-effect {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }

    a {
        color: #007bff;
        text-decoration: none;
        background-color: transparent;
        cursor: pointer;
        transition: all .2s ease-in-out;
    }
</style>