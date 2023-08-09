<?php

namespace App\Http\Requests;

class UpdateConferenceRequest extends SaveConferenceRequest
{
    public function rules()
    {
        $rules = array_merge(parent::rules(),  [
            'id' => ['required'],
        ]);

        if (auth()->user()->isAdmin())
            $rules['is_approved'] = 'required';
    
        return $rules;
    }

    protected function prepareForValidation()
    {
        parent::prepareForValidation();
        $this->merge(['id' => $this->route('conference')]);
        if ($this->author_id !== auth()->id() && !auth()->user()->isAdmin()) return redirect('/');
    }
}