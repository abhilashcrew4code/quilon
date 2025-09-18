@isset($user)
<form class="form-horizontal" method="POST" action="{{ route('change.password', $user->id) }}" id="changePasswordForm">
        @csrf
        @endisset
        <div class="modal-header">
            <h4 class="modal-title" id="modelHeading">@isset($user) Change Password @endisset</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="alert alert-danger" id="erralert"></div>
            <input type="hidden" name="user_id" id="user_id" @isset($user->id) value="{{ $user->id }}" @endisset>
            <div class="card-body">

                <div class="row g-2">
                    <div class="col mb-2">
                        <label for="name" class="form-label">Name</label>
                            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text"
                            name="name" placeholder="Name"@isset($user->name) value="{{ $user->name }}" @endisset readonly="" />
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col mb-2">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password"
                                name="password" placeholder="Password" required />
                            <span class="input-group-text cursor-pointer" onclick="togglePasswordVisibility('password')">
                                <i class="mdi mdi-eye-off-outline" id="eye-icon-password"></i>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row g-2">
                    <div class="col mb-2">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input id="password_confirmation" class="form-control @error('password') is-invalid @enderror" type="password"
                                name="password_confirmation" placeholder="Confirm Password" required />
                            <span class="input-group-text cursor-pointer" onclick="togglePasswordVisibility('password_confirmation')">
                                <i class="mdi mdi-eye-off-outline" id="eye-icon-password_confirmation"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="submit" class="btn btn-danger waves-effect waves-light" id="saveBtn">Save</button>
                <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
    </form>

    <script>
        function togglePasswordVisibility(fieldId) {
            var passwordField = document.getElementById(fieldId);
            var eyeIcon = document.getElementById('eye-icon-' + fieldId);
    
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

    <script type="text/javascript">
        $('#changePasswordForm').submit(function(e){
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
            console.log(data);
            if (data.status == 'success') {
                $('#modal-default').modal('hide');
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
            $('#saveBtn').html('Save');
            var err_resp = JSON.parse(data.responseText);
            $('#erralert').show();
            $('#erralert').append('<ul></ul>');
            $.each( err_resp.errors, function( key, value) {
                $('#erralert ul').append('<li>'+value+'</li>');
            });
        });
    });

    </script>