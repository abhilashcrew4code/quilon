<table class="table">
    <thead>
        <tr>
            <th>Sl No.</th>
            <th>Name</th>
            <th>Code</th>
            <th>MRP</th>
            <th>Price</th>
            {{-- <th>Status</th> --}}
            <th>Manage</th>
        </tr>
    </thead>

    


    <tbody class="table-border-bottom-0">

        @forelse($products as $key => $value)
        
        <tr>
            <td> {{ $key + $products->firstItem()}}</td>
            <td>{{ $value->name }}</td>
            <td>{{ $value->code }}</td>
            <td>{{ $value->mrp }}</td>
            <td>{{ $value->price }}</td>
            {{-- <td>@if($value->status==1) Active @else Blocked @endif</td> --}}

            <td>

                <a href="#" data-endpoint="{{ route('products.edit', $value->id) }}" data-async="true"
                    data-toggle="tooltip" data-id="{{ $value->id }}" title="Edit" class="editUser"
                    data-target="modal-lg" data-cache="false" data-act_type="editUser"><i
                        class="mdi mdi-pencil-outline me-2"></i></a>


            </td>
        </tr>
        @empty
        <tr>
            <th scope="row" colspan="9">No Data To List . . . </th>
        </tr>
        @endforelse
    </tbody>
</table>




<div class="d-flex justify-content-center">
    {!! $products->links() !!}
</div>