@if($categories->isNotEmpty())
    @foreach($categories as $category)
        <div class="">
            <div class="">
                <div class="col-8">
                    <h6 class="my-2 font-size-14"><a href="{{ route('category-jmor-shows.year', [$category->link, $year]) }}">{{ $category->title }}</a></h6>
                </div>
            </div>
        </div>
    @endforeach
@else
    No Record Found.
@endif
