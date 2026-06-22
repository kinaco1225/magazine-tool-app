<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Http\Requests\Admin\StoreToolRequest;
use App\Http\Requests\Admin\UpdateToolRequest;
use Illuminate\Auth\Events\Validated;

class ToolController extends Controller
{
    public function index()
    {
        // ログイン中の会社のカテゴリーを工具数と一緒に取得
        $categories = ToolCategory::where('company_id', Auth::user()->company_id)
                        ->withCount('tools')
                        ->get();

        return view('admin.tools.index', compact('categories'));
    }

    public function category(ToolCategory $toolCategory)
    {
       $tools = Tool::where('tool_category_id', $toolCategory->id)->get();

       return view('admin.tools.category',compact('toolCategory','tools'));

    }

    public function create()
    {
        // カテゴリー選択用に会社のカテゴリー一覧を取得
        $categories = ToolCategory::where('company_id', Auth::user()->company_id)->get();

        return view('admin.tools.create', compact('categories'));
    }

    public function store(StoreToolRequest $request)
    {
        // バリデーション済みデータに company_id を付与して保存
        $tool = $request->validated();

        $tool['company_id'] = Auth::user()->company_id;
        Tool::create($tool);

        return redirect()->route('admin.tools.category', $tool['tool_category_id'])
                    ->with('success', '新しい工具を追加しました。');

    }

    public function edit(Tool $tool)
    {
        // カテゴリー選択用に会社のカテゴリー一覧を取得
        $categories = ToolCategory::where('company_id', Auth::user()->company_id)->get();

        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function update(UpdateToolRequest $request, Tool $tool)
    {
        $Validated = $request->validated();

        $tool->update($Validated);

        return redirect()->route('admin.tools.category', $tool['tool_category_id'])
                    ->with('success', '工具を編集しました。');

    }

    public function destroy(Tool $tool)
    {
        $categoryId = $tool->tool_category_id;
        $tool->delete();
        return redirect()->route('admin.tools.category', $categoryId)
                    ->with('success', '工具を削除しました。');
    }
}
