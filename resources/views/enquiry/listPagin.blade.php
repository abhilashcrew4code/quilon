<table class="table">
    <thead>
        <tr>
            <th>Sl No.</th>
            <th>Name</th>
            <th>Amount</th>
            <td>Qty</td>
            <th>Date</th>
            <th>Order Status</th>
            <th>Manage</th>
        </tr>
    </thead>

    <tbody class="table-border-bottom-0">
        @forelse($enquiry as $key => $value)
        <tr>
            <td> {{ $key + $enquiry->firstItem()}}</td>
            <td>{{ $value->product->name }}</td>
            <td>{{ $value->customer_name }}</td>
            <td>{{ $value->quantity }}</td>
            <td>{{ \Carbon\Carbon::parse($value->date)->format('M-d-Y') }}</td>
            <td>
                @switch($value->order_status)
                    @case(0)
                        <span class="badge bg-danger">Pending</span>
                        @break
                    @case(1)
                        <span class="badge bg-success text-dark">Success</span>
                        @break
                    @default
                        <span class="badge bg-dark">Unknown</span>
                @endswitch
            </td>
            <td>
                <a href="#" data-endpoint="{{ route('enquiry.edit', $value->id) }}" data-async="true"
                    data-toggle="tooltip" data-id="{{ $value->id }}" title="Edit" class="editUser"
                    data-target="modal-xl" data-cache="false" data-act_type="editUser"><i
                        class="mdi mdi-pencil-outline me-2"></i></a>

                    @if($value->status==1)
                    <a href="#" data-endpoint="{{ route('enquiry.delete', $value->id) }}" data-async="true"
                        data-toggle="tooltip" data-id="{{ $value->id }}" title="Delete" data-act_type="revokeAccess"
                        class="revokeAccess"><i class="mdi mdi-delete me-2"></i></a>
                    @endif

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
    {!! $enquiry->links() !!}
</div>