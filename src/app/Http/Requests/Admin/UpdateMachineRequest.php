<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMachineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'machine_number'    => ['nullable', 'string', 'max:255'],
            'maker'             => ['nullable', 'string', 'max:255'],
            'model'             => ['nullable', 'string', 'max:255'],
            'location'          => ['nullable', 'string', 'max:255'],
            'magazine_capacity' => ['nullable', 'integer', 'min:1'],
            'is_active'         => ['required', 'boolean'],
            'note'              => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => '機械名を入力してください',
            'magazine_capacity.integer'  => 'マガジン本数は整数で入力してください',
            'magazine_capacity.min'      => 'マガジン本数は1以上で入力してください',
        ];
    }
}
