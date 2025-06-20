<?php

namespace App\Http\Controllers\AAPI;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Slide;
use App\Models\Service;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function dashboard()
    {
        $homeServices = $this->homeServices();

        return response()->json([
            'data' => [
                'featuredServices' => $homeServices['featuredServices'],
                'newServices' => $homeServices['newServices'],
                'slides' => $this->slides(),
                'categories' => $this->allCategories(),
            ],
            'success' => true,
            'message' => 'All Data retrieved successfully.',
        ]);
    }


    private function slides()
    {
        try {
            $slides = Slide::with('image')->where('status', 1)->get();

            $newSlides = $slides->map(function ($slide) {
                return [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'text' => $slide->text,
                    'desc' => $slide->desc,
                    'status' => $slide->status,
                    'image_path' => asset('uploads/' . $slide->image->path) ?? null,
                ];
            });

            return $newSlides;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function homeServices()
    {
        // Base query for services
        $baseQuery = Service::query()
            ->where('available', 1)
            ->whereHas('provider', function ($providerQuery) {
                $providerQuery->where('accepted', 1)->where('available', 1);
            })
            ->with(['provider', 'categories', 'images', 'image']);

        // Get new services (latest created)
        $newServices = (clone $baseQuery)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(fn($slide) => $this->transformService($slide));

        // Get featured services
        $featuredServices = (clone $baseQuery)
            ->where('featured', 1)
            ->limit(6)
            ->get()
            ->map(fn($slide) => $this->transformService($slide));

        return [
            'newServices' => $newServices,
            'featuredServices' => $featuredServices,
        ];
    }


    private function transformService($slide)
    {
        return [
            'id' => $slide->id,
            'address' => $slide->address,
            'category_id' => $slide->category_id,
            'provider_id' => $slide->provider_id,
            'name' => $slide->name,
            'description' => $slide->description,
            'discount_price' => $slide->discount_price,
            'price' => $slide->price,
            'quantity_unit' => $slide->quantity_unit,
            'duration' => $slide->duration,
            'featured' => $slide->featured,
            'terms' => $slide->terms,
            'qty_limit' => $slide->qty_limit,
            'enable_booking' => $slide->enable_booking,
            'available' => $slide->available,
            'bookings' => $slide->bookings?->filter(fn($b) => $b->booking_status_id != 4 && $b->booking_status_id != 3)?->map(fn($b) => [
                'id' => $b->id,
                'start_at' => $b->start_at,
                'ends_at' => $b->ends_at,
            ]),
            'created_at' => $slide->created_at,
            'provider' => [
                'name' => $slide->provider?->name,
                'email' => $slide->provider?->email,
                'description' => $slide->provider?->description,
                'schedules' => $slide->provider?->getSchedules(),
                'phone_number' => $slide->provider?->phone_number,
                'mobile_number' => $slide->provider?->mobile_number,
                'availability_range' => $slide->provider?->availability_range,
                'available' => $slide->provider?->available,
                'accepted' => $slide->provider?->accepted,
                'featured' => $slide->provider?->featured,
                'created_at' => $slide->provider?->created_at,
            ],
            'images' => $slide->images?->map(fn($img) => asset('uploads/' . $img->path))->toArray(),
        ];
    }


    public function allCategories()
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
                'has_parent' => $category->parent ? true : false,
                'has_services' => $category->services->isEmpty() ? false : true,
                'services' => $category->services?->map(function ($srv) {
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

        return $newCategories;
    }

    public function parentCategories()
    {
        $categories = Category::whereNull('parent_id')->get();

        return response()->json([
            'success' => 'success',
            'message' => 'All Categories retrieved successfully.',
            'data' => $categories,
        ], 200);
    }

    public function subCategories($id)
    {
        if (!Category::where('id', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'data' => [],
            ], 404);
        }

        $c = Category::with('children', 'image', 'images', 'services.provider', 'services.reviews')->where('id', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully.',
            'data' => [
                "id" => $c->id,
                "name" => $c->name,
                "desc" => $c->desc,
                "color" => $c->color,
                "order" => $c->order,
                "featured" => $c->featured,
                "parent_id" => $c->parent_id,
                'image_path' => $c->image ? asset('uploads/' . $c->image?->path) : null,
                'images' => $c->images?->map(function ($img) {
                    return asset('uploads/' . $img->path);
                }),
                'services' => $c->services?->map(function ($srv) {
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
                        'rate' => $srv->reviews()->sum('rate') ?? 0,
                        'available' => $srv->available,
                        'image_path' => $srv?->image ? asset('uploads/' . $srv?->image?->path) : null,
                        'images' => $srv?->images?->map(function ($img) {
                            return asset('uploads/' . $img->path);
                        }),
                    ];
                }),
                "children" => $c->children?->isEmpty() ? [] : $c?->children->map(function ($child) {
                    return [
                        "id" => $child->id,
                        "name" => $child->name,
                        "desc" => $child->desc,
                        "color" => $child->color,
                        "order" => $child->order,
                        "featured" => $child->featured == 1 ? true : false,
                        "parent_id" => $child->parent_id,
                        'image_path' => $child->image ? asset('uploads/' . $child->image?->path) : null,
                        'images' => $child->images?->map(function ($img) {
                            return asset('uploads/' . $img->path);
                        }),
                        'services' => $child->services?->map(function ($srv) {
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
                                'rate' => $srv->reviews()->sum('rate') ?? 0,
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
                        "children" => []
                    ];
                }),
            ],
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

    public function searchCategories(Request $request)
    {
        $keywords = $request->input('keywords');
        $categoryIds = $request->input('categories');

        $query = Service::query();

        // Filter by keywords (name or description)
        if ($keywords) {
            $query->where(function ($q) use ($keywords) {
                $q->where('name', 'LIKE', "%{$keywords}%")
                    ->orWhere('description', 'LIKE', "%{$keywords}%");
            });
        }

        // Filter by category IDs if provided
        if (!empty($categoryIds) && is_array($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        $services = $query->with(['provider', 'images', 'image'])->get();

        // Transform response
        $data = $services->map(function ($srv) {
            return [
                'id' => $srv->id,
                'name' => $srv->name,
                'address' => $srv->address,
                'category' => $srv->category ? [
                    'id' => $srv->category->id,
                    'name' => $srv->category->name,
                    'desc' => $srv->category->desc,
                    'color' => $srv->category->color,
                    'order' => $srv->category->order,
                    'featured' => $srv->category->featured,
                    'parent_id' => $srv->category->parent_id,
                ] : null,
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
                'rate' => $srv->reviews()->sum('rate') ?? 0,
                'available' => $srv->available,
                'image_path' => $srv->image ? asset('uploads/' . $srv->image->path) : null,
                'images' => $srv->images?->map(function ($img) {
                    return asset('uploads/' . $img->path);
                }),
            ];
        });

        return response()->json([
            "success" => true,
            "data" => $data,
            "message" => "success",
        ]);
    }
}
