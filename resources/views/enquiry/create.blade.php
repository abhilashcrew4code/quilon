@isset($enquiry)
<form class="form-horizontal" method="POST" action="{{ route('enquiry.update', $enquiry->id) }}" id="orderForm" autocomplete="off">
    @method('PUT')
@else
<form class="form-horizontal" method="POST" action="{{ route('enquiry.store') }}" id="orderForm" autocomplete="off">
    @endisset
    @csrf

    <div class="modal-header">
        <h4 class="modal-title" id="modelHeading">@isset($enquiry) Edit Enquiry @else Create New Enquiry @endisset</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="alert alert-danger" id="erralert"></div>

        <div class="card-body">
            <div class="row g-2 mb-2">
                <div class="col-12 col-md">
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input id="customer_name" class="form-control" type="text" 
                           name="customer_name" placeholder="Customer Name"
                           @isset($enquiry->customer_name) value="{{ $enquiry->customer_name }}" @endisset required />
                </div>
                <div class="col-12 col-md">
                    <label for="payment_status" class="form-label">Product</label>
                    <select class="form-control" id="product_id" name="product_id" required=""
                        data-placeholder="Select Author" style="width: 100%;">
                        <option value="">Select</option>
                        @foreach ($products as $product)
                        <option value="{{ $product->id }}" @isset($enquiry->product_id )
                            {{ $enquiry->product_id == $product->id ? 'selected' : '' }} @endisset>
                            {{ $product->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-12 col-md">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input id="quantity" class="form-control" type="text" 
                           name="quantity" placeholder="Quantity"
                           @isset($enquiry->quantity) value="{{ $enquiry->quantity }}" @endisset required />
                </div>
                <div class="col-12 col-md">
                    <label for="payment_status" class="form-label">Date</label>
                   <input id="date" class="form-control" type="date" 
                           name="date" placeholder="Date"
                           @isset($enquiry->date) value="{{ $enquiry->date }}" @endisset required />
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-12 col-md">
                    <label for="order_status" class="form-label">Payment Status</label>
                    <select id="order_status" name="order_status" class="form-select">
                        <option value="0" @isset($enquiry->order_status) @if($enquiry->order_status=='0') selected @endif @endisset>Pending</option>
                        <option value="1" @isset($enquiry->order_status) @if($enquiry->order_status=='1') selected @endif @endisset>Success</option>
                    </select>
                </div>
               <div class="col-12 col-md">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea id="remarks" class="form-control" name="remarks" rows="2">@isset($enquiry->remarks){{ $enquiry->remarks }}@endisset</textarea>
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
    $('#orderForm').submit(function(e){
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
                $('#modal-xl').modal('hide');
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
    
setInputFilter(document.getElementById("quantity"), function(value) {
return /^\d*[.]?\d*$/.test(value); }
, "Must be a number");


</script>