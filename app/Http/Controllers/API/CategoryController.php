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

        $newCategories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'desc' => $category->desc,
                'color' => $category->color,
                'order' => $category->order,
                'featured' => $category->featured,
                'image_path' => $category->image ? asset('uploads/' . $category->image?->path) : null,
                'images' => $category->images?->map(function ($img) {
                    return asset('uploads/' . $img->path);
                }),
                'has_parent' =>  $category->parent ? true : false,
                'has_services' =>  $category->services->isEmpty() ? false : true,
                'services' =>  $category->services?->map(function ($srv) {
                    return [
                        'id' => $srv->id,
                        'name' => $srv->name,
                        'address' => $srv->address,
                        'category' => $srv->category,
                        'provider' => $srv->provider ? [
                            'id' => $srv->provider->id,
                            'name' => $srv->provider->name,
                            'email' => $srv->provider->email,
                        ] : null,
                        'description' => $srv->description,
                        'discount_price' => $srv->discount_price,
                        'price' => $srv->price,
                        'price_unit' => $srv->price_unit,
                        'quantity_unit' => $srv->quantity_unit,
                        'duration' => $srv->duration,
                        'featured' => $srv->featured,
                        'enable_booking' => $srv->enable_booking,
                        'available' => $srv->available,
                        'image_path' => $srv?->image ? asset('uploads/' . $srv?->image?->path) : null,
                        'images' => $srv?->images?->map(function ($img) {
                            return asset('uploads/' . $img->path);
                        }),
                    ];
                }),

                'parent' =>  $category->parent ? [
                    'id' => $category->parent->id,
                    'name' => $category->parent->name,
                    'desc' => $category->parent->desc,
                    'color' => $category->parent->color,
                    'order' => $category->parent->order,
                    'featured' => $category->parent->featured,
                    'image_path' => $category->parent->image ? asset('uploads/' . $category->parent->image?->path) : null,
                    'images' => $category->parent->images?->map(function ($img) {
                        return asset('uploads/' . $img->path);
                    }),

                ] : null,

                'has_children' =>  $category->children->isEmpty() ? false : true,
                'children' =>  $category->children?->map(function ($chld) {
                    return [
                        'id' => $chld->id,
                        'name' => $chld->name,
                        'desc' => $chld->desc,
                        'color' => $chld->color,
                        'order' => $chld->order,
                        'featured' => $chld->featured,
                        'image_path' => $chld?->image ? asset('uploads/' . $chld?->image?->path) : null,
                        'images' => $chld?->images?->map(function ($img) {
                            return asset('uploads/' . $img->path);
                        }),
                        'parent' =>  $chld->parent ? [
                            'id' => $chld->parent->id,
                            'name' => $chld->parent->name,
                            'desc' => $chld->parent->desc,
                            'color' => $chld->parent->color,
                            'order' => $chld->parent->order,
                            'featured' => $chld->parent->featured,
                            'image_path' => $chld->parent->image ? asset('uploads/' . $chld->parent->image?->path) : null,
                            'images' => $chld->parent->images?->map(function ($img) {
                                return asset('uploads/' . $img->path);
                            }),
                        ] : null,
                        'has_services' =>  $chld->services->isEmpty() ? false : true,
                        'services' =>  $chld->services?->map(function ($srv) {
                            return [
                                'id' => $srv->id,
                                'name' => $srv->name,
                                'category' => $srv->category,
                                'address' => $srv->address,
                                'provider' => $srv->provider ? [
                                    'id' => $srv->provider->id,
                                    'name' => $srv->provider->name,
                                    'email' => $srv->provider->email,
                                ] : null,
                                'description' => $srv->description,
                                'discount_price' => $srv->discount_price,
                                'price' => $srv->price,
                                'price_unit' => $srv->price_unit,
                                'quantity_unit' => $srv->quantity_unit,
                                'duration' => $srv->duration,
                                'featured' => $srv->featured,
                                'enable_booking' => $srv->enable_booking,
                                'available' => $srv->available,
                                'image_path' => $srv?->image ? asset('uploads/' . $srv?->image?->path) : null,
                                'images' => $srv?->images?->map(function ($img) {
                                    return asset('uploads/' . $img->path);
                                }),
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json([
            'message' => 'All categories retreived successfully',
            'data' => $newCategories,
            'status' => 'success',
        ], 200);
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
