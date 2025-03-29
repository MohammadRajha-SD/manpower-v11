<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with([
            'images',
            'image',
            'parent',
            'children',
        ])->get();

        return response()->json($categories, 200);
    }

    public function parentCategories()
    {
        $categories = Category::whereNull('parent_id')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'All Categories retrieved successfully.',
            'categories' => $categories,
        ], 200);
    }

    public function subCategories($id)
    {
        if (!Category::where('id', $id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
                'categories' => [],
            ], 404);
        }

        $subCategories = Category::with('children')->where('id', $id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Categories retreived successfully.',
            'category' => $subCategories,
        ]);
    }

    public function subCategoriesWithDetails($id)
    {
        $category = Category::with('children')->find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
                'category' => [],
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Category retreived successfully.',
            'category' => $category,
        ]);
    }
}
