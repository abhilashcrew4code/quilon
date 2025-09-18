@isset($permission)
<form class="form-horizontal" method="POST" action="{{ route('permissions.update', $permission->id) }}"
    id="permissionForm">
    @method('PUT')
    @else
    <form class="form-horizontal" method="POST" action="{{ route('permissions.store') }}" id="permissionForm">
        @endisset
        @csrf
        <div class="modal-header">
            <h4 class="modal-title" id="modelHeading">@isset($permission) Edit Permission @else Create New Permission
                @endisset</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="alert alert-danger" id="erralert"></div>
            <input type="hidden" name="permission_id" id="permission_id" @isset($permission->id) value="{{
            $permission->id }}" @endisset>
            <div class="card-body">

                <div class="row g-2">
                    <div class="col mb-2">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" class="form-control @error('name') is-invalid @enderror" type="text"
                            name="name" placeholder="Name" @isset($permission->name)
                        value="{{ $permission->name }}" @endisset required="" />
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col mb-2">
                        <label for="display_name" class="form-label">Display Name</label>
                        <input id="display_name" class="form-control @error('display_name') is-invalid @enderror"
                            type="text" name="display_name" placeholder="Display Name" @isset($permission->display_name)
                        value="{{ $permission->display_name }}" @endisset required="" />
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col mb-2">
                        <label for="display_name" class="form-label">Permission Group</label>
                        <select class="form-control select2" id="group_id" name="group_id" required="">
                            <option value="">--- Select---</option>
                            @foreach($permission_groups as $group)
                            @php $selected = ' '; @endphp
                            @isset($permission)
                            @if ($group->id==$permission->group_id)
                            @php $selected = 'selected'; @endphp
                            @endif
                            @endisset
                            <option value="{{ $group->id }}" {{ $selected }}>{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="saveBtn">Save</button>
                <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
    </form>

    <script type="text/javascript">
        $('#permissionForm').submit(function(e){
    e.preventDefault();
    $('#erralert').hide();
    $('#erralert').html(" ");
    $('#saveBtn').html('Sending..');
    var form = $(this);
    $.ajax({
    data : form.serialize(),
    url : form.attr('action'),
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
    </script>