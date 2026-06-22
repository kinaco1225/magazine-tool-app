<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ToolCategory;
use App\Models\Company;

class ToolCategorySeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $categories = [
            'カッター',
            'ホルダー',
            'ドリル',
            'タップ',
            'エンドミル',
            'チップ',
        ];

        foreach ($categories as $name) {
            ToolCategory::create([
                'company_id' => $company->id,
                'name'       => $name,
            ]);
        }
    }
}
