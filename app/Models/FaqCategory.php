<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FaqCategory extends Model
{
    use HasFactory;
    use HasTranslations;
    protected $fillable = ['name'];
    public $translatable = ['name'];

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'faq_category_id');
    }
}
