<!-- Bootstrap js-->
<script src="{{asset('public/assets/js/bootstrap/popper.min.js')}}"></script>
<script src="{{asset('public/assets/js/bootstrap/bootstrap.js')}}"></script>
<!-- feather icon js-->
<script src="{{asset('public/assets/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{asset('public/assets/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- Sidebar jquery-->
<script src="{{asset('public/assets/js/config.js')}}"></script>
<!-- Plugins JS start-->
<script src="{{asset('public/assets/js/sidebar-menu.js')}}"></script>
<script src="{{asset('public/assets/js/chart/chartist/chartist.js')}}"></script>
<script src="{{asset('public/assets/js/chart/chartist/chartist-plugin-tooltip.js')}}"></script>
<script src="{{asset('public/assets/js/chart/knob/knob.min.js')}}"></script>
<script src="{{asset('public/assets/js/chart/knob/knob-chart.js')}}"></script>
<script src="{{asset('public/assets/js/chart/apex-chart/apex-chart.js')}}"></script>
<script src="{{asset('public/assets/js/chart/apex-chart/stock-prices.js')}}"></script>
<script src="{{asset('public/assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('public/assets/js/dashboard/default.js')}}"></script>
<script src="{{asset('public/assets/js/notify/index.js')}}"></script>
<script src="{{asset('public/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('public/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('public/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('public/assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{asset('public/assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/typeahead/typeahead.custom.js')}}"></script>
<script src="{{asset('public/assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{asset('public/assets/js/typeahead-search/typeahead-custom.js')}}"></script>
<script src="{{asset('public/assets/js/tooltip-init.js')}}"></script>
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{asset('public/assets/js/script.js')}}"></script>
<script src="{{asset('public/assets/js/theme-customizer/customizer.js')}}"></script>

<script src="{{asset('public/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/assets/js/datatable/datatables/datatable.custom.js')}}"></script>

<script src="{{asset('public/assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('public/assets/js/select2/select2-custom.js')}}"></script>
<!-- login js-->
<!-- Plugin used-->
<script src="{{ asset('public/assets/js/notify/bootstrap-notify.min.js') }}"></script>

@if (Session::has('message'))
    <script type="text/javascript">
        $(function() {
            notify("{{ Session::get('message') }}", "info", "bottom", "right")
        });

    </script>
@endif
@if ($alert = Session::get('error'))
    <script type="text/javascript">
        $(function() {
            notify("{{ Session::get('error') }}", "danger", "bottom", "right")
        });

    </script>

@endif
@foreach ($errors->all() as $error)
    <script type="text/javascript">
        $(function() {
            notify("{{ $error }}", "danger", "bottom", "right")
        });
    </script>
@endforeach
<script>
    function notify(message,type,from,align){		
            $.notify({
                // options
                message: message 
            },{
                // settings
                type: type,
                placement: {
                    from: from,
                    align: align
                }
            });
            
        }
    
</script>