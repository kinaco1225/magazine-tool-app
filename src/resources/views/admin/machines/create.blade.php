@extends('layouts.app')

@section('title', '機械登録 | 加工機工具管理システム')
@section('page-title', '機械管理')

@section('content')

    <div class="content-header">
        <a href="{{ route('admin.machines.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            機械一覧に戻る
        </a>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>機械登録</h2>
            <p>新しい機械の情報を入力してください</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.machines.store') }}" method="POST" class="form">
            @csrf

            <div class="form-section-label">基本情報</div>

            <div class="form-row">
                <div class="form-group">
                    <label for="name">機械名 <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2"/>
                            <path d="M8 21h8M12 17v4"/>
                        </svg>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="例：マシニングセンタ A"
                            required
                            class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="machine_number">機械番号 <span class="optional">（任意）</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        </svg>
                        <input
                            type="text"
                            id="machine_number"
                            name="machine_number"
                            value="{{ old('machine_number') }}"
                            placeholder="例：MC-001"
                            class="{{ $errors->has('machine_number') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="maker">メーカー <span class="optional">（任意）</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        <input
                            type="text"
                            id="maker"
                            name="maker"
                            value="{{ old('maker') }}"
                            placeholder="例：FANUC"
                            class="{{ $errors->has('maker') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="model">型式 <span class="optional">（任意）</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/>
                        </svg>
                        <input
                            type="text"
                            id="model"
                            name="model"
                            value="{{ old('model') }}"
                            placeholder="例：ROBODRILL α-D21MiA"
                            class="{{ $errors->has('model') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>
            </div>

            <div class="form-section-label">設置情報</div>

            <div class="form-row">
                <div class="form-group">
                    <label for="location">設置場所 <span class="optional">（任意）</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <input
                            type="text"
                            id="location"
                            name="location"
                            value="{{ old('location') }}"
                            placeholder="例：第1工場 Aライン"
                            class="{{ $errors->has('location') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="magazine_capacity">マガジン本数 <span class="optional">（任意）</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="16"/>
                            <line x1="8" y1="12" x2="16" y2="12"/>
                        </svg>
                        <input
                            type="number"
                            id="magazine_capacity"
                            name="magazine_capacity"
                            value="{{ old('magazine_capacity') }}"
                            placeholder="例：20"
                            min="1"
                            class="{{ $errors->has('magazine_capacity') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="is_active">使用状態 <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <select id="is_active" name="is_active" class="{{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                            <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>使用中</option>
                            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>停止中</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="note">備考 <span class="optional">（任意）</span></label>
                <textarea
                    id="note"
                    name="note"
                    rows="3"
                    placeholder="メモや特記事項を入力してください"
                    class="form-textarea {{ $errors->has('note') ? 'is-invalid' : '' }}"
                >{{ old('note') }}</textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.machines.index') }}" class="btn-cancel">キャンセル</a>
                <button type="submit" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    登録する
                </button>
            </div>
        </form>
    </div>

@endsection
