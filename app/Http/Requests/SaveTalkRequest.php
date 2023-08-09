<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveTalkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'author_id' => 'required',
            'title' => 'required',
            'type' => 'required',
            'level' => 'required',
            'length' => 'required|integer|min:0',
            'slides' => 'nullable|url',
            'organizer_notes' => 'required',
            'description' => 'required',
            'public' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'slides.url' => 'Slides URL must contain a valid URL',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'author_id' => auth()->user()->id,
        ]);
    }
}
