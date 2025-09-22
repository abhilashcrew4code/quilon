@isset($expense)
<form class="form-horizontal" method="POST" action="{{ route('expenses.update', $expense->id) }}" id="expenseForm"
    autocomplete="off">
    @method('PUT')
    @else
    <form class="form-horizontal" method="POST" action="{{ route('expenses.store') }}" id="expenseForm">
        @endisset
        @csrf
        <div class="modal-header">
            <h4 class="modal-title" id="modelHeading">@isset($expense) Edit Expenses @else Create New Expenses @endisset</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="alert alert-danger" id="erralert"></div>
            <input type="hidden" name="user_id" id="user_id" @isset($expense->id) value="{{ $expense->id }}" @endisset>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12 col-md">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" class="form-control @error('name') is-invalid @enderror" type="text"
                            name="name" placeholder="Name" @isset($expense->name)
                        value="{{ $expense->name }}" @endisset required="" />
                    </div>

                    <div class="col-12 col-md">
                        <label for="amount" class="form-label">Amount</label>
                        <input id="amount" class="form-control @error('amount') is-invalid @enderror" type="amount"
                            name="amount" placeholder="Amount" @isset($expense->amount) value="{{ $expense->amount }}" @endisset />
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-12 col-md">
                        <label for="date" class="form-label">Date</label>
                        <input id="date" class="form-control @error('date') is-invalid @enderror" type="date"
                            name="date" placeholder="Date" @isset($expense->date)
                        value="{{ $expense->date }}" @endisset />
                    </div>
                    <div class="col-12 col-md">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea id="remarks" 
                                class="form-control @error('remarks') is-invalid @enderror" 
                                name="remarks" 
                                placeholder="Enter remarks" 
                                rows="3"
                                autocomplete="off">@isset($expense->remarks){{ $expense->remarks }}@endisset</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
        $('#expenseForm').submit(function(e){
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
    

    setInputFilter(document.getElementById("amount"), function(value) {
    return /^\d*[.]?\d*$/.test(value); }
    , "Must be a number");
    </script>