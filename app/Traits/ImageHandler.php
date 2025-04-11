<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait ImageHandler
{
    /**
     * Upload an image to the specified path.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $destinationPath
     * @return string|null
     */
    public function uploadImage($image, $destinationPath)
    {
        if ($image) {
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
            return $imageName;
        }
        return null;
    }

    public function uploadMultiImage($images, $destinationPath)
    {
        $paths = [];

        if (count($images) > 0) {
            foreach ($images as $key => $image) {
                $paths[] = $this->uploadImage($image, $destinationPath);
            }
        }
        // $destinationPath . '/' . 
        return $paths;
    }

    public function updateMultiImage($images, $destinationPath, $oldPaths){

        return $this->uploadMultiImage($images, $destinationPath);
    }
    
    public function deleteImage($imagePath)
    {
        if ($imagePath && File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }
}
