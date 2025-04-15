<p>Dear {{ $booking->user->name }},</p>

<p>Your booking has been cancelled successfully.</p>

<p><strong>Service:</strong> {{ $service->name }}</p>
<p><strong>Provider:</strong> {{ $provider->name }}</p>

@if($reason)
<p><strong>Reason for cancellation:</strong> {{ $reason }}</p>
@endif

<p>If you have any questions, feel free to contact us.</p>

<p>Thank you,<br>HPower Team</p>
