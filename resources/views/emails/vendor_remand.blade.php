<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Remand Notification</title>
</head>
<body>
    <h2>Supplier Remand Notification</h2>
    <p>
        Dear Sir/Madam {{ $remandName }},
    </p>
    <p>
        The Supplier record for "{{ $vendorMaster->name }}" has been remanded with the following remarks:
    </p>
    <p>
        {{ $remarks }}
    </p>
    <p>
        Please review and take the necessary actions by clicking the link below:
    </p>
    <p>
        <a href="{{ $remandLink }}">Click here to review the Supplier submission</a>
    </p>
    <p>
        Thank you.
    </p>
</body>
</html>
