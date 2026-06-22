<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreToolRequest extends FormRequest
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
            'name'             => ['required', 'string', 'max:255'],
            'tool_category_id' => ['required', 'integer', 'exists:tool_categories,id'],
            'maker'          => ['required', 'string', 'max:255'],
            'model'          => ['required', 'string', 'max:255'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'reorder_point'  => ['nullable', 'integer', 'min:0'],
            'note'           => ['nullable', 'string'],
            'manages_stock'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => '工具名を入力してください',
            'tool_category_id.required' => 'カテゴリーを選択してください',
            'tool_category_id.exists'   => '選択されたカテゴリーが存在しません',
            'maker.required'            => 'メーカーを入力してください',
            'model.required'            => '型式を入力してください',
        ];
    }
}
