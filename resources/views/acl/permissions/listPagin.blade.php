<table class="table">
    <thead>
        <tr>
            <th>Sl.No</th>
            <th>Name</th>
            <th>Group Name</th>
            <th>Display Name</th>
            @hasrole('super-admin') <th>Manage</th> @endhasrole
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">

        @forelse($permissions as $key => $value)

        <tr>
            <td> {{ $key + $permissions->firstItem()}}</td>
            <td>{{ $value->name }}</td>
            <td>@isset($value->permissionGroup) {{ $value->permissionGroup->name }}
                @endisset</td>
            <td>{{ $value->display_name }}</td>
            @hasrole('super-admin')
            <td>
                <div class="btn-group btn-group-sm">

                    <a href="#" data-endpoint="{{ route('permissions.edit', $value->id) }}" data-async="true"
                        data-toggle="tooltip" data-id="{{ $value->id }}" title="Edit" class="editUser"
                        data-target="modal-lg" data-cache="false" data-act_type="editPermission"><i
                            class="mdi mdi-pencil-outline me-2"></i></a>
                </div>
            </td>
            @endhasrole
        </tr>
        @empty
        <tr>
            <th scope="row" colspan="9">No Data To List . . . </th>
        </tr>
        @endforelse
    </tbody>
</table>




<div class="d-flex justify-content-center">
    {!! $permissions->links() !!}
</div>