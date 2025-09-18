@isset($product)
<form class="form-horizontal" method="POST" action="{{ route('products.update', $product->id) }}" id="productForm" enctype="multipart/form-data" autocomplete="off">
    @method('PUT')
@else
<form class="form-horizontal" method="POST" action="{{ route('products.store') }}" id="productForm" enctype="multipart/form-data">
@endisset
    @csrf

    <div class="modal-header">
        <h4 class="modal-title" id="modelHeading">@isset($product) Edit Product @else Create New Product @endisset</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="alert alert-danger" id="erralert"></div>
        <input type="hidden" name="user_id" id="user_id" @isset($product->id) value="{{ $product->id }}" @endisset>

        <div class="card-body">
            <div class="row g-2">
                <div class="col mb-2">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" class="form-control @error('name') is-invalid @enderror" type="text"
                           name="name" placeholder="Product Name"
                           @isset($product->name) value="{{ $product->name }}" @endisset required />
                </div>

                <div class="col mb-2">
                    <label for="code" class="form-label">Code</label>
                    <input id="code" class="form-control @error('code') is-invalid @enderror" type="text"
                           name="code" placeholder="Product Code"
                           @isset($product->code) value="{{ $product->code }}" @endisset required />
                </div>
            </div>

            <div class="row g-2">
                <div class="col mb-2">
                    <label for="mrp" class="form-label">MRP</label>
                    <input id="mrp" class="form-control @error('mrp') is-invalid @enderror" type="number" step="0.01"
                           name="mrp" placeholder="MRP"
                           @isset($product->mrp) value="{{ $product->mrp }}" @endisset required />
                </div>
                <div class="col mb-2">
                    <label for="price" class="form-label">Selling Price</label>
                    <input id="price" class="form-control @error('price') is-invalid @enderror" type="number" step="0.01"
                           name="price" placeholder="Selling Price"
                           @isset($product->price) value="{{ $product->price }}" @endisset required />
                </div>
               
            </div>

            <div class="row g-2">
                 <div class="col mb-2">
                    <label for="stock" class="form-label">Stock</label>
                    <input id="stock" class="form-control @error('stock') is-invalid @enderror" type="number"
                           name="stock" placeholder="Stock"
                           @isset($product->stock) value="{{ $product->stock }}" @endisset required />
                </div>
                <div class="col mb-2">
                    <label for="image" class="form-label">Image</label>
                    <input id="image" class="form-control @error('image') is-invalid @enderror" type="file"
                           name="image" accept="image/*" />
                    @isset($product->image)
                        <small class="text-muted">Current: <a href="{{ asset('uploads/products/'.$product->image) }}" target="_blank">View</a></small>
                    @endisset
                </div>
                
            </div>

            <div class="row g-2">
                <div class="col mb-2">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description"
                              class="form-control @error('description') is-invalid @enderror"
                              name="description"
                              placeholder="Enter description"
                              rows="3">@isset($product->description){{ $product->description }}@endisset</textarea>
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
$('#productForm').submit(function(e){
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
            $('#modal-lg').modal('hide');
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
</script>