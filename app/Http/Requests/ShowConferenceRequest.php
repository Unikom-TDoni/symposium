<?php

namespace App\Http\Requests;

use App\Enums\ConferenceFilterEnum;
use App\Enums\ConferenceSortEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ShowConferenceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sort' => ['required', 'string', new Enum(ConferenceSortEnum::class)],
            'filter' => ['required', 'string', new Enum(ConferenceFilterEnum::class)],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'sort' => is_null($this->sort) ? ConferenceSortEnum::CLOSSINGNEXT->value : $this->sort,
            'filter' => is_null($this->filter) ? ConferenceFilterEnum::FUTURE->value : $this->filter,
        ]);
    }
}
