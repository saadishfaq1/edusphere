<?php

namespace App\Http\Requests\Tenant\Academics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('school_class'));
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('school_classes', 'name')
                    ->where('school_id', app('tenant')->id)
                    ->ignore($this->route('school_class')),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}