<table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Evaluator</th>
        <th>Record ID</th>
        <th>Call Date</th>
        <th>Customer Name</th>
        <th>Customer Phone</th>
        <th>Associate</th>
        <th>Reporting To</th>
        <th>Campaign</th>
        <th>Result</th>
        <th>Alert</th>
        <th>Outcome</th>
        <th>Handling Time</th>
        <th>Call Time</th>
        <th>Evaluation Date</th>
        <th>Communication</th>
        <th>Sales</th>
        <th>Compliance</th>
        <th>Customer Service</th>
        <th>Product Presentation</th>
        <th>QA Notes</th>
      </tr>
    </thead>
    <tbody>


        @foreach ($voice_audits as $audit)
            <tr>
                <td>{{ $audit->id ?? 0 }}</td>
                <td>{{ $audit->user->name ?? '' }}</td>
                <td>{{ $audit->record_id ?? 0 }}</td>
                <td>{{ $audit->call_date }}</td>
                <td>{{ $audit->customer_name ?? '' }}</td>
                <td>{{ $audit->customer_phone ?? '' }}</td>
                <td>{{ $audit->associate->name ?? '' }}</td>
                <td>{{ $audit->associate->supervisor->name ?? '' }}</td>
                <td>{{ $audit->campaign->name ?? '' }}</td>
                <td>{{ $audit->percentage }}</td>
                <td>
                    @if($audit->outcome == 'accepted')
                        Accepted
                    @else
                        Rejected
                    @endif
                </td>
                <td>
                    @if($audit->outcome == 'rejected')
                        @if($audit->review_priority == 1)
                            Critical
                        @else
                            Normal
                        @endif
                    @else
                        NA
                    @endif
                </td>
                <td>{{ $audit->evaluation_time }}</td>
                <td>{{ $audit->recording_duration }}</td>
                <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                <td>{{ $audit->communication }}</td>
                <td>{{ $audit->sales }}</td>
                <td>{{ $audit->compliance }}</td>
                <td>{{ $audit->customer_service }}</td>
                <td>{{ $audit->product_presentation }}</td>
                <td>{{ $audit->notes }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
