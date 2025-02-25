<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProviderSchedule extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'day',
        'start_at',
        'end_at',
        'data',
        'provider_id',
    ];
    
    public $translatable = ['data'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
