<div style="font-family: {{ $isArabic ? 'Tajawal, sans-serif' : 'Poppins, sans-serif' }}; background-color:#f8fafc; padding:20px; direction: {{ $isArabic ? 'rtl' : 'ltr' }}; color:#333;">
    <table width="100%" style="max-width:600px; margin:auto; background:#fff; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.05);">
        <tr>
            <td style="background:linear-gradient(135deg,#4D002e 0%,#FF7C44 100%); color:#fff; padding:20px; text-align:center;">
                <h1 style="margin:0; font-size:24px; font-weight:600;">{{ $translations['header'] }}</h1>
            </td>
        </tr>
        <tr>
            <td style="padding:30px;">
                <p style="margin-bottom:20px; font-size:16px;">{!! $translations['intro'] !!}</p>
                <div style="background:#f1f5f9; border-radius:8px; padding:20px;">
                    <h3 style="color:#4D002e; margin-bottom:15px; font-size:18px; border-bottom:2px solid #e2e8f0; padding-bottom:10px;">{{ $translations['detailsHeader'] }}</h3>
                    <table style="width:100%; font-size:14px;">
                        <tr><td><strong>{{ $translations['labels']['serviceName'] }}:</strong> {{ $data['serviceName'] }}</td></tr>
                        <tr><td><strong>{{ $translations['labels']['userName'] }}:</strong> {{ $data['userName'] }}</td></tr>
                        <tr><td><strong>{{ $translations['labels']['userEmail'] }}:</strong> {{ $data['userEmail'] }}</td></tr>
                        <tr><td><strong>{{ $translations['labels']['userPhone'] }}:</strong> {{ $data['userPhone'] ?? '—' }}</td></tr>
                        <tr><td><strong>{{ $translations['labels']['providerName'] }}:</strong> {{ $data['providerName'] }}</td></tr>
                        {{-- <tr><td><strong>{{ $translations['labels']['quantity'] }}:</strong> {{ $data['quantity'] }}</td></tr> --}}
                        <tr><td><strong>{{ $translations['labels']['address'] }}:</strong> {{ $data['address'] }}</td></tr>
                        <tr><td><strong>{{ $translations['labels']['BookingData'] }}:</strong> {{ $data['BookingData'] }}</td></tr>
                        <tr><td><strong>{{ $translations['labels']['hint'] }}:</strong> {{ $data['hint'] ?? '—' }}</td></tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td style="background:#f8fafc; padding:15px; text-align:center; font-size:12px; color:#FF7C44;">
                {{ $translations['footer'] }}
            </td>
        </tr>
    </table>
</div>
