<!DOCTYPE html>
<html>
<head>
    <title>Customer Form Completion Notification</title>
</head>
<body>
    <h1>Customer Form Completion Notification</h1>
    <p>Dear {{ $userName }},</p>
    <p>The customer form for {{ $customerMaster->customer_name }} has been successfully completed.</p>
    <p>Thanks,<br>
    {{ config('app.name') }}</p>
</body>
</html>
