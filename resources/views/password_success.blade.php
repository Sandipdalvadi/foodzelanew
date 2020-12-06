<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('public/assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('public/assets/images/favicon.png') }}" type="image/x-icon">
    <title>Foodzela</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
        rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/feather-icon.css') }}">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('public/assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/responsive.css') }}">
</head>

<body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="loader-index"><span></span></div>
        <svg>
            <defs></defs>
            <filter id="goo">
                <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
                <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo">
                </fecolormatrix>
            </filter>
        </svg>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- error-400 start-->
        <div class="error-wrapper">
            <div class="container">
                <img class="img-100" src="{{ asset('public/assets/images/other-images/sad.png') }}" alt="">
                <div class="error-heading">
                    <h5 class="headline font-info" style="font-size: 185px">Password Set Successfully</h5>
                </div>
            </div>
        </div>
        <!-- error-400 end-->
    </div>

    <!-- latest jquery-->
    <script src="{{ asset('public/assets/js/jquery-3.5.1.min.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('public/assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/bootstrap/bootstrap.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('public/assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- Sidebar jquery-->

    <script src="{{ asset('public/assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('public/assets/js/config.js') }}"></script>
    <!-- Plugins JS start-->
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('public/assets/js/script.js') }}"></script>
    <!-- login js-->
    <!-- Plugin used-->

</body>

</html>
