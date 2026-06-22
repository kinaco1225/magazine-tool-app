<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\EditUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('company_id', Auth::user()->company_id)->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(EditUserRequest $request)
    {   
        $user = $request->validated();
        $user['password'] = Hash::make($user['password']);

        $user['company_id'] = Auth::user()->company_id;
        User::create($user);

        return redirect()->route('admin.users.index')
                    ->with('success', '新規ユーザーを作成しました。');
    }

    public function edit(User $user)
    {   
        return view('admin.users.edit',compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password); 
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'ユーザー情報を更新しました。');

    }

    public function destroy(User $user)
    {   

        if ($user->id === Auth::user()->id) {
            return redirect()->route('admin.users.index')
                    ->with('error', '自分自身は削除できません。');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
                     ->with('success', 'ユーザーを削除しました。');
    }
}
