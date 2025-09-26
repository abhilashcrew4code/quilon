<table class="table">
    <thead>
        <tr>
            <th>Sl No.</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Manage</th>
        </tr>
    </thead>

    <tbody class="table-border-bottom-0">
        @forelse($invest as $key => $value)
        <tr>
            <td> {{ $key + $invest->firstItem()}}</td>
            <td>{{ $value->name }}</td>
            <td>{{ $value->amount }}</td>
            <td>{{ \Carbon\Carbon::parse($value->date)->format('M-d-Y') }}</td>
            
            <td>
                <a href="#" data-endpoint="{{ route('invest.edit', $value->id) }}" data-async="true"
                    data-toggle="tooltip" data-id="{{ $value->id }}" title="Edit" class="editUser"
                    data-target="modal-default" data-cache="false" data-act_type="editUser"><i
                        class="mdi mdi-pencil-outline me-2"></i></a>

                    @if($value->status==1)
                    <a href="#" data-endpoint="{{ route('invest.delete', $value->id) }}" data-async="true"
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
    {!! $invest->links() !!}
</div>