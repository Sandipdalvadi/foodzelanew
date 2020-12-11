@extends('admin.common.main')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ __('message.site_settings') }}</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.home')}}"> <i
                                        data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">{{ __('message.site_settings') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-xl-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" class="theme-form" enctype="multipart/form-data" action="{{route('admin.sitesetting.save')}}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="col-form-label pt-0" for="logo">{{ __('message.site_logo') }}</label>
                                            <input class="form-control" id="logo" type="file" placeholder="Site Logo" name="logo" >
                                            <img id="blah"
                                            src="{{ $siteSettings->logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}"
                                            alt="Image" width="150px" />
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label pt-0" for="logo">{{ __('message.dark_logo') }}</label>
                                            <input class="form-control" id="dark_logo" type="file" placeholder="Dark Logo" name="dark_logo" >
                                            <img id="dark_logo_img"
                                            src="{{ $siteSettings->dark_logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->dark_logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}"
                                            alt="Image" width="150px" />
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label pt-0" for="logo">{{ __('message.favicon_logo') }}</label>
                                            <input class="form-control" id="favicon_logo" type="file" placeholder="Favicon Logo" name="favicon_logo" >
                                            <img id="favicon_logo_img"
                                            src="{{ $siteSettings->favicon_logo != '' ? file_exists_in_folder('sitesetting', $siteSettings->favicon_logo) : file_exists_in_folder('default_images', 'blank_image.jpeg') }}"
                                            alt="Image" width="150px" />
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="map_api_key">{{ __('message.map_api_key') }}</label>
                                            <input class="form-control" id="map_api_key" type="text" placeholder="{{ __('message.map_api_key') }}" name="map_api_key" value="{{$siteSettings->map_api_key ? $siteSettings->map_api_key : ''}}">
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit">{{ __('message.submit') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function readURL(input,imgSrc) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#'+imgSrc).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#logo").change(function() {
            readURL(this,"blah");
        });
        $("#favicon_logo").change(function() {
            readURL(this,"favicon_logo_img");
        });

        $("#dark_logo").change(function() {
            readURL(this,"dark_logo_img");
        });
    </script>
@endsection