<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\Company;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $makers = ['OSG', '三菱マテリアル', '京セラ', 'NACHI', 'ユニオンツール'];

        $toolsByCategory = [
            'カッター' => [
                '正面フライスカッター φ50',
                'サイドカッター φ80',
                '溝入れカッター φ40',
                'T溝カッター φ20',
                '面取りカッター 90°',
            ],
            'ホルダー' => [
                'BTホルダー BT40',
                'コレットホルダー ER32',
                'シュリンクホルダー φ12',
                'ミーリングチャックホルダー',
                'フェイスミルホルダー',
            ],
            'ドリル' => [
                'φ5.0 ドリル',
                'φ8.5 ドリル',
                'φ10.0 ドリル',
                'φ12.0 ドリル',
                'センタードリル φ3.0',
            ],
            'タップ' => [
                'M5 タップ',
                'M6 タップ',
                'M8 タップ',
                'M10 タップ',
                'M12 タップ',
            ],
            'エンドミル' => [
                'φ6 2枚刃エンドミル',
                'φ10 4枚刃エンドミル',
                'φ12 ボールエンドミル',
                'φ8 ラジアスエンドミル',
                'φ20 荒加工用エンドミル',
            ],
        ];

        $categoryCodes = [
            'カッター'  => 'CT',
            'ホルダー'  => 'HD',
            'ドリル'    => 'DR',
            'タップ'    => 'TP',
            'エンドミル' => 'EM',
        ];

        $makerIndex = 0;

        foreach ($toolsByCategory as $categoryName => $toolNames) {
            $category = ToolCategory::where('company_id', $company->id)
                ->where('name', $categoryName)
                ->first();

            foreach ($toolNames as $i => $toolName) {
                // 各カテゴリーの最後の1本（計5本）を在庫管理外にする
                $isLast = $i === count($toolNames) - 1;

                Tool::create([
                    'company_id'       => $company->id,
                    'tool_category_id' => $category?->id,
                    'name'             => $toolName,
                    'maker'            => $makers[$makerIndex % count($makers)],
                    'model'            => $categoryCodes[$categoryName] . '-' . (100 + $i),
                    'stock_quantity'   => 10,
                    'reorder_point'    => 3,
                    'manages_stock'    => ! $isLast,
                ]);

                $makerIndex++;
            }
        }
    }
}
