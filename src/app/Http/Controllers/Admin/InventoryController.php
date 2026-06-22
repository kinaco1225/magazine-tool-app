<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;

        $managedTools = Tool::with('toolCategory')
            ->withSum(['orders as ordered_quantity' => fn($q) => $q->where('status', 'ordered')], 'quantity')
            ->where('company_id', $companyId)
            ->where('manages_stock', true)
            ->orderBy('tool_category_id')
            ->orderBy('name')
            ->get();

        $unmanagedTools = Tool::with('toolCategory')
            ->withSum(['orders as ordered_quantity' => fn($q) => $q->where('status', 'ordered')], 'quantity')
            ->where('company_id', $companyId)
            ->where('manages_stock', false)
            ->orderBy('tool_category_id')
            ->orderBy('name')
            ->get();

        return view('admin.inventory.index', compact('managedTools', 'unmanagedTools'));
    }

    public function updateReorderPoint(Request $request, Tool $tool)
    {
        $request->validate([
            'reorder_point' => ['required', 'integer', 'min:0'],
        ]);

        $tool->update(['reorder_point' => $request->reorder_point]);

        return redirect()->route('admin.inventory.index')
            ->with('success', "「{$tool->name}」の発注点を {$request->reorder_point} に更新しました。");
    }

    public function toggleManagesStock(Request $request, Tool $tool)
    {
        abort_if($tool->company_id !== Auth::user()->company_id, 403);

        $request->validate(['manages_stock' => ['required', 'boolean']]);

        $newValue = (bool) $request->manages_stock;
        $tool->update(['manages_stock' => $newValue]);
        $label = $newValue ? '在庫管理対象' : '在庫管理外';

        return back()->with('success', "「{$tool->name}」を{$label}に変更しました。");
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'type'     => ['required', 'in:add,use,set'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $quantity = (int) $request->quantity;
        $message  = '';

        switch ($request->type) {
            case 'add':
                $tool->increment('stock_quantity', $quantity);
                $message = "「{$tool->name}」の在庫を {$quantity} 追加しました。";
                break;
            case 'use':
                $newStock = max(0, $tool->stock_quantity - $quantity);
                $tool->update(['stock_quantity' => $newStock]);
                $message = "「{$tool->name}」の在庫を {$quantity} 使用しました。";
                break;
            case 'set':
                $tool->update(['stock_quantity' => $quantity]);
                $message = "「{$tool->name}」の在庫を {$quantity} に更新しました。";
                break;
        }

        return redirect()->route('admin.inventory.index')->with('success', $message);
    }
}
