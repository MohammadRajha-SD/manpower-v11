<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $locale;

    public function __construct($data, $locale = 'en')
    {
        $this->data = $data;
        $this->locale = $locale;
    }

    public function build()
    {
        $isArabic = $this->locale === 'ar';

        $translations = [
            'subject' => $isArabic
                ? "طلب حجز جديد: {$this->data['serviceName']}"
                : "New Booking Request: {$this->data['serviceName']}",
            'header' => $isArabic ? "تأكيد الحجز الجديد" : "New Booking Confirmation",
            'intro' => $isArabic
                ? "تم تقديم طلب حجز جديد للخدمة التالية: <span style='color:#1a5276;'>{$this->data['serviceName']}</span>"
                : "A new booking request has been submitted for the following service: <span style='color:#1a5276;'>{$this->data['serviceName']}</span>",
            'detailsHeader' => $isArabic ? "تفاصيل الحجز" : "Booking Details",
            'labels' => [
                'serviceName' => $isArabic ? "اسم الخدمة" : "Service Name",
                'userName' => $isArabic ? "اسم العميل" : "User Name",
                'userEmail' => $isArabic ? "البريد الإلكتروني" : "User Email",
                'userPhone' => $isArabic ? "رقم هاتف العميل" : "User Phone",
                'providerName' => $isArabic ? "اسم المزود" : "Provider Name",
                'quantity' => $isArabic ? "الكمية" : "Quantity",
                'address' => $isArabic ? "العنوان" : "Address",
                'hint' => $isArabic ? "ملاحظات إضافية" : "Additional Notes",
                'BookingData' => $isArabic ? "تاريخ الخدمة" : "Service Date",
            ],
            'footer' => $isArabic
                ? "2025 HPower. جميع الحقوق محفوظة."
                : "2025 HPower. All rights reserved.",
        ];

        return $this->subject($translations['subject'])
            ->view('emails.booking-success')
            ->with([
                'data' => $this->data,
                'translations' => $translations,
                'isArabic' => $isArabic,
            ]);
    }
}
