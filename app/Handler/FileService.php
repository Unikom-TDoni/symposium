<?php

namespace App\Handler;

use App\Models\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function updateUserProfilePicture($profilePictureName, $inputPictureFile) 
    { 
        $generateProfilePicture = $this->createProfilePictureFile($inputPictureFile);

        Storage::delete([
            User::PROFILE_PICTURE_THUMB_PATH . $profilePictureName,
            User::PROFILE_PICTURE_HIRES_PATH . $profilePictureName,
        ]);

        Storage::put(User::PROFILE_PICTURE_THUMB_PATH . 
            $inputPictureFile->hashName(), $generateProfilePicture['thumbImage']->stream());
        Storage::put(User::PROFILE_PICTURE_HIRES_PATH . 
            $inputPictureFile->hashName(), $generateProfilePicture['hiresImage']->stream());
    }

    private function createProfilePictureFile($fileImage) 
    {
        $thumbImageSize = 250;
        $hiresImageSize = 1250;
        
        $thumbImage = Image::make($fileImage->getRealPath())
            ->fit($thumbImageSize, $thumbImageSize);

        $hiresImage = Image::make($fileImage->getRealPath())
            ->fit($hiresImageSize, $hiresImageSize, function ($constraint) { $constraint->upsize(); });

        return [
            'hiresImage' => $hiresImage,
            'thumbImage' => $thumbImage,
        ];
    }

    public function createUserDataJsonFile($name, $data)
    {
        Storage::disk('local')->put($name, json_encode($data));
    }
}