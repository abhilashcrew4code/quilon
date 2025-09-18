@isset($announcement)
<form class="form-horizontal" method="POST" action="{{ route('announcements.update', $announcement->id) }}" id="userForm"
    autocomplete="off">
    @method('PUT')
    @else
    <form class="form-horizontal" method="POST" action="{{ route('announcements.store') }}" id="userForm">
        @endisset
        @csrf
        <div class="modal-header">
            <h4 class="modal-title" id="modelHeading">@isset($announcement) Edit Announcement @else Create New Announcement
                @endisset
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="alert alert-danger" id="erralert"></div>
            <input type="hidden" name="user_id" id="user_id" @isset($announcement->id) value="{{ $announcement->id }}"
            @endisset>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col mb-2">
                        <label for="announcement" class="form-label">Announcement</label>
                        <textarea class="form-control @error('announcement') is-invalid @enderror" id="announcement" name="announcement" required>@isset($announcement->announcement) {{ $announcement->announcement }} @endisset</textarea>
                    </div>
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
                $('#modal-default').modal('hide');
                Swal.fire({
                icon: 'success',
                text: data.message,
                type: 'success',
                customClass: {
                confirmButton: 'btn btn-primary waves-effect waves-light'
                },
                buttonsStyling: false,
                // timer: 3000,
                }).then((data) => {
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