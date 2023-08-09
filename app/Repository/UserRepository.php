<?php

namespace App\Repository;

use App\Models\User;
use App\ApiResources\Me;
use App\Handler\FileService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class UserRepository extends Repository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function createSocialUser($service)
    {
        $serviceUser = Socialite::driver($service)->user();

        $user = $this->getExistingUser($serviceUser, $service);

        if (!$user)
            $user = $this->model->create([
                'name' => $serviceUser->getName(),
                'email' => $serviceUser->getEmail(),
            ]);

        if (!$user->hasSocialLinked($service))
            $user->social()->create([
                'social_id' => $serviceUser->getId(),
                'service' => $service,
            ]);

        return $user;
    }

    private function getExistingUser($serviceUser, $service)
    {
        return $this->model->where('email', $serviceUser->getEmail())
            ->where('social', function ($query) use ($serviceUser, $service) {
                $query->where('social_id', $serviceUser->getId())
                    ->where('service', $service);
        })->first();
    }

    public function getFeaturedUser($limit)
    {
        $faturedUser = $this->model->whereFeatured();
        return $faturedUser->limit($limit)->get();
    }

    public function getUserApiResource()
    {
        return new Me($this->getAuthUser());
    }

    public function deleteAuthUser() 
    {
        $this->getAuthUser()->delete();
    }
    
    public function updateAuthUser($data) 
    {
        $user = $this->getAuthUser();
        $user->update($data);
        if(isset($data['profile_picture']))
            $this->updateUserProfilePicture($user, $data['profile_picture']);
    }

    private function updateUserProfilePicture($user, $profilePictureFile) 
    {
        $fileHandler = new FileService();
        $fileHandler->updateUserProfilePicture($user->profile_picture, $profilePictureFile);
        $user->updateProfilePicture($profilePictureFile->hashName());
    }

    public function exportUserData($fileName)
    {
        $fileHandler = new FileService();
        $user = $this->getAuthUser();
        $user->load('talks.revisions');
        $fileHandler->createUserDataJsonFile($fileName, ['talks' => $user->talks->sortByTitle()->toArray()]);
    }

    public function getAuthUser()
    {
        return Auth::user();
    }
}