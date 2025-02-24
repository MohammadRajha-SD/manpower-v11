<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProviderType extends Model
{
    use HasFactory, HasTranslations;
    
    protected $fillable = [
        'name',
        'commission',
        'disabled'
    ];

    public $translatable = ['name'];

    public function children(){
        return $this->hasMany(Provider::class, 'provider_type_id');
    }
}