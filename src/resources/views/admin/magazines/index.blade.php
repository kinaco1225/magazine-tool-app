@extends('layouts.app')

@section('title', 'マガジン管理 | 加工機工具管理システム')
@section('page-title', 'マガジン管理')

@section('content')

    {{-- ヘッダー --}}
    <div class="list-header">
        <div class="list-header-left">
            <p class="list-count">登録機械 <span>{{ $machines->count() }}</span> 台</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('admin.standby.index') }}" class="btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                待機工具一覧
            </a>
            <a href="{{ route('admin.machines.index') }}" class="btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
                </svg>
                機械管理
            </a>
        </div>
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

    {{-- 機械カード --}}
    @if ($machines->isEmpty())
        <div class="table-card">
            <div class="table-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="4"/>
                    <line x1="12" y1="2" x2="12" y2="8"/>
                    <line x1="12" y1="16" x2="12" y2="22"/>
                    <line x1="2" y1="12" x2="8" y2="12"/>
                    <line x1="16" y1="12" x2="22" y2="12"/>
                </svg>
                <p>マガジン本数が登録された機械がありません</p>
                <a href="{{ route('admin.machines.index') }}" class="btn-primary">機械管理で登録する</a>
            </div>
        </div>
    @else
        <div class="magazine-grid">
            @foreach ($machines as $machine)
                @php
                    $total     = $machine->magazine_capacity;
                    $available = $machine->available_spots ?? $total;
                    $used      = $total - $available;
                    $pct       = $total > 0 ? round($used / $total * 100) : 0;
                    $barClass  = $pct >= 80 ? 'bar-danger' : ($pct >= 50 ? 'bar-warning' : 'bar-normal');
                    $numClass  = $available === 0 ? 'stat-danger' : ($available <= ($total * 0.2) ? 'stat-warning' : 'stat-normal');
                @endphp
                <a href="{{ route('admin.magazines.show', $machine) }}" class="magazine-card">
                    <div class="magazine-card-header">
                        <div class="magazine-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="4"/>
                                <line x1="12" y1="2" x2="12" y2="8"/>
                                <line x1="12" y1="16" x2="12" y2="22"/>
                                <line x1="2" y1="12" x2="8" y2="12"/>
                                <line x1="16" y1="12" x2="22" y2="12"/>
                            </svg>
                        </div>
                        <div class="magazine-card-title">
                            <h3 class="magazine-card-name">{{ $machine->name }}</h3>
                            <p class="magazine-card-number">
                                {{ $machine->machine_number ? 'No. '.$machine->machine_number : '番号未設定' }}
                            </p>
                        </div>
                    </div>

                    <div class="magazine-card-stats">
                        <div class="magazine-stat">
                            <span class="magazine-stat-label">マガジンポット数</span>
                            <span class="magazine-stat-value">{{ $total }}<span class="magazine-stat-unit">本</span></span>
                        </div>
                        <div class="magazine-stat-divider"></div>
                        <div class="magazine-stat">
                            <span class="magazine-stat-label">残りのポット数</span>
                            <span class="magazine-stat-value {{ $numClass }}">{{ $available }}<span class="magazine-stat-unit">本</span></span>
                        </div>
                    </div>

                    <div class="magazine-progress">
                        <div class="progress-track">
                            <div class="progress-fill {{ $barClass }}" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="progress-label">{{ $pct }}% 使用中（{{ $used }} / {{ $total }}）</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/magazine.css') }}?v={{ filemtime(public_path('css/admin/magazine.css')) }}">
@endpush
