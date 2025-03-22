<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function plan(){
        return $this->belongsTo(Pack::class, 'pack_id');
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function provider(){
        return $this->belongsTo(Provider::class, 'provider_id');
    }
    public function scopeRemainingAmount() {
        return $this->price - $this->amount_refunded;
    }
}
