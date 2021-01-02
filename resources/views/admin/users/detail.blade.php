@extends('admin.common.main')
@section('content')
    @php
    $userImg = file_exists_in_folder('profile_pic', '');
    if($user->profile_link == 1){
        $userImg = $user->profile_pic ? $user->profile_pic : file_exists_in_folder('profile_pic', '');
    }
    else{
        $userImg = $user->profile_pic ? file_exists_in_folder('profile_pic', $user->profile_pic) :
        file_exists_in_folder('profile_pic', '');
    }
    @endphp
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ __('message.user_detail') }}</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.home') }}"> <i data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.users.index') }}"> {{ __('message.users') }}</a>
                            </li>
                            <li class="breadcrumb-item">{{ __('message.user_detail') }}</li>
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
                        <div class="card-profile">
                            <img style="width: 136px;height: 136px;"class="rounded-circle" src="{{ $userImg }}" alt="">
                        </div>
                        <div class="text-center profile-details mt-3">
                            <b class="mb-1 d-block">{{ __('message.user_id') }}: {{ $user->id }}</b>
                            <h4>{{ __('message.name') }}: {{ $user->name ? $user->name : '' }}</h4>
                            <h6>{{ __('message.email') }}: {{ $user->email ? $user->email : '' }}</h6>
                        </div>
                        <div class="card-footer row">
                            <div class="col-6 col-sm-6">
                                <h6>{{ __('message.is_email_verified') }}</h6>
                                <h3 class="counter">{{ $user->is_email_verified == 1 ? 'Yes' : 'No' }}</h3>
                            </div>
                            <div class="col-6 col-sm-6">
                                <h6>{{ __('message.is_phone_verified') }}</h6>
                                <h3><span
                                        class="counter">{{ $user->is_phone_verified == 1 ? $user->is_phone_verified : 'No' }}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="media p-20">
                            <i class="m-r-10 font-primary" data-feather="phone"></i>
                            <div class="media-body">
                                <h6 class="m-0 mega-title-badge">{{ __('message.phone_number') }}<span
                                        class="badge badge-primary pull-right">{{ $user->phone ? $user->phone : '' }}</span></h6>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="media p-20">
                            <i class="m-r-10 font-primary" data-feather="food"></i>
                            <div class="media-body">
                                <h6 class="m-0 mega-title-badge">{{ __('message.language') }}<span
                                        class="badge badge-primary pull-right">{{$user->language_code == 'en' ? 'English' : 'Arabic'}}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-8 box-col-6">
                    <div class="card">
                        <div class="card-body megaoptions-border-space-sm">
                            <div class="row">
                                <div class="col-sm-12 xl-100">
                                    <div class="card">
                                        <div class="media p-20">
                                            <i class="m-r-10 font-primary" data-feather="edit-2"></i>
                                            <div class="media-body">
                                                <h6 class="mt-0 mega-title-badge">{{ __('message.instagram_link') }}</h6>
                                                <p>{{ $user->instagram ? $user->instagram : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 xl-100">
                                    <div class="card">
                                        <div class="media p-20">
                                            <i class="m-r-10 font-primary" data-feather="edit-2"></i>
                                            <div class="media-body">
                                                <h6 class="mt-0 mega-title-badge">{{ __('message.snap_link') }}</h6>
                                                <p>{{ $user->snap ? $user->snap : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 xl-100">
                                    <div class="card">
                                        <div class="media p-20">
                                            <i class="m-r-10 font-primary" data-feather="map-pin"></i>
                                            <div class="media-body">
                                                <h6 class="mt-0 mega-title-badge">{{ __('message.address') }}</h6>
                                                <p>{{ $user->address ? $user->address : '' }}</p>
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
                                                <p>{{ $user->latitude ? $user->latitude : '' }}</p>
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
                                                <p>{{ $user->longitude ? $user->longitude : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <div class="media p-20">
                                            <div class="media-body">
                                                <h6 class="mt-0 mega-title-badge">{{ __('message.status') }}</h6>
                                                <p>
                                                    @if ($user->status == 2)
                                                        Pending
                                                    @elseif($user->status == 1)
                                                        Active
                                                    @else
                                                        Inactive
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card text-right">
                                        <a class="btn btn-success btn-sm" href="{{route('admin.users.index')}}">{{ __('message.back') }}</a>
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
