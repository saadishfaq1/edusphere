<?php

namespace App\Http\Requests\Tenant\Academics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\SchoolClass::class);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('school_classes', 'name')->where('school_id', app('tenant')->id),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}