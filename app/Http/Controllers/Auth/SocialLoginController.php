<?php

namespace App\Http\Controllers\Auth;

use App\Repository\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect($service)
    {
        return Socialite::driver($service)->redirect();
    }

    public function callback($service, UserRepository $userRepository)
    {
        Auth::login($userRepository->createSocialUser($service));
        return redirect()->route('dashboard');
    }
}