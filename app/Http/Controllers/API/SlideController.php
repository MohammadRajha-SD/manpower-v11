<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index()
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

            return response()->json([
                'status' => 'success',
                'slides' => $newSlides,
                'message' => 'Slides retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'slides' => [],
            ], 500);
        }
    }
}
