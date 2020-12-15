@extends('admin.common.main')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ $permissions->id ? __('message.edit_permission') : __('message.add_permission') }}</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.home')}}"> <i
                                        data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.permissions.index')}}">{{ __('message.permissions') }} </a>
                            </li>
                            <li class="breadcrumb-item">{{ $permissions->id ? __('message.edit_permission') : __('message.add_permission') }}</li>
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
                                    <form method="POST" class="theme-form" enctype="multipart/form-data" action="{{route('admin.permissions.save')}}">
                                        @csrf
                                        <input class="form-control" type = "hidden" name="id" value="{{$permissions->id ? $permissions->id : 0 }}">
                                        <div class="form-group">
                                            <label for="name">{{ __('message.name') }}</label>
                                            <input class="form-control" id="name" type="text" placeholder="{{ __('message.name') }}" name="name" value="@if(old('name')){{old('name')}}@else{{ $permissions->name ? $permissions->name : ''}}@endif">
                                        </div>
                                        <div class="form-group">
                                            <label for="url">{{ __('message.url') }}</label>
                                            <input class="form-control" id="url" type="text" placeholder="{{ __('message.url') }}" name="url" value="@if(old('url')){{old('url')}}@else{{$permissions->url ? $permissions->url : ''}}@endif">
                                        </div>
                                        <div class="form-group">
                                            <label for="full_url">{{ __('message.full_url') }}</label>
                                            <input class="form-control" id="full_url" type="text" placeholder="{{ __('message.full_url') }}" name="full_url" value="@if(old('full_url')){{old('full_url')}}@else{{$permissions->full_url ? $permissions->full_url : ''}}@endif">
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit">{{ __('message.submit') }}</button>
                                            <a href="{{route('admin.permissions.index')}}" class="btn btn-danger">{{ __('message.cancel') }}</a>
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
@endsection