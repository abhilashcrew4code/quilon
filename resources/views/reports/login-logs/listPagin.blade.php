
    <table class="table table-head-fixed">
        <thead>
            <tr>
                <th style="text-align: center;">Sl No.</th>
                <th style="text-align: center;">User</th>
                <th style="text-align: center;">Login IP</th>
                <th style="text-align: center;">Location</th>
                <th style="text-align: center;">Browser</th>
                <th style="text-align: center;">OS</th>
                <th style="text-align: center;">Login Date Time</th>
                <th style="text-align: center;">Last Active Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $row)
                
                <tr>
                    <td style="text-align: center;"> {{ $key + $data->firstItem()}}</td>
                    <td style="text-align: center;"> {{ $row->user->name }}</td>
                    <td style="text-align: center;"> {{ $row->ip }}</td>
                    <td style="text-align: center;"> {{ $row->location }}</td>
                    <td style="text-align: center;"> {{ $row->browser_name }}</td>
                    <td style="text-align: center;"> {{ $row->platform_name }}</td>
                    <td style="text-align: center;"> {{ $row->created_at }}</td>
                    <td style="text-align: center;"> {{ $row->last_active_at }}</td>
                </tr>
            @empty
            <tr>
                <th scope="row" colspan="6">No Data To List . . . </th>
            </tr>
            @endforelse
        </tbody>
    </table>

<br>
<br>
<div class="d-flex justify-content-center">
    {!! $data->links() !!}
</div>