<?php

namespace App\Http\Requests;

use App\Models\Submission;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubmissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'response' => [
                'required',
                Rule::in(array_keys(Submission::RESPONSES)),
            ],
            'reason' => 'nullable|max:255',
        ];
    }
}
