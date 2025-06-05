@component('mail::message')
# Welcome aboard, {{ $name }}!

Thank you for registering as a trusted service provider with **H Power**.  
We’re excited to have you join our growing network.

Just one last step before you can start receiving bookings!

To activate your account, please review and sign your provider agreement by clicking the button below:

@component('mail::button', ['url' => $agreementLink])
Review & Sign My Agreement
@endcomponent

Best regards,  
**The H Power Team**
@endcomponent
