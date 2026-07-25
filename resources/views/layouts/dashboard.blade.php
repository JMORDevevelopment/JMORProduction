<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <base href="{{ url('/') }}/" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $title }}</title>
  <meta name="description" content="{{ $description ?? '' }}">
  <meta name="keywords" content="{{ $keywords ?? '' }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <script src="{{ asset('assets/plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/dist/css/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/dist/css/skins/_all-skins.min.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/datepicker3.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker-bs3.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="{{ url('dashboard') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->

      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="{{ asset('assets/images/logo.png') }}" style="max-width:300px; margin-left:-30px;" alt="User Image"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ asset('assets/dist/img/avatar5.png') }}" class="user-image" alt="User Image">
              <span class="hidden-xs"></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{ asset('assets/dist/img/avatar5.png') }}" class="img-circle" alt="User Image">

                <p>

                </p>
              </li>
              <!-- Menu Body -->

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat">logout</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
    </nav>
  </header>

  @include('dashboard.partials.sidebar')

  @yield('content')

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0.0
    </div>
    <strong>Copyright &copy; {{ date('Y') }}</strong> All rights
    reserved.
  </footer>

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/app.min.js') }}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ asset('assets/dist/js/demo.js') }}"></script>
<script type="text/javascript">
$(function () {
	$('#datetimepicker').datepicker({
		format:'yyyy-mm-dd'
	});
});
</script>

@yield('scripts')

</body>
</html>
