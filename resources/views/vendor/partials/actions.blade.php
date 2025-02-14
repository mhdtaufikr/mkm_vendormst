<div class="btn-group">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="{{ url('vendor/detail/'.encrypt($data->id)) }}" title="Detail">
                <i class="fas fa-info"></i> Detail
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{url('/vendor/update/'. encrypt($data->id))}}" title="Update">
                <i class="fas fa-pen"></i> Update
            </a>
        </li>
        <li>
            <button class="dropdown-item" title="Delete" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                <i class="fas fa-trash-alt"></i> Delete
            </button>
        </li>
        <li>
            <button class="dropdown-item" title="Show Log" data-bs-toggle="modal" data-bs-target="#modal-log{{ $data->id }}">
                <i class="fas fa-list"></i> Show Log
            </button>
        </li>
    </ul>
</div>
<!-- Log Modal -->
<div class="modal fade" id="modal-log{{ $data->id }}" tabindex="-1" aria-labelledby="modal-logLabel{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-logLabel{{ $data->id }}">Supplier Log for {{ $data->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display the log here -->
                <table id="tableLog{{ $data->id }}" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Approver</th>
                            <th>Action</th>
                            <th>Comments</th>
                            <th>Timestamp</th>
                            <th>Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->vendorChanges as $change)
                            @foreach($change->logs as $log)
                                <tr class="
                                @if($log->approval_action === 'checked')
                                    table-success
                                @elseif($log->approval_action === 'remand')
                                    table-danger
                                @elseif($log->approval_action === 'Submitter')
                                    table-primary
                                @endif
                                ">
                                    <td>{{ $log->approver->name }}</td>
                                    <td>{{ $log->approval_action }}</td>
                                    <td>{{ $log->approval_comments }}</td>
                                    <td>{{ $log->approval_timestamp }}</td>
                                    <td>{{ $log->approval_level }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
