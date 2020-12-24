@extends('admin.common.main')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ __('message.permissions') }}</h3>
                        <p>
                            <a href="{{ route('admin.permissions.form',['id'=>0])}}" class="btn btn-success"><i class="fa fa-edit" aria-hidden="true"></i>Add New </a>
                            <a style="color: white" class="btn btn-danger deletesellected" onclick='multipleDelete("{{route("admin.permissions.alldelete")}}")'> <i class="fa fa-trash" aria-hidden="true"></i>Delete </a>
                        </p>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.home')}}"> <i
                                        data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">{{ __('message.permissions') }}</li>
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
                     
                                        <table id="example" class="display nowrap" style="width:100%" role="grid" aria-describedby="tabarticleid_info" style="width: 100%px;">
                                            <thead>
                                                <tr role="row">
                                                    <th class="text-center sorting_asc" style="text-align: center; width: 100px;" rowspan="1" colspan="1" aria-label="">
                                                    <input type="checkbox" id="selectAll">
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="tabarticleid" rowspan="1" colspan="1" style="width: 100px;" aria-label="Id:activate to sort column ascending">{{ __('message.id') }}</th>
                                                    <th class="sorting" tabindex="0" aria-controls="tabarticleid" rowspan="1" colspan="1" style="width: 220px;" aria-label="company_name:activate to sort column ascending">{{ __('message.name') }}</th>
                                                    <th class="sorting" tabindex="0" aria-controls="tabarticleid" rowspan="1" colspan="1" style="width: 220px;" aria-label="company_name:activate to sort column ascending">{{ __('message.url') }}</th>
                                                    <th class="sorting" tabindex="0" aria-controls="tabarticleid" rowspan="1" colspan="1" style="width: 220px;" aria-label="company_name:activate to sort column ascending">{{ __('message.full_url') }}</th>
                                                    <th  tabindex="0" aria-controls="tabarticleid" rowspan="1" colspan="1" style="width: 200px;" aria-label="Action:activate to sort column ascending">{{ __('message.action') }}</th>
                                                </tr>
                                            </thead>                
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
            $('#example').DataTable({
                  
               "processing": true,
               "serverSide": true,
               "rowId": 'Id',
               "ajax":{
                    "url": "{{ route('admin.permissions.list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"},
                },
       
                "columns": [
                    { "data": "checkdata","orderable":false,"bSortable": true, "className": "text-center" },
                    { "data": "id"},
                    { "data": "name"},
                    { "data": "url"},
                    { "data": "full_url"},
                    { "data": "action","orderable":false,"bSortable": true },                
                ]  
            });
        });

    </script>
</div>
<script src="{!! asset('public/assets/ajax/deletefunction.js') !!}"></script>
@endsection
