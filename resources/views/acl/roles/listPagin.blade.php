<table class="table">
    <thead>
        <tr>
            <th>Sl.No</th>
            <th>Name</th>
            <th>Manage</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">

        @forelse($roles as $key => $value)

        <tr>
            <td> {{ $key + $roles->firstItem()}}</td>
            <td>{{ ucwords(str_replace('-', ' ', $value->name)) }}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    @hasrole('super-admin')
                    <a href="#" data-endpoint="{{ route('roles.edit', $value->id) }}" data-async="true"
                        data-toggle="tooltip" data-id="{{ $value->id }}" data-original-title="Edit"
                        class="edit btn btn-danger waves-effect waves-light editRole" data-target="modal-default"
                        data-cache="false"><i class="mdi mdi-pencil-outline"></i></a>
                    @endhasrole
                    <a href="{{ route('acl.role.assign.permissions', $value->id) }}"
                        class="btn btn-primary waves-effect waves-light"><i class="fas fa-user-check"></i>&nbsp;Assign
                        Permission</a>
                </div>
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
    {!! $roles->links() !!}
</div>