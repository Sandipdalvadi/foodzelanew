@extends('admin.common.main')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>Terms Conditions</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.home')}}"> <i
                                        data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item">Terms Conditions</li>
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
                                    <form method="POST" class="theme-form" enctype="multipart/form-data" action="{{route('admin.terms_conditions.save')}}">
                                        @csrf
                                        
                                        <div class="form-group">
                                            <label for="editor0">Terms conditions English <span style="color: red">*</span></label>
                                            <textarea name="terms_conditions_en" id="editor0" cols="30" class="form-control editor" rows="10">
                                                {!! $termsConditions->terms_conditions_en ? $termsConditions->terms_conditions_en : '' !!}
                                            </textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="editor1">Terms conditions Arabic <span style="color: red">*</span></label>
                                            <textarea name="terms_conditions_ar" id="editor1" cols="30" class="form-control editor" rows="10">
                                                {!! $termsConditions->terms_conditions_ar ? $termsConditions->terms_conditions_ar : '' !!}
                                            </textarea>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit">Submit</button>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/12.4.0/classic/ckeditor.js"></script>
    <script>
        var editorId = 0;
        $(".editor").each(function() {
            ClassicEditor.create(document.querySelector('#editor'+editorId))
                .then( editor => {
                    console.log( editor );
                } )
                .catch( error => {
                    console.error( error );
            } );
            editorId++;
        });
            
    </script>

@endsection