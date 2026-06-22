<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ToolCategory;
use App\Http\Requests\Admin\ToolCategoryRequest;
use Illuminate\Support\Facades\Redirect;

class ToolCategoryController extends Controller
{
    public function create()
    {
        return view('admin.tool_categories.create');
    }

    public function store(ToolCategoryRequest $request)
    {
        $toolCategory = $request->validated();

        $toolCategory['company_id'] = Auth::user()->company_id;
        ToolCategory::create($toolCategory);

        return redirect()->route('admin.tools.index')
                ->with('success','新規カテゴリーを作成しました。');
    }

    public function edit(ToolCategory $toolCategory)
    {
        return view('admin.tool_categories.edit', compact('toolCategory'));
    }

    public function update(ToolCategoryRequest $request, ToolCategory $toolCategory)
    {
        $validated = $request->validated();

        $toolCategory->update($validated);

        return redirect()->route('admin.tools.index')
                ->with('success','カテゴリー名を変更しました。');

    }

    public function destroy(ToolCategory $toolCategory)
    {
        $toolCategory->delete();
        return redirect()->route('admin.tools.index')
                ->with('success','カテゴリーを削除しました。');
    }
}
