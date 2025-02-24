<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;

class DeleteImageController extends Controller
{
    public function deleteImageFunc($id)
    {
        $image = Image::findOrFail($id);
        $image->deleteImage($image->path);
        $image->delete();

        return response()->json(['success' => true, 'message' => __('lang.deleted_successfully', ['operator' => __('lang.image')])]);
    }
}
