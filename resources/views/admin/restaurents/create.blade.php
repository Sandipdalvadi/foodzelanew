@extends('admin.common.main')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ $categories->id ? __('message.edit_categories') : __('message.add_categories') }}</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.home')}}"> <i
                                        data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.categories.index')}}">{{ __('message.categories') }} </a>
                            </li>
                            <li class="breadcrumb-item">{{ $categories->id ? __('message.edit_categories') : __('message.add_categories') }}</li>
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
                                    <form method="POST" class="theme-form" enctype="multipart/form-data" action="{{route('admin.categories.save')}}">
                                        @csrf
                                        <input class="form-control" type = "hidden" name="id" value="{{$categories->id ? $categories->id : 0 }}">
                                        <div class="form-group">
                                            <label for="name">{{ __('message.name_english') }}</label>
                                            <input class="form-control" id="name" type="text" placeholder="{{ __('message.name_english') }}" name="name_en" value="@if(old('name_en')){{old('name_en')}}@else{{ $categories->name_en ? $categories->name_en : ''}}@endif">
                                        </div>
                                        <div class="form-group">
                                            <label for="name_arabic">{{ __('message.name_arabic') }}</label>
                                            <input class="form-control" id="name_arabic" type="text" placeholder="{{ __('message.name_arabic') }}" name="name_ar" value="@if(old('name_ar')){{old('name_ar')}}@else{{ $categories->name_ar ? $categories->name_ar : ''}}@endif">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label pt-0" for="image">{{ __('message.image') }}</label>
                                            <input class="form-control" id="image" type="file" placeholder="categories_image" name="image" >
                                            <img id="image_img"
                                            src="{{ $categories->image != '' ? file_exists_in_folder('categories', $categories->image) : file_exists_in_folder('categories', '') }}"
                                            alt="Image" width="150px" />
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="col-form-label pt-0">{{ __('message.status') }}</label>
                                            <select class="form-control js-example-basic-single" name="status">
                                                <option value="1" @if($categories->status == 1) selected @endif>{{ __('message.active') }}</option>
                                                <option value="0" @if($categories->status == 0) selected @endif>{{ __('message.in_active') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit">{{ __('message.submit') }}</button>
                                            <a href="{{route('admin.categories.index')}}" class="btn btn-danger">{{ __('message.cancel') }}</a>
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

        $("#image").change(function() {
            readURL(this,"image_img");
        });
    </script>
@endsection