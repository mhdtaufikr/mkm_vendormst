@component('mail::message')
# Customer Remand Notification

Dear Sir/Madam {{ $remandName }},

The customer {{ $customerMaster->customer_name }} has been remanded for the following reason:

**Remarks:**
{{ $remarks }}

Please review the customer details by clicking the link below:

@component('mail::button', ['url' => $remandLink])
Review Customer
@endcomponent

Thanks,<br>
@endcomponent
