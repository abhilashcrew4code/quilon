@extends('layouts.master')
@extends('layouts.sidemenu')

@section('title') Login Logs @endsection

@section('css')
<style>
    .select2-container {
        z-index: 100000;
    }

    .download-button {
        margin-left: 10px; /* Change the value as needed */
    }

</style>
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}">

<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css')}}">



@endsection

@section('content')


<div class="card">
    <div class="card-datatable table-responsive pt-0">
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="card-header flex-column flex-md-row">
                <div class="head-label text-center">
                    <h4 class="card-header">Login Logs</h4>
                   </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons">
                      
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 ">

                     {{-- Form Start --}}
                     <form method="POST" action=""  class="form-horizontal" id="idForm1">
                        @csrf
                        <div class="row justify-content-end">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"> <i class="menu-icon tf-icons mdi mdi-calendar-range"></i></span>
                                        <input type="text" class="form-control float-right" id="search_date" name="search_date" readonly="" value="{{ $search['search_date'] }}">
                                        <button type="button" class="btn btn-primary btn-block mb-0 mt-0" style="border-top-right-radius: 10px;border-bottom-right-radius: 10px;" onclick="getData(1)">Go</button>
                                        <span class="input-group-append">
                                            @if(auth()->user()->can('reports.login.logs.download'))
                                            <button class="dt-button add-new btn btn-primary  download-button" name="download" value="download" tabindex="0" aria-controls="DataTables_Table_0" type="submit">
                                                <span><i class="mdi mdi-download me-0 me-sm-1"></i>
                                                    <span class="d-none d-sm-inline-block">Download</span>
                                                </span>
                                            </button>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                     {{-- Form End --}}


                </div>
            </div>
            <div id="showresult">
                @include('reports.login-logs.listPagin')
            </div>
        </div>
    </div>
</div>

    {{-- <div class="row">
        <div class="col-12 ">
            <form method="POST" action=""  class="form-horizontal" id="idForm1">
                @csrf

                <div class="row justify-content-end">
                    <div class="col-3">
                        <div class="form-group ">
                            
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group  ">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right" id="search_date" name="search_date" readonly="" value="{{ $search['search_date'] }}">
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-crm btn-flat">Go!</button>
                                </span>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    @if(auth()->user()->can('reports.login.logs.download'))
                        <div class="col-2">
                            <div class="form-group ">
                                <button type="submit" name="download" value="download" class="btn btn-crm btn-flat">Download</button>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div> --}}
    <!-- Main content -->
    {{-- <div class="row">
        <div class="col-12">
            <div class="card card-crm">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="">
                                   
                            <div class="input-group-append">
                                <select name="entry_count" id="entry_count" aria-controls="example1" class="custom-select custom-select-sm form-control form-control-sm"  data-toggle="tooltip" data-placement="top"  title="Entries Per Page" onchange="getData(1)">
                                    <option value="15">15</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body ">
                    <div id="showresult" class="table-responsive p-0">
                       @include('reports.login-logs.listPagin')
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div> --}}
    <!-- /.row -->
@endsection

@section('js')


<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>


<script type="text/javascript">

    $('#search_date').daterangepicker({
            autoApply: true,
            maxDate: moment(),
            locale:{
                format:'DD/MM/YYYY'
            },
        });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
      
            $(document).on('click', 'a[data-async="true"]', function (e) {
                e.preventDefault();
                var self    = $(this),
                    url     = self.data('endpoint'),
                    target  = self.data('target'),
                    cache   = self.data('cache'),
                    act_type = self.data('act_type');
                if(act_type=='revokeAccess'){
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'warning',
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                           $.ajax({
                                url  : url,
                                type : "POST",
                                cache : cache,
                            }).done(function(data) {
                                console.log(data);
                                if (data.status == 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        text: data.message,
                                        type: 'success',
                                        customClass: {
                                        confirmButton: 'btn btn-primary waves-effect waves-light'
                                        },
                                        buttonsStyling: false,
                                        timer: 3000,
                                    });
                                    location.reload();
                                }
                            }).fail(function(data) {
                                console.log(error);
                            }); 
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                    
                        }

                    });   
                }
                else{

                    $.ajax({
                        url     : url,
                        cache   : cache,
                        success : function(result){
                            console.log(result);
                            if (target !== 'undefined'){
                                $('#'+target+' .modal-content').html(result);
                                $('#'+target).modal('show');
                                $(".select2").select2();
                                $('#erralert').html(" ");
                                $('#erralert').hide();
                            }
                        },
                        error : function(error){
                            console.log(error);
                        },
                    });
                }
            });
           
        });

</script>
<script type="text/javascript">
    $(window).on('hashchange', function() {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                }else{
                    // getData(page);
                }
            }
        });
        
        $(document).ready(function()
        {
            $(document).on('click', '.pagination a',function(event)
            {
                event.preventDefault();
      
                $('li').removeClass('active');
                $(this).parent('li').addClass('active');
      
                var myurl = $(this).attr('href');
                var page=$(this).attr('href').split('page=')[1];
      
                getData(page);
            });
      
        });
      
        function getData(page){
            
            var search = $('#search').val();
            var entry_count = $('#entry_count').val();
            var search_date = $('#search_date').val();
            $.ajax(
            {
                url: '?page=' + page + '&search='+ search +'&search_date='+ search_date,
                type: "get",
                datatype: "html"
            }).done(function(data){
                $("#showresult").empty().html(data);
                location.hash = page;
            }).fail(function(jqXHR, ajaxOptions, thrownError){
                toastr.error('An error occured. Please try again.');
                setTimeout(function() {
                    location.reload();
                }, 3000);
            });
        }
</script>
@endsection