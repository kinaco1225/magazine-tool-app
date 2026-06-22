@extends('layouts.app')

@section('title', 'ユーザー登録 | 加工機工具管理システム')
@section('page-title', 'ユーザー登録')

@section('content')

    <div class="content-header">
        <a href="{{ route('admin.users.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            ユーザー一覧に戻る
        </a>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>新規ユーザー登録</h2>
            <p>作業者または管理者のアカウントを作成します</p>
        </div>

        {{-- エラーメッセージ --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="form">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="name">氏名 <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="山田 太郎"
                            required
                            class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">権限 <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <select id="role" name="role" class="{{ $errors->has('role') ? 'is-invalid' : '' }}">
                            <option value="worker" {{ old('role', 'worker') === 'worker' ? 'selected' : '' }}>作業者</option>
                            <option value="admin"  {{ old('role') === 'admin'  ? 'selected' : '' }}>管理者</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="email">メールアドレス <span class="required">*</span></label>
                <div class="input-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="example@company.com"
                        required
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                    >
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">パスワード <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="8文字以上"
                            required
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">パスワード（確認） <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="もう一度入力"
                            required
                            class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">キャンセル</a>
                <button type="submit" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <line x1="20" y1="8" x2="20" y2="14"/>
                        <line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                    登録する
                </button>
            </div>
        </form>
    </div>

@endsection
