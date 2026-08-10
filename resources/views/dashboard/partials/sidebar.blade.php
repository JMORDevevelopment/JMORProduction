<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
            </div>
            <div class="pull-left info">
                <p></p>
                <a href="#"><i class="fa fa-circle text-success"></i>Online</a>
            </div>
        </div>

        {{-- NOTE: CI's sidebar hardcodes class="active" on the Dashboard item only,
           with no conditional logic for the c
           rent page. Preserved exactly. --}}
        <ul class="sidebar-menu">
            <li class="header"></li>
            <li class="active">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.orders') }}">
                    <i class="fa fa-laptop"></i>
                    <span>Orders</span>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.giftcard') }}">
                    <i class="fa fa-laptop"></i>
                    <span>Gift Card</span>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.testimonial') }}">
                    <i class="fa fa-laptop"></i>
                    <span>Testimonial</span>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.user_settings') }}">
                    <i class="fa fa-user"></i>
                    <span>Setting</span>
                </a>
            </li>

            <li>
                <a href="{{ route('home') }}" target="_blank">
                    <i class="fa fa-question-circle"></i>
                    <span>View Frontend</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
