<table class="table">
    <thead>
        <tr>
            <th>Sl.No</th>
            <th>Name</th>
            <th>Manage</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">

        @forelse($users as $key => $value)
        <tr>
            <td> {{ $key + $users->firstItem()}}</td>
            <td>{{ $value->name }}</td>
            <td>
                {{-- @if(auth()->user()->can('users.list')) --}}
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('acl.user.assign.permissions', $value->id) }}"
                        class="btn btn-primary waves-effect waves-light"><i class="fas fa-user-check"></i>&nbsp;Assign
                        Permission</a>
                </div>
                {{-- @else --}}
                {{-- @endif --}}
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