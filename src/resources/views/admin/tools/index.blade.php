@extends('layouts.app')

@section('title', '工具管理 | 加工機工具管理システム')
@section('page-title', '工具管理')

@section('content')

    {{-- ヘッダー --}}
    <div class="list-header">
        <div class="list-header-left">
            <p class="list-count">カテゴリー <span>{{ $categories->count() }}</span> 件</p>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.tool-categories.create') }}" class="btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            カテゴリー追加
        </a>
        @endif
    </div>

    {{-- セッションメッセージ --}}
    @if (session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- カテゴリーカード --}}
    @if ($categories->isEmpty())
        <div class="table-card">
            <div class="table-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
                <p>カテゴリーが登録されていません</p>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.tool-categories.create') }}" class="btn-primary">最初のカテゴリーを追加する</a>
                @endif
            </div>
        </div>
    @else
        <div class="category-grid">
            @foreach ($categories as $category)
                <div class="category-card">
                    <a href="{{ route('admin.tools.category', $category) }}" class="category-card-link">
                        <div class="category-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                            </svg>
                        </div>
                        <div class="category-card-body">
                            <h3 class="category-card-name">{{ $category->name }}</h3>
                            <p class="category-card-count">{{ $category->tools_count }} 件の工具</p>
                        </div>
                        <div class="category-card-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </div>
                    </a>
                    @if(auth()->user()->isAdmin())
                    <div class="category-card-actions">
                        <a href="{{ route('admin.tool-categories.edit', $category) }}" class="btn-action btn-edit">編集</a>
                        <form action="{{ route('admin.tool-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('このカテゴリーを削除しますか？\n※工具データも削除されます')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">削除</button>
                        </form>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

@endsection
