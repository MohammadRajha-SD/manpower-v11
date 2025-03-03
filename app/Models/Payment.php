<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded= [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function payment_status(){
        return $this->belongsTo(PaymentStatus::class);
    }

    public function payment_method(){
        return $this->belongsTo(PaymentMethod::class);
    }
}
