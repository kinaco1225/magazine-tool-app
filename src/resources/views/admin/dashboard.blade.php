@extends('layouts.app')

@section('title', 'ダッシュボード | 加工機工具管理システム')
@section('page-title', 'ダッシュボード')


@section('content')

    {{-- ウェルカムバナー --}}
    <div class="welcome-banner">
        <div class="welcome-text">
            <h2>おかえりなさい、{{ Auth::user()->name }}さん</h2>
            <p>{{ now()->format('Y年m月d日（D）') }}</p>
        </div>
        <div class="welcome-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="28" stroke="#4f8ef7" stroke-width="2" opacity="0.3"/>
                <circle cx="32" cy="32" r="10" stroke="#4f8ef7" stroke-width="2"/>
                <line x1="32" y1="4"  x2="32" y2="16" stroke="#4f8ef7" stroke-width="2" stroke-linecap="round"/>
                <line x1="32" y1="48" x2="32" y2="60" stroke="#4f8ef7" stroke-width="2" stroke-linecap="round"/>
                <line x1="4"  y1="32" x2="16" y2="32" stroke="#4f8ef7" stroke-width="2" stroke-linecap="round"/>
                <line x1="48" y1="32" x2="60" y2="32" stroke="#4f8ef7" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    {{-- 管理メニューカード --}}
    <div class="menu-grid">

        <a href="{{ route('admin.machines.index') }}" class="menu-card">
            <div class="menu-card-icon" style="--icon-color: #4f8ef7; --icon-bg: rgba(79,142,247,0.12);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07M8.46 8.46a5 5 0 0 0 0 7.07"/>
                </svg>
            </div>
            <div class="menu-card-body">
                <h3>機械管理</h3>
                <p>工作機械の登録・編集・削除</p>
            </div>
            <div class="menu-card-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.magazines.index') }}" class="menu-card">
            <div class="menu-card-icon" style="--icon-color: #a78bfa; --icon-bg: rgba(167,139,250,0.12);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="4"/>
                    <line x1="12" y1="2" x2="12" y2="8"/>
                    <line x1="12" y1="16" x2="12" y2="22"/>
                    <line x1="2" y1="12" x2="8" y2="12"/>
                    <line x1="16" y1="12" x2="22" y2="12"/>
                </svg>
            </div>
            <div class="menu-card-body">
                <h3>マガジン管理</h3>
                <p>マガジン構成・段取り替え管理</p>
            </div>
            <div class="menu-card-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.tools.index') }}" class="menu-card">
            <div class="menu-card-icon" style="--icon-color: #34d399; --icon-bg: rgba(52,211,153,0.12);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
            </div>
            <div class="menu-card-body">
                <h3>工具管理</h3>
                <p>工具の登録・寿命・在庫管理</p>
            </div>
            <div class="menu-card-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.inventory.index') }}" class="menu-card">
            <div class="menu-card-icon" style="--icon-color: #06b6d4; --icon-bg: rgba(6,182,212,0.12);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
            </div>
            <div class="menu-card-body">
                <h3>在庫管理</h3>
                <p>工具在庫の確認・発注状況管理</p>
            </div>
            <div class="menu-card-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </div>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.users.index') }}" class="menu-card">
            <div class="menu-card-icon" style="--icon-color: #fb923c; --icon-bg: rgba(251,146,60,0.12);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div class="menu-card-body">
                <h3>ユーザー管理</h3>
                <p>作業者の登録・権限管理</p>
            </div>
            <div class="menu-card-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </div>
        </a>
        @endif

    </div>

@endsection
