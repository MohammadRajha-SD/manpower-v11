<?php

namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => ['en' => 'Food', 'ar' => 'طعام'],
            'desc' => ['en' => 'Food category description', 'ar' => 'وصف فئة الطعام'],
            'color' => 'red',
            'order' => 1,
            'featured' => true,
            'parent_id' => null,
        ]);

        Category::create([
            'name' => ['en' => 'Services', 'ar' => 'خدمات'],
            'desc' => ['en' => 'Services category description', 'ar' => 'وصف فئة الخدمات'],
            'color' => 'blue',
            'order' => 2,
            'featured' => false,
            'parent_id' => null,
        ]);
    }
}
