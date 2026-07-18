<div class="container-fluid" style="background:#0053a0;padding:5px 15px;">
    <div class="container" style="text-align:right;">
        @auth
    <a href="{{ url('dashboard') }}" style="color:#fff;">My Account</a>
    <span style="color:#fff;">|</span>
    <a href="{{ url('logout') }}" style="color:#fff;"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a>
    <form id="logout-form" action="{{ url('logout') }}" method="POST" class="d-none">@csrf</form>
@else
    <a href="{{ url('login') }}" style="color:#fff;">Login</a>
@endauth
    </div>
</div>

<div class="top_bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5"></div>
            <div class="col-md-4">
                <form action="{{ url('search') }}" autocomplete="off" class="form" method="post">
                    @csrf
                    <div class="seatch">
                        <input type="text" name="search" class="form-control" placeholder="Search">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-md-3 topnav">
                <a href="{{ url('contact') }}"
                   style="height:38px;font-size:16px;background:#0053a0!important;"
                   class="btn btn-info">Reach Out Today</a>
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-md" style="background-color:#0053a0;">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon fa fa-bars" style="color:white;"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <div class="custom-size" id="otherDiv">
            <ul class="navbar-nav">
                @foreach ($navigation as $item)
                    @include('partials.navigation', ['item' => $item, 'depth' => 0])
                @endforeach
            </ul>
        </div>
    </div>
</nav>