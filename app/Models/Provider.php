<?php

namespace App\Models;

use App\Traits\ImageHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Provider extends Model
{
    use HasFactory, HasTranslations;
    use ImageHandler;
    
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

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function providerType()
    {
        return $this->belongsTo(ProviderType::class, 'provider_type_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'provider_tax');
    }

    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'provider_address');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'provider_user');
    }
}

