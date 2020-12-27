@extends('admin.common.main')
@section('content')
    @php
    $userObj = $restaurents->hasOneUser ? $restaurents->hasOneUser : [];
    $userImg = file_exists_in_folder('profile_pic', '');
    if($userObj){
    if($userObj->profile_link == 1){
    $userImg = $userObj->profile_pic ? $userObj->profile_pic : file_exists_in_folder('profile_pic', '');
    }
    else{
    $userImg = $userObj->profile_pic ? file_exists_in_folder('profile_pic', $userObj->profile_pic) :
    file_exists_in_folder('profile_pic', '');
    }
    }
    @endphp
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ __('message.restaurents_detail') }}</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.home') }}"> <i data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.home') }}"> {{ __('message.restaurents') }}</a>
                            </li>
                            <li class="breadcrumb-item">{{ __('message.restaurents_detail') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-4 box-col-6">
                    <div class="card custom-card">
                        <div class="card-header"><img class="img-fluid"
                                src="{{ file_exists_in_folder('ownerLogo', $restaurents->owner_logo) }}" alt=""></div>
                        <div class="card-profile">
                            <img style="width: 136px;height: 136px;" class="rounded-circle" src="{{ file_exists_in_folder('ownerLogo', $restaurents->owner_logo) }}" alt="">
                            <img style="width: 136px;height: 136px;"class="rounded-circle" src="{{ $userImg }}" alt="">
                        </div>
                        <div class="text-center profile-details mt-3">
                            <b class="mb-1 d-block">Restauren id: {{ $restaurents->id }}</b>
                            <h4>{{ __('message.restaurent_name') }}: {{ $restaurents->name ? $restaurents->name : '' }}</h4>
                            <h6>{{ __('message.restaurent_owner_name') }}: {{ $userObj ? $userObj->name : '' }}</h6>
                        </div>
                        <div class="card-footer row">
                            <div class="col-6 col-sm-6">
                                <h6>{{ __('message.open_time') }}</h6>
                                <h3 class="counter">{{ $restaurents->open_time ? $restaurents->open_time : '' }}</h3>
                            </div>
                            <div class="col-6 col-sm-6">
                                <h6>{{ __('message.close_time') }}</h6>
                                <h3><span
                                        class="counter">{{ $restaurents->close_time ? $restaurents->close_time : '' }}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="media p-20">
                            <i class="m-r-10 font-primary" data-feather="shopping-bag"></i>
                            <div class="media-body">
                                <h6 class="m-0 mega-title-badge">{{ __('message.total_orders') }}<span
                                        class="badge badge-primary pull-right">1234</span></h6>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="media p-20">
                            <i class="m-r-10 font-primary" data-feather="food"></i>
                            <div class="media-body">
                                <h6 class="m-0 mega-title-badge">{{ __('message.total_foods') }}<span
                                        class="badge badge-primary pull-right">36</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-8 box-col-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('message.owner_info') }}</h5>
                        </div>
                        <div class="card-body megaoptions-border-space-sm">
                            <div class="row">
                                <div class="col-sm-12 xl-100">
                                    <div class="card">
                                        <div class="media p-20">
                                            <i class="m-r-10 font-primary" data-feather="edit-2"></i>
                                            <div class="media-body">
                                                <h6 class="mt-0 mega-title-badge">{{ __('message.description') }}</h6>
                                                <p>{{ $restaurents->description ? $restaurents->description : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 xl-100">
                                    <div class="card">
                                        <div class="media p-20">
                                            <i class="m-r-10 font-primary" data-feather="map-pin"></i>
                                            <div class="media-body">
                                                <h6 class="mt-0 mega-title-badge">{{ __('message.dddress') }}</h6>
                                                <p>{{ $restaurents->address ? $restaurents->address : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="card">
                                        <div class="media p-20">
                                            <i class="m-r-10 font-primary" data-feather="map"></i>
                                            <div class="media-body">
                                                <h6 class="mt-0 mega-title-badge">{{ __('message.latitude') }}</h6>
                                                <p>{{ $restaurents->latitude ? $restaurents->latitude : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="card">
                                        <div class="media p-20">
                                            <i class="m-r-10 font-primary" data-feather="map"></i>
                                            <div class="media-body">
                                                <h6 class="mt-0 mega-title-badge">{{ __('message.longitude') }}</h6>
                                                <p>{{ $restaurents->longitude ? $restaurents->longitude : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <div class="media p-20">
                                            <i class="m-r-10 font-primary" data-feather="phone"></i>
                                            <div class="media-body">
                                                <h6 class="mt-0 mega-title-badge">{{ __('message.phone_number') }}</h6>
                                                <p>{{ $restaurents->phone ? $restaurents->phone : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="card">
                                        <div class="media p-20">
                                            <i class="m-r-10 font-primary" data-feather="dollar-sign"></i>
                                            <div class="media-body">
                                                <h6 class="m-0 mega-title-badge">{{ __('message.delivery_fee') }}<span
                                                        class="badge badge-primary pull-right">$
                                                        {{ $restaurents->delivery_fee ? $restaurents->delivery_fee : '' }}</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="card">
                                        <div class="media p-20">
                                            <i class="m-r-10 font-primary" data-feather="percent"></i>
                                            <div class="media-body">
                                                <h6 class="m-0 mega-title-badge">{{ __('message.admin_commission') }}<span
                                                        class="badge badge-primary pull-right">{{ $restaurents->admin_commission ? $restaurents->admin_commission : '' }}%</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card text-right">
                                        <a class="btn btn-success btn-sm" href="{{route('admin.restaurents.index')}}">{{ __('message.back') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
