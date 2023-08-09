<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAccountRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'profile_picture' => 'image|max:5000',
            'password' => 'exclude_if:password,null',
            'email' => 'email|required|unique:users,email,' . Auth::id(),
            'profile_slug' => 'alpha_dash|required_if:enable_profile,1|unique:users,profile_slug,' . Auth::id(),
            'city' => '',
            'state' => '',
            'country' => '',
            'location' => '',
            'sublocality' => '',
            'neighborhood' => '',
            'profile_intro' => '',
            'enable_profile' => '',
            'wants_notifications' => '',
            'allow_profile_contact' => '',
        ];
    }

    public function messages(): array
    {
        return [
            'profile_picture.max' => 'Profile picture cannot be larger than 5mb',
            'profile_slug.required_if' => 'You must set a Profile URL Slug to enable your Public Speaker Profile',
        ];
    }
}