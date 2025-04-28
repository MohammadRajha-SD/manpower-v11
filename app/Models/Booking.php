<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function booking_status()
    {
        return $this->belongsTo(BookingStatus::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getTotal(): float
    {
        $total = $this->getCouponValue();
        $total += $this->getTax();

        return $total;
    }



    
    public function getSubtotal(): float
    {
        $total = 0;
        if ($this->service) {
            $total = $this->service?->getPrice() * ($this->quantity >= 1 ? $this->quantity : 1);
        }
        return $total;
    }


    public function getTax(): float
    {
        $total = $this->getSubtotal();
        $taxValue = 0;
        $tax = $this->tax;

        if ($tax) {
            if ($tax->type == 'percent') {
                $taxValue += ($total * $tax->value / 100);
            } else {
                $taxValue += $tax->value;
            }
        }

        return $taxValue;
    }
    
    public function couponx()
    {
        return $this->belongsTo(Coupon::class, 'coupon', 'code');
    }

    public function getCouponValue(): mixed
    {
        $finalPrice = $this->getSubtotal();
        $coupon = $this->couponx;

        if ($coupon) {
            if ($coupon->discount_type === 'fixed') {
                return $finalPrice - $coupon->discount;
            } elseif ($coupon->discount_type === 'percent') {
                return $finalPrice - ($finalPrice * ($coupon->discount / 100));
            }
        }

        return $finalPrice;
    }
}
