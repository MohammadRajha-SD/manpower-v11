<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = ['question', 'answer', 'faq_category_id'];

    public $translatable = ['question', 'answer'];


    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

}
