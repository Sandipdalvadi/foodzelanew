@extends('admin.common.main')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('/public/assets/css/toggle_menu.css')}}">
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ __('message.restaurents') }}</h3>
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
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="tabarticleid_wrapper" class="dataTables_wrapper no-footer">
                                        <div class="row">
                                            <div class="col-md-3" style="">
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2">Status</span> 
                                                    <select class="mb-4 form-control js-example-basic-single" onchange="restaurentDatatable()" name="status" id="selectStatus">
                                                        <option value="3">All</option>
                                                        <option value="2">Pening</option>
                                                        <option value="0">In Active</option>
                                                        <option value="1">Active</option>
                                                    </select>
                                                </div> 
                                            </div>
                                            <div class="col-md-9 text-right">
                                                <a style="color: white" class="mb-4 btn btn-danger deletesellected" onclick='multipleDelete("{{route("admin.restaurents.alldelete")}}")'> <i class="fa fa-trash" aria-hidden="true"></i>Delete </a>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="example" class="display nowrap" style="width:100%" role="grid" aria-describedby="tabarticleid_info" style="width: 100%px;">
                                                
                                                <thead>
                                                    <tr role="row">
                                                        <th class="text-center sorting_asc" style="text-align: center; width: 100px;" rowspan="1" colspan="1" aria-label="">
                                                        <input type="checkbox" id="selectAll">
                                                        </th>
                                                        <th class="sorting" tabindex="0" aria-controls="tabarticleid" rowspan="1" colspan="1" style="width: 100px;" aria-label="Id:activate to sort column ascending">{{ __('message.id') }}</th>
                                                        <th class="sorting" tabindex="0" aria-controls="tabarticleid" rowspan="1" colspan="1" style="width: 220px;" aria-label="company_name:activate to sort column ascending">{{ __('message.name') }}</th>
                                                        <th class="sorting" tabindex="0" aria-controls="tabarticleid" rowspan="1" colspan="1" style="width: 220px;" aria-label="company_name:activate to sort column ascending">{{ __('message.owner_logo') }}</th>
                                                        <th class="sorting" tabindex="0" aria-controls="tabarticleid" rowspan="1" colspan="1" style="min-width: 120px;" aria-label="company_name:activate to sort column ascending">{{ __('message.status') }}</th>
                                                        <th  tabindex="0" aria-controls="tabarticleid" rowspan="1" colspan="1" style="width: 200px;" aria-label="Action:activate to sort column ascending">{{ __('message.action') }}</th>
                                                    </tr>
                                                </thead>                
                                            </div>
                                        </table> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function()
        {
            restaurentDatatable();
            
        });
        function restaurentDatatable(){
            var status = $("#selectStatus option:selected").val();
            $('#example').DataTable({
                  "processing": true,
                  "serverSide": true,
                  destroy: true,
                  "rowId": 'Id',
                  "ajax":{
                       "url": "{{ route('admin.restaurents.list') }}",
                       "dataType": "json",
                       "type": "POST",
                       "data":{ _token: "{{csrf_token()}}", status:status},
                   },
          
                   "columns": [
                       { "data": "checkdata","orderable":false,"bSortable": true, "className": "text-center" },
                       { "data": "id"},
                       { "data": "name"},
                       { "data": "owner_logo"},
                       { "data": "status","orderable":false,"bSortable": true },
                       { "data": "action","orderable":false,"bSortable": true },                
                   ]  
               });
        }
        function changeStatus(objs,urls){
            
            jQuery.ajax({
                type: "get",
                url: urls,
                data: {'status':objs.value},
                success: function(resultData){
                        
                }
            });

        }
    </script>
</div>
<script src="{!! asset('public/assets/ajax/deletefunction.js') !!}"></script>
@endsection
