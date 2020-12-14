@extends('admin.common.main')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ $user->id ? __('message.edit_manager') : __('message.add_manager') }}</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.home')}}"> <i
                                        data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.managers.index')}}">{{ __('message.managers') }} </a>
                            </li>
                            <li class="breadcrumb-item">{{ $user->id ? __('message.edit_manager') : __('message.add_manager') }}</li>
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
                                    <form method="POST" class="theme-form" enctype="multipart/form-data" action="{{route('admin.managers.save')}}">
                                        @csrf
                                        <input class="form-control" type = "hidden" name="id" value="{{$user->id ? $user->id : 0 }}">
                                        <div class="form-group">
                                            <label for="name">{{ __('message.name') }}</label>
                                            <input class="form-control" id="name" type="text" placeholder="{{ __('message.name') }}" name="name" value="@if(old('name')){{old('name')}}@else{{ $user->name ? $user->name : ''}}@endif">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">{{ __('message.email') }}</label>
                                            <input class="form-control" id="email" type="email" placeholder="{{ __('message.email') }}" name="email" value="@if(old('email')){{old('email')}}@else{{$user->email ? $user->email : ''}}@endif">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">{{ __('message.phone_number') }}</label>
                                            <input class="form-control" id="phone" type="text" placeholder="{{ __('message.phone_number') }}" name="phone" value="@if(old('phone')){{old('phone')}}@else{{$user->phone ? $user->phone : ''}}@endif">
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">{{ __('message.password') }}</label>
                                            <input class="form-control" id="password" type="password" placeholder="{{ __('message.password') }}" name="password" value="">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label pt-0" for="profile_pic">{{ __('message.profile_picture') }}</label>
                                            <input class="form-control" id="profile_pic" type="file" placeholder="Profile_pic" name="profile_pic" >
                                            <img id="profile_pic_img"
                                            src="{{ $user->profile_pic != '' ? file_exists_in_folder('profile_pic', $user->profile_pic) : file_exists_in_folder('profile_pic', '') }}"
                                            alt="Image" width="150px" />
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label pt-0" for="id_proof">{{ __('message.id_proof') }}</label>
                                            <input class="form-control" id="id_proof" type="file" placeholder="ID proof" name="id_proof" >
                                            <img id="id_proof_img"
                                            src="{{ $user->id_proof != '' ? file_exists_in_folder('id_proof', $user->id_proof) : file_exists_in_folder('id_proof', '') }}"
                                            alt="Image" width="150px" />
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class=" pt-0">{{ __('message.status') }}</label>
                                            <select class="form-control js-example-basic-single" name="status">
                                                <option value="1" @if($user->status == 1) selected @endif>{{ __('message.active') }}</option>
                                                <option value="0" @if($user->status == 0) selected @endif>{{ __('message.in_active') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit">{{ __('message.submit') }}</button>
                                            <a href="{{route('admin.managers.index')}}" class="btn btn-danger">{{ __('message.cancel') }}</a>
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

        $("#profile_pic").change(function() {
            readURL(this,"profile_pic_img");
        });
        $("#id_proof").change(function() {
            readURL(this,"id_proof_img");
        });
    </script>
@endsection