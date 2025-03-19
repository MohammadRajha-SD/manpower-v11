<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProviderSchedule extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = ['data', 'timezone', 'provider_id'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
