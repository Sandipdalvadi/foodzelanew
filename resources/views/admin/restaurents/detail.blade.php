@extends('admin.common.main')
@section('content')
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
                                <a href="{{route('admin.home')}}"> <i
                                        data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">{{ __('message.restaurents') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-xl-12">
                    <div class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
