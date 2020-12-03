@extends('admin.common.main')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>Default</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="http://laravel.pixelstrap.com/cuba/dashboard/index"> <i
                                        data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active">Default</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row second-chart-list third-news-update">
                <div class="col-xl-3 xl-50 chart_data_right box-col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="media-body right-chart-content">
                                    <h4>$95,900<span class="new-box">Hot</span></h4>
                                    <span>Purchase Order Value</span>
                                </div>
                                <div class="knob-block text-center">
                                    <input class="knob1" data-width="10" data-height="70" data-thickness=".3"
                                        data-angleoffset="0" data-linecap="round" data-fgcolor="#7366ff"
                                        data-bgcolor="#eef5fb" value="60">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection