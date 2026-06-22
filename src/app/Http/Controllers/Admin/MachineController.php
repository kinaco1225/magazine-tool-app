<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Http\Requests\Admin\StoreMachineRequest;
use App\Http\Requests\Admin\UpdateMachineRequest;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::where('company_id', Auth::user()->company_id)->get();
        return view('admin.machines.index',compact('machines'));
    }

    public function create()
    {
        return view('admin.machines.create');
    }

    public function store(StoreMachineRequest $request)
    {
        $machines = $request->validated();

        $machines['company_id'] = Auth::user()->company_id;
        Machine::create($machines);

        return redirect()->route('admin.machines.index')
                    ->with('success', '新規ユーザーを作成しました。');
    }

    public function edit(Machine $machine)
    {
        return view('admin.machines.edit',compact('machine'));
    }

    public function update(UpdateMachineRequest $request, Machine $machine)
    {
        $validated = $request->validated();
        
        $machine->update($validated);

        return redirect()->route('admin.machines.index')
                        ->with('success', '機械情報を更新しました。');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();
        return redirect()->route('admin.machines.index')
                     ->with('success', '機械を削除しました。');
    }
}
