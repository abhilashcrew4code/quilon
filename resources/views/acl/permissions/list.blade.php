@extends('layouts.master')
@extends('layouts.sidemenu')

@section('title') Manage Permissions @endsection

@section('css')
<style>
    .select2-container {
        z-index: 100000;
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}">

@endsection
@section('content')

<div class="card">
    <div class="card-datatable table-responsive pt-0">
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="card-header flex-column flex-md-row">
                <div class="head-label text-center">
                    <h4 class="card-header">Manage Permissions</h4>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons">


                        <a id="createNewRole" class="dt-button create-new btn btn-primary"
                            data-endpoint="{{ route('permission_groups.create') }}" data-target="modal-lg"
                            data-cache="false" data-toggle='modal' href='#' data-async="true">
                            <span>
                                <i class="mdi mdi-plus me-sm-1"></i>
                                <span class="d-none d-sm-inline-block">Create New Permission Group</span>
                            </span>
                        </a>

                        <a id="createNewRole" class="dt-button create-new btn btn-primary"
                            data-endpoint="{{ route('permissions.create') }}" data-target="modal-lg" data-cache="false"
                            data-toggle='modal' href='#' data-async="true">
                            <span>
                                <i class="mdi mdi-plus me-sm-1"></i>
                                <span class="d-none d-sm-inline-block">Create New Permission</span>
                            </span>
                        </a>

                        {{-- <a id="createNewuser" class="btn btn-block btn-crm"
                            data-endpoint="{{ route('users.create') }}" data-bs-target="modal-default"
                            data-cache="false" data-toggle='modal' href='#' data-async="true">Create Test</a> --}}

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_length" id="DataTables_Table_0_length"><label>Show
                            <select name="entry_count" id="entry_count" aria-controls="DataTables_Table_0"
                                class="form-select" onchange="getData(1)">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="75">75</option>
                                <option value="100">100</option>
                            </select> Entries</label>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                    <div id="DataTables_Table_0_filter" class="dataTables_filter">
                        <label>Search:
                            <input type="search" name="search" id="search" class="form-control"
                                aria-controls="DataTables_Table_0" onkeyup="getData(1)" placeholder="Search . . .">
                        </label>
                    </div>
                </div>
            </div>
            <div id="showresult">
                @include('acl.permissions.listPagin')
            </div>
        </div>
    </div>
</div>



@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script type="text/javascript">
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
                               
                                if (data.status == 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: data.message,
                                        showConfirmButton: true,
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
            $.ajax(
            {
                url: '?page=' + page + '&entry_count='+entry_count + '&search='+ search,
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