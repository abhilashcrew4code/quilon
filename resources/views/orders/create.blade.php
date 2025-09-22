@isset($order)
<form class="form-horizontal" method="POST" action="{{ route('orders.update', $order->id) }}" id="orderForm" autocomplete="off">
    @method('PUT')
@else
<form class="form-horizontal" method="POST" action="{{ route('orders.store') }}" id="orderForm" autocomplete="off">
@endisset
    @csrf

    <div class="modal-header">
        <h4 class="modal-title" id="modelHeading">@isset($order) Edit Order @else Create New Order @endisset</h4>
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
                           @isset($order->customer_name) value="{{ $order->customer_name }}" @endisset required />
                </div>
                <div class="col-12 col-md">
                    <label for="payment_status" class="form-label">Payment Status</label>
                    <select id="payment_status" name="payment_status" class="form-select">
                        <option value="0" @isset($order->payment_status) @if($order->payment_status=='0') selected @endif @endisset>Unpaid</option>
                        <option value="1" @isset($order->payment_status) @if($order->payment_status=='1') selected @endif @endisset>Paid</option>
                    </select>
                </div>
            </div>

            {{-- Order Items --}}
            <h6>Products</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="orderItemsTable" style="table-layout: fixed; width:100%;">
                    <thead>
                        <tr>
                            <th style="width:45%">Product</th>
                            <th style="width:15%">Price</th>
                            <th style="width:10%">Qty</th>
                            <th style="width:20%">Subtotal</th>
                            <th style="width:10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rowIndex = 0; @endphp
                        @if(isset($order) && $order->items->count())
                            @foreach($order->items as $item)
                                <tr>
                                    <td data-label="Product">
                                        <select name="products[{{ $rowIndex }}][product_id]" class="form-select product-select" required>
                                            <option value="">-- Select Product --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" data-price="{{ $p->price }}"
                                                    {{ $item->product_id == $p->id ? 'selected' : '' }}>
                                                    {{ $p->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td data-label="Price">
                                        <input type="number" name="products[{{ $rowIndex }}][price]" class="form-control price" step="0.01" value="{{ $item->price }}">
                                    </td>
                                    <td data-label="Qty">
                                        <input type="number" name="products[{{ $rowIndex }}][quantity]" class="form-control qty" value="{{ $item->quantity }}" min="1">
                                    </td>
                                    <td data-label="Subtotal">
                                        <input type="number" name="products[{{ $rowIndex }}][subtotal]" class="form-control subtotal" step="0.01" value="{{ $item->subtotal }}">
                                    </td>
                                    <td data-label="">
                                        <button type="button" class="btn btn-danger btn-sm removeRow">&times;</button>
                                    </td>
                                </tr>
                                @php $rowIndex++; @endphp
                            @endforeach
                        @else
                            <tr>
                                <td>
                                    <select name="products[0][product_id]" class="form-select product-select" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="products[0][price]" class="form-control price" step="0.01" readonly></td>
                                <td><input type="number" name="products[0][quantity]" class="form-control qty" value="1" min="1"></td>
                                <td><input type="number" name="products[0][subtotal]" class="form-control subtotal" step="0.01" readonly></td>
                                <td><button type="button" class="btn btn-danger btn-sm removeRow">&times;</button></td>
                            </tr>
                            @php $rowIndex = 1; @endphp
                        @endif
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-sm btn-secondary mt-2" id="addRow">+ Add Product</button>

            <div class="row g-2 mt-3">
               <div class="col-12 col-md">
                    <label for="total_amount" class="form-label">Total Amount</label>
                    <input id="total_amount" class="form-control" type="number" step="0.01" 
                           name="total_amount" 
                           @isset($order->total_amount) value="{{ $order->total_amount }}" @endisset />
                </div>
                 <div class="col-12 col-md">
                    <label for="date" class="form-label">Date</label>
                    <input id="date" class="form-control" type="date" name="date" 
                           @isset($order->date) value="{{ $order->date }}" @endisset required />
                </div>
               
            </div>
             <div class="row g-2 mt-3">

                <div class="col-12 col-md">
                    <label for="delivery_status" class="form-label">Delivery Status</label>
                    <select name="delivery_status" id="delivery_status" class="form-select">
                        <option value="0" {{ old('delivery_status', $order->delivery_status ?? 0) == 0 ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ old('delivery_status', $order->delivery_status ?? 0) == 1 ? 'selected' : '' }}>Shipped</option>
                        <option value="2" {{ old('delivery_status', $order->delivery_status ?? 0) == 2 ? 'selected' : '' }}>Delivered</option>
                        <option value="3" {{ old('delivery_status', $order->delivery_status ?? 0) == 3 ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>


                 <div class="col-12 col-md">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea id="remarks" class="form-control" name="remarks" rows="2">@isset($order->remarks){{ $order->remarks }}@endisset</textarea>
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
$(document).ready(function(){
    let rowIndex = 1;

    // Add new row
    $('#addRow').click(function(){
        let newRow = `
        <tr>
            <td>
                <select name="products[${rowIndex}][product_id]" class="form-select product-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="products[${rowIndex}][price]" class="form-control price" step="0.01" readonly></td>
            <td><input type="number" name="products[${rowIndex}][quantity]" class="form-control qty" value="1" min="1"></td>
            <td><input type="number" name="products[${rowIndex}][subtotal]" class="form-control subtotal" step="0.01" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">&times;</button></td>
        </tr>`;
        $('#orderItemsTable tbody').append(newRow);
        rowIndex++;
    });

    // Remove row
    $(document).on('click', '.removeRow', function(){
        $(this).closest('tr').remove();
        calculateTotal();
    });

    // On product change, fill price
    $(document).on('change', '.product-select', function(){
        let price = $(this).find(':selected').data('price') || 0;
        let row = $(this).closest('tr');
        row.find('.price').val(price);
        row.find('.qty').val(1);
        row.find('.subtotal').val(price);
        calculateTotal();
    });

    // On qty change, recalc subtotal
    $(document).on('input', '.qty', function(){
        let row = $(this).closest('tr');
        let price = parseFloat(row.find('.price').val()) || 0;
        let qty = parseInt($(this).val()) || 0;
        row.find('.subtotal').val(price * qty);
        calculateTotal();
    });

    let manualTotalEdit = false;

    $('#total_amount').on('input', function() {
        manualTotalEdit = true;
    });


    function calculateTotal(){
       
        if (manualTotalEdit) return; 

        let total = 0;
        $('.subtotal').each(function(){
            total += parseFloat($(this).val()) || 0;
        });
        $('#total_amount').val(total.toFixed(2));

    }

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
});
</script>