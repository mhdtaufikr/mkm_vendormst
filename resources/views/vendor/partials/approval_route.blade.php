@if ($data->vendorChanges && count($data->vendorChanges) > 0)
    @foreach ($data->vendorChanges as $change)
        <div>
            <strong class="{{ $change->latestPending == 'Approved' ? 'text-success' : 'text-warning' }}">
                {!! $change->latestPending == 'Approved' ? 'Approved' : 'Pending at ' . $change->latestPending !!}
            </strong>

            <button class="btn btn-link btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#approvalRoute{{ $change->id }}" aria-expanded="false" aria-controls="approvalRoute{{ $change->id }}">
                Show All
            </button>

            <div class="collapse" id="approvalRoute{{ $change->id }}">
                @if ($change->groupedApprovalRoutes && count($change->groupedApprovalRoutes) > 0)
                    <ul class="list-unstyled">
                        @foreach ($change->groupedApprovalRoutes as $deptLevel => $approvers)
                            @php
                                // Extract department name only, without level for display
                                $dept = explode('-', $deptLevel)[0];
                            @endphp
                            <li>
                                <strong>{{ $dept }}:</strong>
                                {!! $approvers !!}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No approval routes available.</p>
                @endif
            </div>
        </div>
    @endforeach
@else
    <p>No vendor changes available.</p>
@endif
