@component('mail::message')
# Welcome, {{ $provider->contact_person }} – You’re Officially Part of H Power!

Dear {{ $provider->contact_person }},
We’re happy to confirm that your signed provider agreement has been received.  

You can now start receiving bookings!

We’ve attached a copy of your signed agreement for your records.  

Welcome to the H Power family — we look forward to growing together.
For anything you need, our support team is here for you during working hours.

@component('mail::button', ['url' => $downloadUrl])
Link the Agreement
@endcomponent

Best regards,  
**The H Power Team**

---

**📎 Attachment:** <a href="{{ $downloadUrl }}" download>Signed Agreement Copy</a>
@endcomponent
