@extends('admin.common.main')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ __('message.profile') }}</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.home') }}"> <i data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">{{ __('message.profile') }}</li>
                            <li class="breadcrumb-item active">{{ __('message.edit_profile') }}</li>
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
                                    <form method="POST" class="theme-form" enctype="multipart/form-data"
                                        action="{{ route('admin.profile.save') }}">
                                        @csrf

                                        <div class="form-group">
                                            <label for="name">{{ __('message.name') }} <span style="color: red">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                value="{{ $user->name ? $user->name : '' }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">{{ __('message.email') }} <span style="color: red">*</span></label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $user->email ? $user->email : '' }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="profile_pic">{{ __('message.profile_picture') }}</label>
                                            <input type="file" name="profile_pic" id="profile_pic" class="form-control">
                                            <img id="profile_pic_img"
                                                src="{{ $user->profile_pic != '' ? file_exists_in_folder('profile_pic', $user->profile_pic) : file_exists_in_folder('profile_pic', '') }}"
                                                alt="Image" width="150px" />
                                        </div>

                                        <div class="form-group">
                                            <label for="password">{{ __('message.password') }}</label>
                                            <input type="text" id="password" name="password" class="form-control">
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
        <!-- Container-fluid Ends-->
    </div>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#profile_pic_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profile_pic").change(function() {
            readURL(this);
        });

    </script>
@endsection
