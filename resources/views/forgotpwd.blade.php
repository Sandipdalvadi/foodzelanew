<!DOCTYPE html>
<html lang="en">
@php
$siteSettings = App\Models\SiteSettings::first();
$siteSettings = !empty($siteSettings) ? $siteSettings : new SiteSettings;
@endphp
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon"
        href="{{ $siteSettings->favicon_logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->favicon_logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}"
        type="image/x-icon">
    <link rel="shortcut icon"
        href="{{ $siteSettings->favicon_logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->favicon_logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}"
        type="image/x-icon">
    <title>Admin Login</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/feather-icon.css') }}">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('public/assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/responsive.css') }}">


    <style>
        /* .shw-pwd{
            display: block;
        }
        .hd-pwd{
            display: none;
        } */
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="login-card">
                    <div>
                        <div>
                            <a class="logo" href="javascript:void(0)">
                                <img class="img-fluid for-light"
                                    src="{{ $siteSettings->logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}"
                                    alt="looginpage">
                                <img class="img-fluid for-dark"
                                    src="{{ $siteSettings->logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}"
                                    alt="looginpage">
                            </a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="{{ route('passwordChange1') }}">
                                @csrf
                                <input class="form-control"type="hidden" name="id" value="{{$id}}">
                                <div class="form-group">
                                    <label class="col-form-label">{{ __('Password') }}</label>
                                    <input id="txtPassword" class="form-control  @error('password') is-invalid @enderror"
                                        type="password" name="password" required="" placeholder="*********">
                                    {{-- <div class="show-hide" ><span onclick="showHidePWD('password')" class="hd-pwd show"> </span></div> --}}
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">{{ __('Confirm Password') }}</label>
                                    <input id="txtConfirmPassword" class="form-control @error('password') is-invalid @enderror"
                                        type="password" name="cpassword" required="" placeholder="*********">
                                    <span id="valid_password" style="display:none;color:red;">{{ __('Password Not Match') }}</span>
                                    {{-- <div class="show-hide" ><span onclick="showHidePWD('cpassword')" class="show hd-pwd"> </span></div> --}}
                                    @error('cpassword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block" id="submit" onclick="checkPassword()" type="button">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('public/assets/js/jquery-3.5.1.min.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('public/assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/bootstrap/bootstrap.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('public/assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('public/assets/js/config.js') }}"></script>
    <!-- Plugins JS start-->
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('public/assets/js/script.js') }}"></script>
    <!-- Plugin used-->
    <script type="text/javascript">
        function checkPassword(){
            var password = jQuery("#txtPassword").val();
            var cpassword = jQuery("#txtConfirmPassword").val();
            if(password == cpassword){
                jQuery("#submit").attr("type","submit");
            }
            else{
                jQuery("#valid_password").show();
            }
        }
    </script>
</body>

</html>
