@isset($invest)
<form class="form-horizontal" method="POST" action="{{ route('invest.update', $invest->id) }}" id="investForm" autocomplete="off">
    @method('PUT')
@else 
<form class="form-horizontal" method="POST" action="{{ route('invest.store') }}" id="investForm" autocomplete="off">
    @endisset
    @csrf

    <div class="modal-header">
        <h4 class="modal-title" id="modelHeading">@isset($invest) Edit Enquiry @else Create New Enquiry @endisset</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="alert alert-danger" id="erralert"></div>

        <div class="card-body">
            <div class="row g-2 mb-2">
                <div class="col-12 col-md">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" class="form-control" type="text" 
                           name="name" placeholder="Name"
                           @isset($invest->name) value="{{ $invest->name }}" @endisset required />
                </div>
            </div>

             <div class="row g-2 mb-2">
                <div class="col-12 col-md">
                    <label for="amount" class="form-label">Amount</label>
                    <input id="amount" class="form-control" type="text" 
                           name="amount" placeholder="Amount"
                           @isset($invest->amount) value="{{ $invest->amount }}" @endisset required />
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-12 col-md">
                    <label for="date" class="form-label">Date</label>
                    <input id="date" class="form-control" type="date" 
                           name="date" placeholder="Customer Name"
                           @isset($invest->date) value="{{ $invest->date }}" @endisset required />
                </div>
            </div>

            

            <div class="row g-2 mb-2">
               
               <div class="col-12 col-md">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea id="remarks" class="form-control" name="remarks" rows="2">@isset($invest->remarks){{ $invest->remarks }}@endisset</textarea>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary waves-effect waves-light" id="saveBtn">Save</button>
            <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</form>

<script type="text/javascript">

    // Ajax Submit
    $('#investForm').submit(function(e){
        e.preventDefault();
        $('#erralert').hide();
        $('#erralert').html(" ");
        $('#saveBtn').html('Sending..');

        var form = $(this);
        var formData = new FormData(this);

        $.ajax({
            data: formData,
            url : form.attr('action'),
            type: form.attr('method'),
            processData: false,
            contentType: false,
        }).done(function(data) {
            if (data.status == 'success') {
                $('#modal-default').modal('hide');
                Swal.fire({
                    icon: 'success',
                    text: data.message,
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
            $.each(err_resp.errors, function(key, value) {
                $('#erralert ul').append('<li>'+value+'</li>');
            });
        });
});

var num_alert_test = "Must be a numeric value";
    
setInputFilter(document.getElementById("amount"), function(value) {
return /^\d*[.]?\d*$/.test(value); }
, "Must be a number");


</script>