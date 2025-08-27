<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // $address = $request->address;
        // $address = null;

        $categories = Category::with([
            'images',
            'image',
            'parent',
            'children',
        ])->get();

        // use ($address)
        $newCategories = $categories->map(function ($category) {
            // $services = $address
            //     ? $category->services()->whereHas('addresses', function ($q) use ($address) {
            //         $q->where('address', $address);
            //     })->get()
            //     : $category->services;

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
                'has_parent' => $category->parent ? true : false,
                'has_services' => $category->services->isEmpty() ? false : true,
                'services' => $category->services?->map(function ($srv) {
                    return [
                        'id' => $srv->id,
                        'addresses' => $srv->addresses ? $srv->addresses->map(function ($ad) {
                            return [
                                'address' => $ad->address,
                                'service_charge' => $ad->service_charge,
                            ];
                        }) : [],
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

                'parent' => $category->parent ? [
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

                'has_children' => $category->children->isEmpty() ? false : true,
                'children' => $category->children?->map(function ($chld) {
                    // use ($address)
                    // $services = $address
                    //     ? $chld->services()->whereHas('addresses', function ($q) use ($address) {
                    //         $q->where('address', $address);
                    //     })->get()
                    //     : $chld->services;
    
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
                        'parent' => $chld->parent ? [
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
                        'has_services' => $chld->services->isEmpty() ? false : true,
                        'services' => $chld->services?->map(function ($srv) {
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
                                'addresses' => $srv->addresses ? $srv->addresses->map(function ($ad) {
                                    return [
                                        'address' => $ad->address,
                                        'service_charge' => $ad->service_charge,
                                    ];
                                }) : [],
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
                'description' => $cat->description, // ✅ make sure column is correct
                'image' => $cat->image ? asset('uploads/' . $cat->image->path) : null,
                'is_end_sub_category' => $cat->is_end_sub_category,
                'has_children' => $cat->children->isNotEmpty(),
                'children' => $cat->children->map(fn($child) => $formatCategory($child)),
                'services' => $cat->services?->map(function ($srv) {
                    return [
                        'id' => $srv->id,
                        'name' => $srv->name,
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
            'description' => $category->parent->description,
            'is_end_sub_category' => $category->parent->is_end_sub_category,
            'image' => $category->parent->image ? asset('uploads/' . $category->parent->image->path) : null,
        ] : null;

        // $result['services'] = $category->services->map(function ($service) {
        //     return [
        //         'id' => $service->id,
        //         'name' => $service->name,
        //         'description' => $service->description,
        //         'price' => $service->price,
        //         'discount_price' => $service->discount_price,
        //         'images' => $service->images ? $service->images->map(fn($img) => asset('uploads/' . $img->path))->toArray() : [],
        //     ];
        // });


        return response()->json([
            'status' => 'success',
            'message' => 'Category retrieved successfully.',
            'category' => $result,
        ]);
    }

    public function subCategoriesOld($id)
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
