<?php

namespace App\Http\Requests;

use App\Models\ConferenceIssue;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SaveConferenceIssueRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reason' => [
                'required',
                Rule::in(ConferenceIssue::REASONS),
            ],
            'note' => 'required',
            'user_id' => 'required',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::id(),
        ]);
    }
}