@extends('admin.common.main')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ __('message.dashboard') }}</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                              <a href="{{route('admin.home')}}"> <i data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">{{ __('message.dashboard') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
          <div class="row second-chart-list third-news-update">            
            <div class="col-xl-3 chart_data_right box-col-12">
              <div class="card">
                <div class="card-body">
                  <div class="media align-items-center">
                    <div class="media-body right-chart-content">
                      <h4>$95,900<span class="new-box">Hot</span></h4>
                      <span>Purchase Order Value</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 chart_data_right box-col-12">
              <div class="card">
                <div class="card-body">
                  <div class="media align-items-center">
                    <div class="media-body right-chart-content">
                      <h4>900</h4>
                      <span>Customer</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 chart_data_right box-col-12">
              <div class="card">
                <div class="card-body">
                  <div class="media align-items-center">
                    <div class="media-body right-chart-content">
                      <h4>95,900</h4>
                      <span>Driver</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 chart_data_right box-col-12">
              <div class="card">
                <div class="card-body">
                  <div class="media align-items-center">
                    <div class="media-body right-chart-content">
                      <h4>900</h4>
                      <span>Restaurent Owner</span>
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