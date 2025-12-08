<?php
namespace App\Helpers;
use Intervention\Image\ImageManager;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

use File;


class ImageUploadHelper
{
    public static function upload(
        UploadedFile $file,
        string $folder,
        string $fileName,
        int $quality = 75,
        int $maxWidth = 500,
        int $maxHeight = 500
    ): string
    {
        // // Delete old image
        // if ($oldImagePath && File::exists(public_path($oldImagePath))) {
        //     File::delete(public_path($oldImagePath));
        // }

        // Ensure folder exists
        $uploadPath = public_path($folder);
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true);
        }

        // Generate file name
        // $filename = time() . '_' . uniqid() . '.webp';
        $filename = $fileName.'.webp';
        $imagePath = $folder . '/' . $filename;

        // Create ImageManager with GD driver
        $manager = new ImageManager(new GdDriver());

        // Read image from uploaded file
        $image = $manager->read($file->getRealPath());

        // Resize proportionally
        $image->resize($maxWidth, $maxHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Encode to WEBP with quality
        $image->toWebp($quality);

        // Save final image
        $image->save(public_path($imagePath));

        return $imagePath;
    }
}

