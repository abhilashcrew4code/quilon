<table class="table">
    <thead>
        <tr>
            <th>Sl No.</th>
            <th>Name</th>
            <th>User Name</th>
            <th>Roles</th>
            <th>Creation Date</th>
            <th>Status</th>
            <th>Manage</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">

        @forelse($users as $key => $value)
        @php
        $roles = $value->roles;
        $role_name = '';
        $role_names = array();
        foreach ($roles as $k => $val) {
        $role_names[] = $val->name;
        }
        $role_name = implode(',', $role_names);
        @endphp
        <tr>
            <td> {{ $key + $users->firstItem()}}</td>
            <td>{{ $value->name }}</td>
            <td>{{ $value->username }}</td>
            <td>{{ $role_name }}</td>
            <td> {{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y H:i:s' ) }}</td>
            <td>@if($value->status==1) Active @else Blocked @endif</td>

            <td>

                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                    Large
                </button> --}}

                <a href="#" data-endpoint="{{ route('users.edit', $value->id) }}" data-async="true"
                    data-toggle="tooltip" data-id="{{ $value->id }}" title="Edit" class="editUser"
                    data-target="modal-lg" data-cache="false" data-act_type="editUser"><i
                        class="mdi mdi-pencil-outline me-2"></i></a>


                @if($value->status==1)
                <a href="#" data-endpoint="{{ route('manage.portal.access', $value->id) }}" data-async="true"
                    data-toggle="tooltip" data-id="{{ $value->id }}" title="Block User" data-act_type="revokeAccess"
                    class="revokeAccess"><i class="mdi mdi-check me-2"></i></a>
                @elseif($value->status==2)
                <a href="#" data-endpoint="{{ route('manage.portal.access', $value->id) }}" data-async="true"
                    data-toggle="tooltip" data-id="{{ $value->id }}" title="Unblock User" class="revokeAccess"
                    data-act_type="revokeAccess"><i class="mdi mdi-cancel me-2"></i></a>
                @endif

                @if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin')) 
                    <a href="#" data-endpoint="{{ route('change.password.get', $value->id) }}" data-target="modal-default" 
                        data-cache="false" title="Change Password"  data-toggle='modal' href='#' data-async="true"><i class="mdi mdi-key me-2"></i></a>
                @endif

                @hasrole('super-admin')
                    <a href="{{ route('user.impersonate.start', $value->id) }}" 
                        data-toggle='tooltip' data-placement="top" title="Start Impersonate"><i
                            class="mdi mdi-eye me-2"></i></a>
                    @endhasrole
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
    {!! $users->links() !!}
</div>