<!DOCTYPE html>
<html>
<head>
    <title>Vendor Form Completed</title>
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
        h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <h2>Supplier Form Completed</h2>
    <p>The Supplier form with account number {{ $vendorMaster->vendor_account_number }} has been completed.</p>

    <h3>Supplier Master Details</h3>
    <table>
        <tr>
            <th>ID</th>
            <td>{{ $vendorMaster->id }}</td>
        </tr>
        <tr>
            <th>Supplier Account Number</th>
            <td>{{ $vendorMaster->vendor_account_number }}</td>
        </tr>
        <tr>
            <th>Company Code</th>
            <td>{{ $vendorMaster->company_code }}</td>
        </tr>
        <tr>
            <th>Account Group</th>
            <td>{{ $vendorMaster->account_group }}</td>
        </tr>
        <tr>
            <th>Supplier Name</th>
            <td>{{ $vendorMaster->vendor_name }}</td>
        </tr>
        <tr>
            <th>Title</th>
            <td>{{ $vendorMaster->title }}</td>
        </tr>
        <tr>
            <th>Department</th>
            <td>{{ $vendorMaster->department }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $vendorMaster->name }}</td>
        </tr>
        <tr>
            <th>Search Term 1</th>
            <td>{{ $vendorMaster->search_term_1 }}</td>
        </tr>
        <tr>
            <th>Search Term 2</th>
            <td>{{ $vendorMaster->search_term_2 }}</td>
        </tr>
        <tr>
            <th>Street</th>
            <td>{{ $vendorMaster->street }}</td>
        </tr>
        <tr>
            <th>House Number</th>
            <td>{{ $vendorMaster->house_number }}</td>
        </tr>
        <tr>
            <th>Postal Code</th>
            <td>{{ $vendorMaster->postal_code }}</td>
        </tr>
        <tr>
            <th>City</th>
            <td>{{ $vendorMaster->city }}</td>
        </tr>
        <tr>
            <th>Country</th>
            <td>{{ $vendorMaster->country }}</td>
        </tr>
        <tr>
            <th>Region</th>
            <td>{{ $vendorMaster->region }}</td>
        </tr>
        <tr>
            <th>PO Box</th>
            <td>{{ $vendorMaster->po_box }}</td>
        </tr>
        <tr>
            <th>Telephone</th>
            <td>{{ $vendorMaster->telephone }}</td>
        </tr>
        <tr>
            <th>Fax</th>
            <td>{{ $vendorMaster->fax }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $vendorMaster->email }}</td>
        </tr>
        <tr>
            <th>Tax Code</th>
            <td>{{ $vendorMaster->tax_code }}</td>
        </tr>
        <tr>
            <th>NPWP</th>
            <td>{{ $vendorMaster->npwp }}</td>
        </tr>
        <tr>
            <th>Bank Account Number</th>
            <td>{{ $vendorMaster->bank_key }}</td>
        </tr>
        <tr>
            <th>Bank Name</th>
            <td>{{ $vendorMaster->bank_account }}</td>
        </tr>
        <tr>
            <th>Account Holder</th>
            <td>{{ $vendorMaster->account_holder }}</td>
        </tr>
        <tr>
            <th>Bank Region</th>
            <td>{{ $vendorMaster->bank_region }}</td>
        </tr>
        <tr>
            <th>Recon Account</th>
            <td>{{ $vendorMaster->recon_account }}</td>
        </tr>
        <tr>
            <th>Sort Key</th>
            <td>{{ $vendorMaster->sort_key }}</td>
        </tr>
        <tr>
            <th>Cash Management Group</th>
            <td>{{ $vendorMaster->cash_management_group }}</td>
        </tr>
        <tr>
            <th>Payment Terms</th>
            <td>{{ $vendorMaster->payment_terms }}</td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td>{{ $vendorMaster->payment_method }}</td>
        </tr>
        <tr>
            <th>Payment Block</th>
            <td>{{ $vendorMaster->payment_block }}</td>
        </tr>
        <tr>
            <th>Withholding Tax</th>
            <td>{{ $vendorMaster->withholding_tax }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $vendorMaster->created_at }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $vendorMaster->updated_at }}</td>
        </tr>
    </table>

    <h3>Supplier Change Details</h3>
    <table>
        <tr>
            <th>ID</th>
            <td>{{ $vendorChange->id }}</td>
        </tr>
        <tr>
            <th>Vendor ID</th>
            <td>{{ $vendorChange->vendor_id }}</td>
        </tr>
        <tr>
            <th>Change Type</th>
            <td>{{ $vendorChange->change_type }}</td>
        </tr>
        <tr>
            <th>Previous SAP Vendor Number</th>
            <td>{{ $vendorChange->previous_sap_vendor_number }}</td>
        </tr>
        <tr>
            <th>Remarks</th>
            <td>{{ $vendorChange->remarks }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $vendorChange->status }}</td>
        </tr>
        <tr>
            <th>Level</th>
            <td>{{ $vendorChange->level }}</td>
        </tr>
        <tr>
            <th>Created By</th>
            <td>{{ $vendorChange->created_by }}</td>
        </tr>
        <tr>
            <th>Approved By</th>
            <td>{{ $vendorChange->approved_by }}</td>
        </tr>
        <tr>
            <th>Approved At</th>
            <td>{{ $vendorChange->approved_at }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $vendorChange->created_at }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $vendorChange->updated_at }}</td>
        </tr>
    </table>
</body>
</html>
