<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => '',
            'message' => 'required',
            'email' => 'required|email',
            'g-recaptcha-response' => 'required'
        ];
    }
}
