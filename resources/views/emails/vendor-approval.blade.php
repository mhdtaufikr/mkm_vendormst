<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Approval</title>
</head>
<body>
    <h2>Vendor Approval Required</h2>
    <p>
        Dear Sir/Madam {{$approvalName }},
    </p>
    <p>
        You are required to review and approve the vendor submission "{{$vendorMaster->name}}". Please click the link below to proceed:
    </p>
    <p>
        <a href="{{ $approvalLink }}">Click here to review the vendor submission</a>
    </p>
    <p>
        Thank you.
    </p>
</body>
</html>
