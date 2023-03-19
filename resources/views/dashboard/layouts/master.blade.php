@php
    date_default_timezone_set("Asia/Kuwait");
    header("Content-Type: text/html; charset=utf-8");
    set_time_limit(0);
    //session_start();
    // include_once('includes/system_connection.php');
    include_once('includes/functions.php');

@endphp

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>@yield('title', 'Questanya')</title>
    <meta name="HandheldFriendly" content="true">
    <meta name="viewport" content="width=device-width, initial-scale=0.666667, maximum-scale=0.666667, user-scalable=0">
    <meta name="viewport" content="width=device-width">

    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?=$title?>" />
    <meta property="og:description" content="<?=$content?>" />
    <meta property="og:url" content="<?=$url?>" />
    <meta property="og:image" content="<?=$img?>" />
    <meta property="og:image:width" content="1438" />
    <meta property="og:image:height" content="1126" />


    <!--<script src="js/min.js"></script>-->
    <script type="text/javascript" src="{{ asset("js/app.js") }}"></script>
    <link rel="stylesheet" href="{{ asset("css/jquery-ui.min.css") }}">
    <link rel="stylesheet" href="{{ asset("css/swiper-bundle.css") }}">
    <link rel="stylesheet" href="{{ asset("css/cropper.css") }}">
    <link rel="stylesheet" href="{{ asset("plugins/toastr/toastr.min.css") }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="{{ asset('css/style.css') . '?v=' . rand(10,100) }}" rel="stylesheet" type="text/css" />
    <style>
        html, body {
            height: 100% !important;
        }
        .ripple{
            background: rgb(148,187,233);
            background: -moz-radial-gradient(circle, rgba(148,187,233,1) 49%, rgba(238,174,202,1) 50%, rgba(148,187,233,1) 50%);
            background: -webkit-radial-gradient(circle, rgba(148,187,233,1) 49%, rgba(238,174,202,1) 50%, rgba(148,187,233,1) 50%);
            background: radial-gradient(circle, rgba(148,187,233,1) 49%, rgba(238,174,202,1) 50%, rgba(148,187,233,1) 50%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#94bbe9",endColorstr="#94bbe9",GradientType=1); 
        }
        .swal2-container {
          z-index: 999999999 !important;
        }

        #toast-container {
          z-index: 999999999 !important;
        }
    </style>
    
    @yield('css')

</head>

<body>
    
    <div class="dsktop_view @yield('additonal_classes_for_main_div') <?php if($main_action=='login'){ echo 'abcdx';}?>">
        @yield('content')
        <input type="hidden" class="base_url" value="<?=$base_url?>" />
    </div>

    <script src="{{ asset("plugins/toastr/toastr.min.js") }}"></script>
    <script src="{{ asset("js/ckfinder.js") }}"></script>
    <script src="{{ asset("js/cropper.js") }}"></script>
    <script src="{{ asset("js/jquery-ui.min.js") }}"></script>
    <script src="{{ asset("js/jquery.ui.touch-punch.min.js") }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset("js/localforage.min.js") }}"></script>
    <script src="//cdn.jsdelivr.net/npm/bluebird@3.7.2/js/browser/bluebird.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{ asset("plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("dist/js/adminlte.js") }}"></script>
    <script src="{{ asset("plugins/jquery-validation/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("plugins/jquery-validation/additional-methods.min.js") }}"></script>
    @yield('pkgs')
    @php
        if(isset($_SESSION['message'])){
        $split = explode(',',$_SESSION['message']);
        echo '<script> swal('.$split[0].', '.$split[1].', '.$split[2].'); </script>';
        unset($_SESSION['message']);
        }
    @endphp
   
    <script type="text/javascript" src="{{ asset("js/for_html.js") }}"></script>
    <script type="text/javascript" src="{{ asset("js/recorder.js") }}"></script>
    <script type="text/javascript" src="{{ asset("js/index.js") }}"></script>
    <script>
        $( function() {
            $('[data-toggle="popover"]').popover()

      const Toast = Swal.mixin({
          toast: true,
          position: 'top-right',
          showConfirmButton: false,
          timer: 7000
        });
        var d = localStorage.getItem('draft');
        var p = localStorage.getItem('published');
        if (d) {
            localStorage.removeItem('draft');
            fireAlert(Toast, 'Exam Saved as Draft');
        } else if(p) {
            localStorage.removeItem('published');
            fireAlert(Toast, 'Exam Published Successfully');
        }
    })

    function fireAlert(Toast, txt) {
      
        Toast.fire({
            icon: 'success',
            title: txt
          });
    }
    </script>
    
    @yield('scripts')
</body>

</html>
