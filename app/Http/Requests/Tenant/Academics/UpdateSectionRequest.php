<?php

namespace App\Http\Requests\Tenant\Academics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('section'));
    }

    public function rules(): array
    {
        return [
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'name' => [
                'required', 'string', 'max:50',
                Rule::unique('sections', 'name')
                    ->where('school_class_id', $this->input('school_class_id'))
                    ->ignore($this->route('section')),
            ],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ];
    }
}