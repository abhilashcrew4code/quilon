@extends('layouts.base')

@section('basecss')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
@endsection

@section('content')

<h4 class="mb-2">Welcome to Quilon Pickles !</h4>
<p class="mb-4">Please sign-in to your account and start the adventure</p>


<form class="mb-3" action="{{ route('login') }}" id="loginForm" method="POST">
    @csrf
    <div class="form-floating form-floating-outline mb-3">
        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username"
            value="{{ old('username') }}" required autocomplete="username" placeholder="Username">
            <input id="valid_till" type="hidden" name="valid_till" value="{{ $valid_till }}">
        @error('username')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <label for="email">Username</label>
    </div>
    <div class="mb-3">
        <div class="form-password-toggle">
            <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password">
                    
                    <label for="password">Password</label>
                </div>
                <span class="input-group-text cursor-pointer" onclick="togglePasswordVisibility()">
                    <i class="mdi mdi-eye-off-outline" id="eye-icon"></i>
                </span>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="mb-3 d-flex justify-content-between">
        {{-- <div class="form-check">
        </div>
        <a href="auth-forgot-password-cover.html" class="float-end mb-1">
            <span>Forgot Password?</span>
        </a> --}}
        <button type="submit" class="btn btn-primary d-grid w-100">Sign in</button>
    </div>

</form>
<p class="text-center">
    <span>Copyright &copy; @php echo date('Y') @endphp  </span>
</p>
@endsection

@section('basejs')

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>

<script type="text/javascript">
    $('#loginForm').submit(function(e){
    e.preventDefault();
    $('#erralert').hide();
    $('#erralert').html(" ");
    $('#saveBtn').html('Sending..');
    var form    = $(this);
    var formData = new FormData($(form)[0]);
    $.ajax({
        data : formData,
        url  : form.attr('action'),
        type : form.attr('method'),
        processData: false,
        contentType: false
    }).done(function(data) {
        console.log(data);
        if (data.status == 'success') {
            location.reload();

        }else if(data.status == 'error'){
            Swal.fire({
            icon: 'error',
            text: data.message,
            type: 'error',
            customClass: {
            confirmButton: 'btn btn-danger waves-effect waves-light'
            },
            buttonsStyling: false,
            // timer: 3000,
            }).then((data) => {
            location.reload();
            })
        }else{
        }
    }).fail(function(data) {
       
    });
});


</script>

<script>

$(document).on({
        ajaxStart: function() {
            $.blockUI({
                message: '<div class="spinner-border text-white" role="status"></div>',
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                overlayCSS: {
                    opacity: 0.5
                }
            });
        },
        ajaxStop: function() {
            $.unblockUI();
        }
    });

    $(document).ajaxComplete(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
            if (xhr.status === 401 || xhr.status === 419) {
                toastr.error('Session Expired. Redirecting...')

                setTimeout(function() {
                    location.reload(true);
                }, 2000);
            } else if (xhr.status === 422) {
                // console.log(xhr.responseJSON.errors);
                // var err_resp = JSON.parse(xhr.responseJSON);

                $.each(xhr.responseJSON.errors, function(key, value) {
                    toastr.error(value);
                });

            } else if (xhr.status === 500) {
                toastr.error('Some error occured. Please contact administrator.')
            }
            // alert("Ajax request completed with response code " + xhr.status);
        });
        
    function togglePasswordVisibility() {
        var passwordField = document.getElementById("password");
        var eyeIcon = document.getElementById("eye-icon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove("mdi-eye-off-outline");
            eyeIcon.classList.add("mdi-eye-outline");
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove("mdi-eye-outline");
            eyeIcon.classList.add("mdi-eye-off-outline");
        }
    }
</script>

@endsection