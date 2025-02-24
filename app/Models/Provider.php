<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Provider extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'category_id',
        'provider_type_id',
        'description',
        'phone_number',
        'mobile_number',
        'availability_range',
        'available',
        'featured',
        'accepted'
    ];

    public $translatable = ['name', 'description'];



    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function providerType()
    {
        return $this->belongsTo(ProviderType::class, 'provider_type_id');
    }
}
