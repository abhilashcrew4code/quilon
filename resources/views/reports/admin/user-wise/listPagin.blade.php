<table class="table table-head-fixed">
    <thead>
        <tr>
            <th style="text-align: center;">Sl No.</th>
            <th style="text-align: center;">User </th>
            <th style="text-align: center;">Enquiry</th>
            <th style="text-align: center;">Service</th>
            <th style="text-align: center;">Accounts</th>
            <th style="text-align: center;">EMI Details</th>
            <th style="text-align: center;">HRD</th>
            <th style="text-align: center;">WC</th>
        </tr>
    </thead>
    <tbody>
        @forelse($allUsers as $key => $row)
        <tr>
            <td style="text-align: center;">{{ $key + 1 }}</td>
            <td style="text-align: center;">{{ $row->name }}</td>
            <td style="text-align: center;">{{ $callTypeCounts[$row->id]['New Enquiry'] ?? 0 }}</td>
            <td style="text-align: center;">{{ $callTypeCounts[$row->id]['Service'] ?? 0 }}</td>
            <td style="text-align: center;">{{ $callTypeCounts[$row->id]['Accounts'] ?? 0 }}</td>
            <td style="text-align: center;">{{ $callTypeCounts[$row->id]['EMI Details'] ?? 0 }}</td>
            <td style="text-align: center;">{{ $callTypeCounts[$row->id]['HRD'] ?? 0 }}</td>
            <td style="text-align: center;">{{ $callTypeCounts[$row->id]['Wrongly Called'] ?? 0 }}</td>
        </tr>
    @empty
        <tr>
            <th scope="row" colspan="8">No Data To List . . . </th>
        </tr>
    @endforelse
        <tr>
        <td colspan="2" style="text-align: center;"><strong>Total</strong></td>
        <td style="text-align: center;"><strong>{{ $total['new_enquiry'] }}</strong></td>
        <td style="text-align: center;"><strong>{{ $total['service'] }}</strong></td>
        <td style="text-align: center;"><strong>{{ $total['accounts'] }}</strong></td>
        <td style="text-align: center;"><strong>{{ $total['emi'] }}</strong></td>
        <td style="text-align: center;"><strong>{{ $total['hrd'] }}</strong></td>
        <td style="text-align: center;"><strong>{{ $total['wc'] }}</strong></td>
    </tr>
    </tbody>
</table>
<br>
<br>
<div class="d-flex justify-content-center">
    {{-- {!! $allUsers->links() !!} --}}
</div>