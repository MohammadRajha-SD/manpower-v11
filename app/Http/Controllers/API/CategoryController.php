<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with([
            'image',
            'images',
            'parent',
            'services.images',
            'services.addresses',
            'services.provider',
            'children.services.images',
            'children.services.addresses',
            'children.services.provider',
            'children.image',
            'children.images',
        ])->get();

        $formatCategory = function ($cat) use (&$formatCategory) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'desc' => $cat->desc,
                'is_end_sub_category' => $cat->is_end_sub_category,
                'color' => $cat->color,
                'order' => $cat->order,
                'featured' => $cat->featured,
                'image_path' => $cat->image ? asset('uploads/' . $cat->image->path) : null,
                'images' => $cat->images?->map(fn($img) => asset('uploads/' . $img->path)),
                'has_parent' => !is_null($cat->parent),
                'parent' => $cat->parent ? [
                    'id' => $cat->parent->id,
                    'name' => $cat->parent->name,
                    'slug' => $cat->parent->slug,
                    'desc' => $cat->parent->desc,
                    'is_end_sub_category' => $cat->parent->is_end_sub_category,
                    'color' => $cat->parent->color,
                    'order' => $cat->parent->order,
                    'featured' => $cat->parent->featured,
                    'image_path' => $cat->parent->image ? asset('uploads/' . $cat->parent->image->path) : null,
                    'images' => $cat->parent->images?->map(fn($img) => asset('uploads/' . $img->path)),
                ] : null,
                'has_services' => $cat->services->isNotEmpty(),
                'services' => $cat->services->map(function ($srv) {
                    return [
                        'id' => $srv->id,
                        'name' => $srv->name,
                        'slug' => $srv->slug,
                        'description' => $srv->description,
                        'price' => $srv->price,
                        'discount_price' => $srv->discount_price,
                        'price_unit' => $srv->price_unit,
                        'quantity_unit' => $srv->quantity_unit,
                        'duration' => $srv->duration,
                        'featured' => $srv->featured,
                        'enable_booking' => $srv->enable_booking,
                        'end_sub_category_id' => $srv->end_sub_category_id,
                        'available' => $srv->available,
                        'addresses' => $srv->addresses?->map(fn($ad) => [
                            'address' => $ad->address,
                            'service_charge' => $ad->service_charge,
                        ]),
                        'provider' => $srv->provider ? [
                            'id' => $srv->provider->id,
                            'name' => $srv->provider->name,
                            'email' => $srv->provider->email,
                        ] : null,
                        'image_path' => $srv?->image ? asset('uploads/' . $srv?->image?->path) : null,
                        'images' => $srv?->images?->map(fn($img) => asset('uploads/' . $img->path)),
                    ];
                }),
                'has_children' => $cat->children->isNotEmpty(),
                'children' => $cat->children->map(fn($child) => $formatCategory($child)),
            ];
        };

        $newCategories = $categories->map(fn($cat) => $formatCategory($cat));

        return response()->json([
            'message' => 'All categories retrieved successfully',
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
        $category = Category::with('children', 'image', 'parent', 'services')->find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
                'categories' => [],
            ], 404);
        }

        $formatCategory = function ($cat) use (&$formatCategory) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'description' => $cat->description, // ✅ make sure column is correct
                'image' => $cat->image ? asset('uploads/' . $cat->image->path) : null,
                'is_end_sub_category' => $cat->is_end_sub_category,
                'has_children' => $cat->children->isNotEmpty(),
                'children' => $cat->children->map(fn($child) => $formatCategory($child)),
                'has_services' => $cat->services->isNotEmpty(),
                'services' => $cat->services?->map(function ($srv) {
                    return [
                        'id' => $srv->id,
                        'name' => $srv->name,
                        'slug' => $srv->slug,
                        'description' => $srv->description,
                        'price' => $srv->price,
                        'discount_price' => $srv->discount_price,
                        'price_unit' => $srv->price_unit,
                        'quantity_unit' => $srv->quantity_unit,
                        'duration' => $srv->duration,
                        'featured' => $srv->featured,
                        'enable_booking' => $srv->enable_booking,
                        'end_sub_category_id' => $srv->end_sub_category_id,
                        'available' => $srv->available,
                        'addresses' => $srv->addresses?->map(fn($ad) => [
                            'address' => $ad->address,
                            'service_charge' => $ad->service_charge,
                        ]),
                        'provider' => $srv->provider ? [
                            'id' => $srv->provider->id,
                            'name' => $srv->provider->name,
                            'email' => $srv->provider->email,
                        ] : null,
                        'image_path' => $srv?->image ? asset('uploads/' . $srv?->image?->path) : null,
                        'images' => $srv?->images?->map(fn($img) => asset('uploads/' . $img->path)),


                    ];
                }),

            ];
        };

        $result = $formatCategory($category);

        $result['parent'] = $category->parent ? [
            'id' => $category->parent->id,
            'name' => $category->parent->name,
            'slug' => $category->parent->slug,
            'description' => $category->parent->description,
            'is_end_sub_category' => $category->parent->is_end_sub_category,
            'image' => $category->parent->image ? asset('uploads/' . $category->parent->image->path) : null,
        ] : null;




        return response()->json([
            'status' => 'success',
            'message' => 'Category retrieved successfully.',
            'category' => $result,
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
