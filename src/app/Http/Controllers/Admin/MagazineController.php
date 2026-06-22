<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MagazinePot;
use App\Models\StandbySet;
use App\Models\Tool;
use App\Models\ToolCategory;

class MagazineController extends Controller
{
    public function index()
    {
        $machines = Machine::where('company_id', Auth::user()->company_id)
            ->whereNotNull('magazine_capacity')
            ->where('magazine_capacity', '>', 0)
            ->orderBy('machine_number')
            ->get();

        return view('admin.magazines.index', compact('machines'));
    }

    public function show(Machine $machine)
    {
        abort_if($machine->company_id !== Auth::user()->company_id, 403);

        $pots = $machine->magazinePots()
            ->withCount('tools')
            ->with('tools')
            ->get()
            ->keyBy('pot_number');

        return view('admin.magazines.show', compact('machine', 'pots'));
    }

    // ポット登録フォーム
    public function createPot(Machine $machine, int $potNumber)
    {
        abort_if($machine->company_id !== Auth::user()->company_id, 403);
        abort_if($potNumber < 1 || $potNumber > $machine->magazine_capacity, 404);

        $existing = $machine->magazinePots()->where('pot_number', $potNumber)->first();
        if ($existing) {
            return redirect()->route('admin.magazines.editPot', [$machine, $existing]);
        }

        [$categories, $toolsByCategoryJson, $standbySets] = $this->formData();

        return view('admin.magazines.pot_form', compact('machine', 'potNumber', 'categories', 'toolsByCategoryJson', 'standbySets'));
    }

    // ポット登録
    public function storePot(Request $request, Machine $machine)
    {
        abort_if($machine->company_id !== Auth::user()->company_id, 403);

        $validated = $request->validate([
            'pot_number'      => [
                'required', 'integer', 'min:1', 'max:' . $machine->magazine_capacity,
                Rule::unique('magazine_pots')->where('machine_id', $machine->id),
            ],
            'tool_ids'        => ['required', 'array', 'min:1'],
            'tool_ids.*'      => ['required', 'integer', 'exists:tools,id'],
            'from_standby_id' => ['nullable', 'integer', 'exists:standby_sets,id'],
        ]);

        $pot = MagazinePot::create([
            'machine_id' => $machine->id,
            'pot_number' => $validated['pot_number'],
        ]);
        $pot->tools()->attach(array_unique($validated['tool_ids']));

        // 待機セットから使用した場合は削除
        if (! empty($validated['from_standby_id'])) {
            $set = StandbySet::find($validated['from_standby_id']);
            if ($set && $set->company_id === $machine->company_id) {
                $set->delete();
            }
        }

        $this->syncAvailableSpots($machine);

        return redirect()->route('admin.magazines.show', $machine)
            ->with('success', "ポット {$validated['pot_number']} に工具を登録しました。");
    }

    // ポット詳細
    public function showPot(Machine $machine, MagazinePot $pot)
    {
        abort_if($machine->company_id !== Auth::user()->company_id, 403);

        $pot->load('tools.toolCategory');

        return view('admin.magazines.pot_show', compact('machine', 'pot'));
    }

    // ポット編集フォーム
    public function editPot(Machine $machine, MagazinePot $pot)
    {
        abort_if($machine->company_id !== Auth::user()->company_id, 403);

        $pot->load('tools.toolCategory');
        $potNumber    = $pot->pot_number;
        $currentTools = $pot->tools->map(fn($t) => [
            'category_id' => $t->tool_category_id,
            'id'          => $t->id,
        ])->values();

        [$categories, $toolsByCategoryJson, $standbySets] = $this->formData();

        return view('admin.magazines.pot_form', compact(
            'machine', 'pot', 'potNumber', 'categories', 'toolsByCategoryJson', 'currentTools', 'standbySets'
        ));
    }

