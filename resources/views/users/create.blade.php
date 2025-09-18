@isset($user)
<form class="form-horizontal" method="POST" action="{{ route('users.update', $user->id) }}" id="userForm"
    autocomplete="off">
    @method('PUT')
    @else
    <form class="form-horizontal" method="POST" action="{{ route('users.store') }}" id="userForm">
        @endisset
        @csrf
        <div class="modal-header">
            <h4 class="modal-title" id="modelHeading">@isset($user) Edit User @else Create New User @endisset</h4>
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
                            name="name" placeholder="Name" @isset($user->name)
                        value="{{ $user->name }}" @endisset required="" />
                    </div>

                    <div class="col mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" class="form-control @error('email') is-invalid @enderror" type="email"
                            name="email" placeholder="Email" @isset($user->email) value="{{
                        $user->email }}" @endisset />
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col mb-2">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input id="mobile" class="form-control @error('mobile') is-invalid @enderror" type="text"
                            name="mobile" placeholder="Mobile" @isset($user->mobile)
                        value="{{ $user->mobile }}" @endisset />
                    </div>
                    <div class="col mb-2">
                        <label for="username" class="form-label">Username</label>
                        <input id="username" class="form-control @error('username') is-invalid @enderror" type="text"
                            name="username" placeholder="Username" @isset($user->username)
                        value="{{ $user->username }}" @endisset required="" autocomplete="off" />
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col mb-2" @isset($user) style="display: none;" @endisset>
                        <label for="password" class="form-label">Password</label>
                        <input id="password" class="form-control @error('username') is-invalid @enderror"
                            type="password" name="password" placeholder="Password" @isset($user) @else required=""
                            @endisset />
                    </div>

                    <div class="col mb-2">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control select2" id="role_name" name="role_name[]" required=""
                            multiple="multiple" data-placeholder="Select Role" style="width: 100%;">
                            @foreach($roles as $role)
                            @php $selected = ' '; @endphp
                            @isset($assigned_roles)
                            @foreach ($assigned_roles as $rn)
                            @if ($role==$rn)
                            @php $selected = 'selected'; @endphp
                            @endif
                            @endforeach
                            @else
                            @endisset
                            <option value="{{ $role }}" {{ $selected }}>{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>

                    @isset($user)
                    <div class="col mb-2">
                    </div>

                    @else
                    @endisset
                </div>


            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="saveBtn">Save</button>
                <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        $('#userForm').submit(function(e){
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
                $('#modal-lg').modal('hide');
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

    var num_alert_test = "Must be a numeric value";
    

    setInputFilter(document.getElementById("mobile"), function(value) {
    return /^\d*[.]?\d*$/.test(value); }
    , "Must be a number");
    </script>