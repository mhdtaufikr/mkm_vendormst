<!DOCTYPE html>
<html>
<head>
    <title>Customer Approval Required</title>
</head>
<body>
    <h1>Customer Approval Required</h1>
    <p>Dear Sir/Madam  {{ $approvalName }},</p>
    <p> A new customer master record requires your approval:</p>
    <p>Customer Name: <strong>{{ $customerMaster->name }}</strong> </p>
    <p><a href="{{ $approvalLink }}">Click here to review and approve</a></p>
    <p>Thank you.</p>
</body>
</html>
