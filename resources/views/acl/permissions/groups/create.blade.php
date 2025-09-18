<form class="form-horizontal" method="POST" action="{{ route('permission_groups.store') }}" id="permissionGroupForm">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title" id="modelHeading">Create New Permission Group</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="alert alert-danger" id="erralert"></div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col mb-2">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                        placeholder="Name" required="" />
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
    $('#permissionGroupForm').submit(function(e){
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