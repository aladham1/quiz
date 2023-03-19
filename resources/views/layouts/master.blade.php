<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

<title>{{env("APP_NAME", "BSharp")}}</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    @media (min-width: 992px) {
      .fixed-below-nav {
        transition: margin-left 0.3s ease-in-out;
        margin-left: 4.6rem !important;
      }

      body:not(.sidebar-mini) .fixed-below-nav .content{
        margin-left: -4.6rem !important;
      }
    }
    .dropdown-item:active {
      color: #000 !important;
      text-decoration: none;
      background-color: white !important;
    }
    .navbar-dark .navbar-nav .nav-link {
	    color: rgba(255,255,255,1) !important;
    }
    [class*="sidebar-dark-"] .sidebar a {
	    color: rgba(255,255,255,1) !important;
    }
    .content-wrapper {
    	background: #fff;
    }
    button{
      background-color: var(--pink) !important;
      cursor: pointer;
      border-radius: 5px;
      font-size: 16px !important;
      border: none !important;
      line-height: 1.25rem;
    }
    a.btn{
      background-color: var(--pink) !important;
      cursor: pointer;
      border-radius: 5px;
      font-size: 16px !important;
      border: none !important;
      line-height: 1.25rem;
    }
    a.btn.plus{
      padding: .85rem;
      font-size: 1rem !important;
    }
  </style>
  @yield('styles')
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to to the body tag
to get the desired effect
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
 
  @include('layouts.nav')
  @include('layouts.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper fixed-below-nav">
    <!-- Main content -->
    <div class="content pt-4">
      <div class="container-fluid">
        @yield('content')
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer py-2">
    <strong>Made with hardwork and discipline by <a target="_blank" href="https://www.freelancer.com/u/bsharp101">B# Technologies</a>.</strong>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset("plugins/jquery/jquery.min.js") }}"></script>
<!-- Bootstrap -->
<script src="{{ asset("plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
<!-- AdminLTE -->
<script src="{{ asset("dist/js/adminlte.js") }}"></script>
<script src="{{ asset("dist/js/demo.js") }}"></script>
<!-- OPTIONAL SCRIPTS -->
@yield('scripts')
</body>
</html>
