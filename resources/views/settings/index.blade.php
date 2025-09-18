@extends('layouts.master')
@extends('layouts.sidemenu')

@section('title') Global Configurations @endsection
@section('css')
<style>
    .select2-container {
        z-index: 100000;
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}">

@endsection

@section('content')
@php
if(isset($settings->json_data)){
$decodedData = json_decode($settings->json_data, true);
}
// print_r($decodedData[0]['valid_till']);
// exit;
// Replace this with your actual JSON data

@endphp


<div class="card">
    <div class="card-datatable table-responsive pt-0">
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="card-header flex-column flex-md-row pb-0">
                <div class="head-label">
                    <h4 class="card-header">Global Configurations</h4>
                </div>
            </div>
            <div class="card-body pt-2 mt-0">
                <form id="settingsForm" action="{{ route('settings.store') }}" method="POST">
                    @csrf

                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="title" name="title"
                                    @isset($settings->title)value="{{$settings->title}}"@endisset
                                placeholder="Title"
                                required="" />
                                <label for="title">Title</label>
                            </div>
                            <hr>
                        </div>
                       
                    </div>
                   
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="site_title" name="site_title"
                                    @isset($decodedData[0]['site_title'])value="{{ $decodedData[0]['site_title'] }}"
                                    @endisset placeholder="Site Title" required="" />
                                <label for="site_title">Site Title</label>
                            </div>
                            <hr>
                        </div>
                       
                    </div>
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="date" id="valid_till" name="valid_till"
                                    @isset($decodedData[0]['valid_till'])value="{{ $decodedData[0]['valid_till'] }}"
                                    @endisset placeholder="Valid Till" required="" />
                                <label for="valid_till">Valid Till</label>
                            </div>
                            <hr >
                        </div>
                    </div>
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select class="form-control" id="time_zone" name="time_zone" required=""
                                    data-placeholder="Select Timezone" style="width: 100%;">
                                    <option value="">Select</option>

                                    <option value="Asia/Kolkata" @isset($decodedData[0]['time_zone']) {{
                                        $decodedData[0]['time_zone']=='Asia/Kolkata' ? 'selected' : '' }} @endisset>
                                        Asia/Kolkata
                                    </option>

                                    <option value="America/Los_Angeles" @isset($decodedData[0]['time_zone']) {{
                                        $decodedData[0]['time_zone']=='America/Los_Angeles' ? 'selected' : '' }}
                                        @endisset>
                                        America/Los_Angeles
                                    </option>

                                    <option value="Africa/Johannesburg" @isset($decodedData[0]['time_zone']) {{
                                        $decodedData[0]['time_zone']=='Africa/Johannesburg' ? 'selected' : '' }}
                                        @endisset>
                                        Africa/Johannesburg
                                    </option>

                                    <option value="America/New_York" @isset($decodedData[0]['time_zone']) {{
                                        $decodedData[0]['time_zone']=='America/New_York' ? 'selected' : '' }} @endisset>
                                        America/New_York
                                    </option>

                                    <option value="Asia/Bangkok" @isset($decodedData[0]['time_zone']) {{
                                        $decodedData[0]['time_zone']=='Asia/Bangkok' ? 'selected' : '' }} @endisset>
                                        Asia/Bangkok
                                    </option>

                                    <option value="Asia/Qatar" @isset($decodedData[0]['time_zone']) {{
                                        $decodedData[0]['time_zone']=='Asia/Qatar' ? 'selected' : '' }} @endisset>
                                        Asia/Qatar
                                    </option>

                                    <option value="Australia/Sydney" @isset($decodedData[0]['time_zone']) {{
                                        $decodedData[0]['time_zone']=='Australia/Sydney' ? 'selected' : '' }} @endisset>
                                        Australia/Sydney
                                    </option>

                                    <option value="Europe/Paris" @isset($decodedData[0]['time_zone']) {{
                                        $decodedData[0]['time_zone']=='Europe/Paris' ? 'selected' : '' }} @endisset>
                                        Europe/Paris
                                    </option>

                                </select>
                                <label for="site_title">Timezone</label>
                            </div>
                            <hr>
                        </div>
                    </div>

                    {{-- <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select class="form-control" id="primary_color" name="primary_color" required=""
                                    data-placeholder="Select Primary Color" style="width: 100%;">
                                    <option value="">Select</option>

                                    <option value="bg-primary" @isset($decodedData[0]['primary_color']) {{
                                        $decodedData[0]['primary_color']=='bg-primary' ? 'selected' : '' }} @endisset 
                                        >
                                        bg-primary
                                    </option>

                                    <option value="bg-secondary" @isset($decodedData[0]['primary_color']) {{
                                        $decodedData[0]['primary_color']=='bg-secondary' ? 'selected' : '' }} @endisset 
                                        >
                                        bg-secondary
                                    </option>

                                    <option value="bg-danger" @isset($decodedData[0]['primary_color']) {{
                                        $decodedData[0]['primary_color']=='bg-danger' ? 'selected' : '' }}
                                        @endisset >
                                        bg-danger
                                    </option>

                                    <option value="bg-info" @isset($decodedData[0]['primary_color']) {{
                                        $decodedData[0]['primary_color']=='bg-info' ? 'selected' : '' }}
                                        @endisset >
                                        bg-info
                                    </option>

                                    <option value="bg-warning" @isset($decodedData[0]['primary_color']) {{
                                        $decodedData[0]['primary_color']=='bg-warning' ? 'selected' : '' }}
                                        @endisset >
                                        bg-warning
                                    </option>

                                    <option value="bg-success" @isset($decodedData[0]['primary_color']) {{
                                        $decodedData[0]['primary_color']=='bg-success' ? 'selected' : '' }} @endisset >
                                       bg-success
                                    </option>

                                    <option value="bg-dark" @isset($decodedData[0]['primary_color']) {{
                                        $decodedData[0]['primary_color']=='bg-dark' ? 'selected' : '' }} @endisset >
                                       bg-dark
                                    </option>

                                    <option value="bg-light" @isset($decodedData[0]['primary_color']) {{
                                        $decodedData[0]['primary_color']=='bg-light' ? 'selected' : '' }} @endisset >
                                       bg-light
                                    </option>

                                    <option value="bg-gray" @isset($decodedData[0]['primary_color']) {{
                                        $decodedData[0]['primary_color']=='bg-gray' ? 'selected' : '' }} @endisset >
                                       bg-gray
                                    </option>

                                    <option value="bg-white" @isset($decodedData[0]['primary_color']) {{
                                        $decodedData[0]['primary_color']=='bg-white' ? 'selected' : '' }} @endisset >
                                       bg-white
                                    </option>
                                </select>

                                <label for="site_title">Primary Color</label>
                            </div>
                            <hr>
                        </div>
                    </div> --}}

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                    </div>
                    {{-- @endforeach --}}
                    {{-- @endif --}}
                </form>
            </div>

        </div>
    </div>
