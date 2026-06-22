@extends('layouts.app')

@section('title', 'カテゴリー編集 | 加工機工具管理システム')
@section('page-title', '工具管理')

@section('content')

    <div class="content-header">
        <a href="{{ route('admin.tools.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            工具管理に戻る
        </a>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>カテゴリー編集</h2>
            <p>{{ $toolCategory->name }} を編集します</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.tool-categories.update', $toolCategory) }}" method="POST" class="form">
            @csrf
            @method('PUT')

            <div class="form-group form-group-half">
                <label for="name">カテゴリー名 <span class="required">*</span></label>
                <div class="input-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $toolCategory->name) }}"
                        required
                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                    >
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.tools.index') }}" class="btn-cancel">キャンセル</a>
                <button type="submit" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    更新する
                </button>
            </div>
        </form>
    </div>

@endsection
