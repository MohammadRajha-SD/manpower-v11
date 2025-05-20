<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderRequest extends Model
{
    use HasFactory;

    protected $table = 'provider_requests';
    protected $guarded = [];
}