</div>


<div class="row">

</div>

@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<script type="text/javascript">
    $(function () {
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
                                    text: data.message,
                                    type: 'success',
                                    customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                    },
                                    buttonsStyling: false,
                                    timer: 3000,
                                    }).then((result) => {
                                    location.reload();
                                    })
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
                                $('#erralert').html("");
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
    $('#settingsForm').submit(function(e){
        e.preventDefault();
        $('#erralert').hide();
        $('#erralert').html(" ");
        $('#saveBtn').html('Sending..');
        var form    = $(this);
        $.ajax({
            data : form.serialize(),
            url  : form.attr('action'),
            type : form.attr('method'),
        }).done(function(data) {
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

                $("#topMenu").load(" #topMenu > *");
                $("#layout-menu").load(" #layout-menu > *");
                

                $('#saveBtn').html('Save & Submit');
            }

            if (data.status == 'error') {
            Swal.fire({
            icon: 'error',
            text: data.message,
            type: 'error',
            customClass: {
            confirmButton: 'btn btn-primary waves-effect waves-light'
            },
            buttonsStyling: false,
            timer: 3000,
            });
            
            $('#saveBtn').html('Save & Submit');
            }
        }).fail(function(data) {
            $('#saveBtn').html('Save & Submit');
            var err_resp = JSON.parse(data.responseText);
            $('#erralert').html("");
            $('#erralert').show();
            $('#erralert').append('<ul></ul>');
            $.each( err_resp.errors, function( key, value) {
                $('#erralert ul').append('<li>'+value+'</li>');
            });
        });
    });
    
   
</script>

@endsection