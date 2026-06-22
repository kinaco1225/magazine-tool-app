<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MagazinePot;
use App\Models\StandbySet;
use App\Models\Tool;
use App\Models\ToolCategory;

class StandbyController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;

        $sets = StandbySet::where('company_id', $companyId)
            ->with(['machine', 'tools.toolCategory'])
            ->withCount('tools')
            ->orderByDesc('created_at')
            ->get();

        $machines = Machine::where('company_id', $companyId)
            ->whereNotNull('magazine_capacity')
            ->where('magazine_capacity', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'machine_number', 'magazine_capacity']);

        return view('admin.standby.index', compact('sets', 'machines'));
    }

    public function create()
    {
        $companyId = Auth::user()->company_id;

        $machines   = Machine::where('company_id', $companyId)
            ->whereNotNull('magazine_capacity')
            ->orderBy('name')
            ->get(['id', 'name', 'machine_number']);

        $categories = ToolCategory::where('company_id', $companyId)->orderBy('name')->get(['id', 'name']);
        $tools      = Tool::where('company_id', $companyId)->orderBy('name')->get(['id', 'tool_category_id', 'name', 'maker']);

        $toolsByCategoryJson = $tools
            ->groupBy('tool_category_id')
            ->map(fn($g) => $g->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'maker' => $t->maker])->values());

        return view('admin.standby.form', compact('machines', 'categories', 'toolsByCategoryJson'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => ['nullable', 'integer', 'exists:machines,id'],
            'tool_ids'   => ['required', 'array', 'min:1'],
            'tool_ids.*' => ['required', 'integer', 'exists:tools,id'],
        ]);

        if (! empty($validated['machine_id'])) {
            $machine = Machine::findOrFail($validated['machine_id']);
            abort_if($machine->company_id !== Auth::user()->company_id, 403);
        }

        $set = StandbySet::create([
            'company_id' => Auth::user()->company_id,
            'machine_id' => $validated['machine_id'] ?? null,
        ]);
        $set->tools()->attach(array_unique($validated['tool_ids']));

        return redirect()->route('admin.standby.index')
            ->with('success', '待機工具セットを登録しました。');
    }

    public function destroy(StandbySet $set)
    {
        abort_if($set->company_id !== Auth::user()->company_id, 403);
        $set->delete();

        return redirect()->route('admin.standby.index')
            ->with('success', '待機工具セットを削除しました。');
    }

    // 待機セットをポットに割り当て
    public function assign(Request $request, StandbySet $set)
    {
        abort_if($set->company_id !== Auth::user()->company_id, 403);

        $validated = $request->validate([
            'machine_id' => ['required', 'integer', 'exists:machines,id'],
            'pot_number' => ['required', 'integer', 'min:1'],
        ]);

        $machine = Machine::findOrFail($validated['machine_id']);
        abort_if($machine->company_id !== Auth::user()->company_id, 403);

        if ($validated['pot_number'] > $machine->magazine_capacity) {
            return back()->with('error', "ポット番号が範囲外です（最大 {$machine->magazine_capacity}）。");
        }

        $existing = $machine->magazinePots()->where('pot_number', $validated['pot_number'])->first();
        if ($existing) {
            return back()->with('error', "ポット {$validated['pot_number']} はすでに使用中または使用不可です。");
        }

        $toolIds = $set->tools()->pluck('tools.id')->toArray();

        $pot = MagazinePot::create([
            'machine_id' => $machine->id,
            'pot_number' => $validated['pot_number'],
        ]);
        $pot->tools()->attach($toolIds);

        $set->delete();

        $machine->update([
            'available_spots' => $machine->magazine_capacity - $machine->magazinePots()->count(),
        ]);

        return redirect()->route('admin.magazines.show', $machine)
            ->with('success', "{$machine->name} のポット {$validated['pot_number']} に待機工具を割り当てました。");
    }
}
