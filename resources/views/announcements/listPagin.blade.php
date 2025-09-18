<table class="table">
    <thead>
        <tr>
            <th>Sl No.</th>
            <th>Announcement</th>
            <th>Created Date</th>
            <th>Modified Date</th>
            <th>Status</th>
            <th>Manage</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">

        @forelse($announcements as $key => $value)
        <tr>
            <td> {{ $key + $announcements->firstItem()}}</td>
            <td>{{ \Illuminate\Support\Str::limit($value->announcement, 80, $end='...') }}</td>
            <td> {{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y H:i:s' ) }}</td>
            <td> {{ \Carbon\Carbon::parse($value->updated_at)->format('d-m-Y H:i:s' ) }}</td>
            <td>@if($value->status==1) Active @else Blocked @endif</td>

            <td>
                <a href="#" data-endpoint="{{ route('announcements.edit', $value->id) }}" data-async="true"
                    data-toggle="tooltip" data-id="{{ $value->id }}" title="Edit" class="editUser"
                    data-target="modal-default" data-cache="false" data-act_type="editUser"><i
                        class="mdi mdi-pencil-outline me-2"></i></a>
                @if($value->status==1)
                <a href="#" data-endpoint="{{ route('announcements.delete', $value->id) }}" data-async="true"
                    data-toggle="tooltip" data-id="{{ $value->id }}" title="Inactive" data-act_type="revokeAccess"
                    class="revokeAccess"><i class="mdi mdi-check me-2"></i></a>
                @elseif($value->status==2)
                <a href="#" data-endpoint="{{ route('announcements.delete', $value->id) }}" data-async="true"
                    data-toggle="tooltip" data-id="{{ $value->id }}" title="Active" class="revokeAccess"
                    data-act_type="revokeAccess"><i class="mdi mdi-cancel  me-2"></i></a>
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
    {!! $announcements->links() !!}
</div>