    // ポット更新
    public function updatePot(Request $request, Machine $machine, MagazinePot $pot)
    {
        abort_if($machine->company_id !== Auth::user()->company_id, 403);

        $validated = $request->validate([
            'tool_ids'   => ['required', 'array', 'min:1'],
            'tool_ids.*' => ['required', 'integer', 'exists:tools,id'],
        ]);

        $pot->tools()->sync(array_unique($validated['tool_ids']));

        return redirect()->route('admin.magazines.show', $machine)
            ->with('success', "ポット {$pot->pot_number} の工具を更新しました。");
    }

    // ポット取り外し → 待機セットとして保管
    public function destroyPot(Machine $machine, MagazinePot $pot)
    {
        abort_if($machine->company_id !== Auth::user()->company_id, 403);

        $potNumber = $pot->pot_number;
        $toolIds   = $pot->tools()->pluck('tools.id')->toArray();

        if (! empty($toolIds)) {
            $set = StandbySet::create([
                'company_id' => $machine->company_id,
                'machine_id' => $machine->id,
            ]);
            $set->tools()->attach($toolIds);
        }

        $pot->delete();
        $this->syncAvailableSpots($machine);

        return redirect()->route('admin.magazines.show', $machine)
            ->with('success', "ポット {$potNumber} の工具を待機工具として保管しました。");
    }

    // ポット取り外し（待機に移さずそのまま外す）
    public function destroyPotWithTools(Machine $machine, MagazinePot $pot)
    {
        abort_if($machine->company_id !== Auth::user()->company_id, 403);

        $potNumber = $pot->pot_number;
        $pot->delete();

        $this->syncAvailableSpots($machine);

        return redirect()->route('admin.magazines.show', $machine)
            ->with('success', "ポット {$potNumber} の工具を取り外しました。");
    }

    // ポットを使用不可にする
    public function disablePot(Machine $machine, int $potNumber)
    {
        abort_if($machine->company_id !== Auth::user()->company_id, 403);
        abort_if($potNumber < 1 || $potNumber > $machine->magazine_capacity, 404);

        $pot = $machine->magazinePots()->where('pot_number', $potNumber)->first();

        if ($pot && $pot->tools()->count() > 0) {
            return back()->with('error', "ポット {$potNumber} に工具が登録されています。先に取り外してください。");
        }

        if ($pot) {
            $pot->update(['is_disabled' => true]);
        } else {
            MagazinePot::create([
                'machine_id'  => $machine->id,
                'pot_number'  => $potNumber,
                'is_disabled' => true,
            ]);
        }

        $this->syncAvailableSpots($machine);

        return redirect()->route('admin.magazines.show', $machine)
            ->with('success', "ポット {$potNumber} を使用不可にしました。");
    }

    // ポットを使用可能に戻す
    public function enablePot(Machine $machine, MagazinePot $pot)
    {
        abort_if($machine->company_id !== Auth::user()->company_id, 403);

        $potNumber = $pot->pot_number;
        $pot->delete();

        $this->syncAvailableSpots($machine);

        return redirect()->route('admin.magazines.show', $machine)
            ->with('success', "ポット {$potNumber} を使用可能に戻しました。");
    }

    private function formData(): array
    {
        $companyId  = Auth::user()->company_id;
        $categories = ToolCategory::where('company_id', $companyId)->orderBy('name')->get(['id', 'name']);
        $tools      = Tool::where('company_id', $companyId)->orderBy('name')
            ->get(['id', 'tool_category_id', 'name', 'maker']);

        $toolsByCategoryJson = $tools
            ->groupBy('tool_category_id')
            ->map(fn($g) => $g->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'maker' => $t->maker])->values());

        $standbySets = StandbySet::where('company_id', $companyId)
            ->with(['machine', 'tools.toolCategory'])
            ->orderByDesc('created_at')
            ->get();

        return [$categories, $toolsByCategoryJson, $standbySets];
    }

    private function syncAvailableSpots(Machine $machine): void
    {
        $used = $machine->magazinePots()->count();
        $machine->update(['available_spots' => $machine->magazine_capacity - $used]);
    }
}
