@extends('layouts.master')
@extends('layouts.sidemenu')

@section('title') Change Password @endsection
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

<div class="row">
    <div class="col-md-12">

        <div class="card mb-4">
            <h5 class="card-header">Change Password</h5>
            <div class="card-body">
                <div id="erralert" class="text-danger"></div>
                <form id="passwordForm" action="{{ route('password-update') }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-6 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('password') is-invalid @enderror" type="password"
                                        id="password" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        autocomplete="off" />
                                    <label for="password">New Password</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i
                                        class="mdi mdi-eye-off-outline"></i></span>
                            </div>
                            @error('password')
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-password-toggle">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                        type="password" name="password_confirmation" id="password_confirmation"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        autocomplete="off" />
                                    <label for="password_confirmation">Confirm New Password</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i
                                        class="mdi mdi-eye-off-outline"></i></span>
                            </div>
                            @error('password_confirmation')
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <!--/ Change Password -->
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>

{{-- <script src="{{ asset('assets/js/pages-account-settings-security.js') }}"></script> --}}

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
                                $('#'+target).modal();
                                $('.select2').select2();
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
    $('#passwordForm').submit(function(e){
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
            // console.log(data);
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

                $('#saveBtn').html('Save & Submit');
                $("#password").val("");
                $("#password_confirmation").val("");
                // $('#userForm').trigger("reset");
    
    
                // location.reload();
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