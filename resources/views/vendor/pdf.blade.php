<!DOCTYPE html>
<html>
<head>
    <title>Supplier Master Maintenance Request Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1, h3 {
            color: #333;
        }
        .two-column-table th {
            width: 20%;
        }
        .two-column-table td {
            width: 30%;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <h1>Supplier Master Maintenance Request Form</h1>

    <h3>Supplier Master Details</h3>
    <table class="two-column-table">
        <tr>
            <th>ID</th>
            <td>{{ $vendorMaster->id }}</td>
            <th>Supplier Account Number</th>
            <td>{{ $vendorMaster->vendor_account_number }}</td>
        </tr>
        <tr>
            <th>Company Code</th>
            <td>{{ $vendorMaster->company_code }}</td>
            <th>Account Group</th>
            <td>{{ $vendorMaster->account_group }}</td>
        </tr>
        <tr>
            <th>Supplier Name</th>
            <td>{{ $vendorMaster->vendor_name }}</td>
            <th>Title</th>
            <td>{{ $vendorMaster->title }}</td>
        </tr>
        <tr>
            <th>Department</th>
            <td>{{ $vendorMaster->department }}</td>
            <th>Name</th>
            <td>{{ $vendorMaster->name }}</td>
        </tr>
        <tr>
            <th>Search Term 1</th>
            <td>{{ $vendorMaster->search_term_1 }}</td>
            <th>Search Term 2</th>
            <td>{{ $vendorMaster->search_term_2 }}</td>
        </tr>
        <tr>
            <th>Street</th>
            <td>{{ $vendorMaster->street }}</td>
            <th>House Number</th>
            <td>{{ $vendorMaster->house_number }}</td>
        </tr>
        <tr>
            <th>Postal Code</th>
            <td>{{ $vendorMaster->postal_code }}</td>
            <th>City</th>
            <td>{{ $vendorMaster->city }}</td>
        </tr>
        <tr>
            <th>Country</th>
            <td>{{ $vendorMaster->country }}</td>
            <th>Region</th>
            <td>{{ $vendorMaster->region }}</td>
        </tr>
        <tr>
            <th>PO Box</th>
            <td>{{ $vendorMaster->po_box }}</td>
            <th>Telephone</th>
            <td>{{ $vendorMaster->telephone }}</td>
        </tr>
        <tr>
            <th>Fax</th>
            <td>{{ $vendorMaster->fax }}</td>
            <th>Email</th>
            <td>{{ $vendorMaster->email }}</td>
        </tr>
        <tr>
            <th>Tax Code</th>
            <td>{{ $vendorMaster->tax_code }}</td>
            <th>NPWP</th>
            <td>{{ $vendorMaster->npwp }}</td>
        </tr>
        <tr>
            <th>Bank Account Number</th>
            <td>{{ $vendorMaster->bank_key }}</td>
            <th>Bank Name</th>
            <td>{{ $vendorMaster->bank_account }}</td>
        </tr>
        <tr>
            <th>Account Holder</th>
            <td>{{ $vendorMaster->account_holder }}</td>
            <th>Bank Region</th>
            <td>{{ $vendorMaster->bank_region }}</td>
        </tr>
        <tr>
            <th>Recon Account</th>
            <td>{{ $vendorMaster->recon_account }}</td>
            <th>Sort Key</th>
            <td>{{ $vendorMaster->sort_key }}</td>
        </tr>
        <tr>
            <th>Cash Management Group</th>
            <td>{{ $vendorMaster->cash_management_group }}</td>
            <th>Payment Terms</th>
            <td>{{ $vendorMaster->payment_terms }}</td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td>{{ $vendorMaster->payment_method }}</td>
            <th>Payment Block</th>
            <td>{{ $vendorMaster->payment_block }}</td>
        </tr>
        <tr>
            <th>Withholding Tax</th>
            <td colspan="3">{{ $vendorMaster->withholding_tax }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $vendorMaster->created_at }}</td>
            <th>Updated At</th>
            <td>{{ $vendorMaster->updated_at }}</td>
        </tr>
    </table>

    <div class="page-break"></div>

    <h3>Supplier Change Details</h3>
    <table class="two-column-table">
        <tr>
            <th>ID</th>
            <td>{{ $vendorChange->id }}</td>
            <th>Supplier ID</th>
            <td>{{ $vendorChange->vendor_id }}</td>
        </tr>
        <tr>
            <th>Change Type</th>
            <td>{{ $vendorChange->change_type }}</td>
            <th>Previous SAP Supplier Number</th>
            <td>{{ $vendorChange->previous_sap_vendor_number }}</td>
        </tr>
        <tr>
            <th>Remarks</th>
            <td colspan="3">{{ $vendorChange->remarks }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $vendorChange->status }}</td>
            <th>Level</th>
            <td>{{ $vendorChange->level }}</td>
        </tr>
        <tr>
            <th>Created By</th>
            <td>{{ $vendorChange->created_by }}</td>
            <th>Approved By</th>
            <td>{{ $vendorChange->approved_by }}</td>
        </tr>
        <tr>
            <th>Approved At</th>
            <td>{{ $vendorChange->approved_at }}</td>
            <th>Created At</th>
            <td>{{ $vendorChange->created_at }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td colspan="3">{{ $vendorChange->updated_at }}</td>
        </tr>
    </table>

    @if($vendorMaster->file)
        <div class="page-break"></div>
        <h3>Uploaded PDF File</h3>
        <p>File: {{ $vendorMaster->file }}</p>
    @endif

    @if($approvalLogs->isNotEmpty())
        <div class="page-break"></div>
        <h3>Approval Logs</h3>
        <table>
            <thead>
                <tr>
                    <th>Approver</th>
                    <th>Action</th>
                    <th>Comments</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($approvalLogs as $log)
                    <tr>
                        <td>{{ $log->approver->name }}</td>
                        <td>{{ $log->approval_action }}</td>
                        <td>{{ $log->approval_comments }}</td>
                        <td>{{ $log->approval_timestamp }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
