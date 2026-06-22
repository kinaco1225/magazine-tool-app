@extends('layouts.app')

@section('title', '在庫管理 | 加工機工具管理システム')
@section('page-title', '在庫管理')

@section('content')

    {{-- サマリーカード（在庫管理対象のみ集計） --}}
    @php
        $statusOf = function ($t) {
            if ((int) ($t->ordered_quantity ?? 0) > 0) {
                return 'ordered';
            }
            if ($t->stock_quantity === 0) {
                return 'out';
            }
            if ($t->reorder_point > 0 && $t->stock_quantity <= $t->reorder_point) {
                return 'warning';
            }
            return 'normal';
        };

        $totalTools          = $managedTools->count();
        $orderedManagedCount = $managedTools->filter(fn($t) => $statusOf($t) === 'ordered')->count();
        $warningTools        = $managedTools->filter(fn($t) => $statusOf($t) === 'warning')->count();
        $outOfStock          = $managedTools->filter(fn($t) => $statusOf($t) === 'out')->count();
        $normalTools         = $totalTools - $orderedManagedCount - $warningTools - $outOfStock;
        $allTools            = $managedTools->concat($unmanagedTools);
        // 発注中カードは管理外工具の発注も含めて集計する
        $orderedTools        = $allTools->filter(fn($t) => (int) ($t->ordered_quantity ?? 0) > 0)->count();
    @endphp

    <div class="inventory-summary">
        <div class="summary-card summary-card-clickable" onclick="filterByStatus('', this)">
            <div class="summary-icon" style="--icon-color:#4f8ef7; --icon-bg:rgba(79,142,247,0.1);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
            </div>
            <div class="summary-body">
                <p class="summary-label">管理工具数</p>
                <p class="summary-value">{{ $totalTools }} <span>件</span></p>
            </div>
        </div>
        <div class="summary-card summary-card-clickable" onclick="filterByStatus('normal', this)">
            <div class="summary-icon" style="--icon-color:#22c55e; --icon-bg:rgba(34,197,94,0.1);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <div class="summary-body">
                <p class="summary-label">在庫正常</p>
                <p class="summary-value" style="color:#22c55e;">{{ $normalTools }} <span>件</span></p>
            </div>
        </div>
        <div class="summary-card summary-card-clickable" onclick="filterByStatus('warning', this)">
            <div class="summary-icon" style="--icon-color:#f59e0b; --icon-bg:rgba(245,158,11,0.1);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div class="summary-body">
                <p class="summary-label">発注点以下</p>
                <p class="summary-value" style="color:#f59e0b;">{{ $warningTools }} <span>件</span></p>
            </div>
        </div>
        <div class="summary-card summary-card-clickable" onclick="filterByStatus('ordered', this)">
            <div class="summary-icon" style="--icon-color:#6366f1; --icon-bg:rgba(99,102,241,0.1);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
            </div>
            <div class="summary-body">
                <p class="summary-label">発注中</p>
                <p class="summary-value" style="color:#6366f1;">{{ $orderedTools }} <span>件</span></p>
            </div>
        </div>
        <div class="summary-card summary-card-clickable" onclick="filterByStatus('out', this)">
            <div class="summary-icon" style="--icon-color:#ef4444; --icon-bg:rgba(239,68,68,0.1);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div class="summary-body">
                <p class="summary-label">在庫切れ</p>
                <p class="summary-value" style="color:#ef4444;">{{ $outOfStock }} <span>件</span></p>
            </div>
        </div>
        <div class="summary-card summary-card-clickable" onclick="filterByStatus('unmanaged', this)">
            <div class="summary-icon" style="--icon-color:#6b7280; --icon-bg:rgba(107,114,128,0.1);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                </svg>
            </div>
            <div class="summary-body">
                <p class="summary-label">管理外</p>
                <p class="summary-value" style="color:#6b7280;">{{ $unmanagedTools->count() }} <span>件</span></p>
            </div>
        </div>
    </div>

    @php
        $uniqueCategories = $allTools->map(fn($t) => $t->toolCategory?->name)->filter()->unique()->sort()->values();
        $uniqueMakers     = $allTools->pluck('maker')->filter()->unique()->sort()->values();
        $totalCount       = $allTools->count();
    @endphp

    {{-- セッションメッセージ --}}
    @if (session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- テーブル --}}
    <div class="table-card">
        @if ($allTools->isEmpty())
            <div class="table-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
                <p>工具が登録されていません</p>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.tools.index') }}" class="btn-primary">工具を登録する</a>
                @endif
            </div>
        @else
            {{-- フィルターバー --}}
            <div class="filter-bar">
                <div class="filter-bar-header">
                    <button type="button" class="filter-toggle-btn" id="filterToggleBtn" onclick="toggleFilterBar()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                        </svg>
                        <span>絞り込み</span>
                        <span class="filter-active-badge" id="filterActiveBadge" style="display:none"></span>
                        <svg class="toggle-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                    <span class="filter-count-inline">
                        <span id="filterCount">{{ $totalCount }}</span> / {{ $totalCount }} 件
                    </span>
                </div>
                <div class="filter-bar-body" id="filterBarBody">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label>工具名</label>
                            <input type="text" class="filter-input" id="m-name" placeholder="部分一致で絞込…">
                        </div>
                        <div class="filter-item">
                            <label>カテゴリー</label>
                            <select class="filter-select" id="m-category">
                                <option value="">すべて</option>
                                @foreach ($uniqueCategories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>メーカー</label>
                            <select class="filter-select" id="m-maker">
                                <option value="">すべて</option>
                                @foreach ($uniqueMakers as $maker)
                                    <option value="{{ $maker }}">{{ $maker }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>型式</label>
                            <input type="text" class="filter-input" id="m-model" placeholder="部分一致で絞込…">
                        </div>
                        <div class="filter-item">
                            <label>ステータス</label>
                            <select class="filter-select" id="m-status">
                                <option value="">すべて</option>
                                <option value="normal">正常</option>
                                <option value="warning">要発注</option>
                                <option value="ordered">発注中</option>
                                <option value="out">在庫切れ</option>
                                <option value="unmanaged">管理外</option>
                            </select>
                        </div>
                        <div class="filter-item filter-item-clear">
                            <button type="button" class="btn-filter-clear" onclick="clearFilters()">クリア</button>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>工具名</th>
                        <th>カテゴリー</th>
                        <th>メーカー</th>
                        <th>型式</th>
                        <th class="text-center">在庫数</th>
                        <th class="text-center">発注点</th>
                        <th class="text-center">発注中</th>
                        <th class="text-center">ステータス</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="inventoryBody">
                    {{-- 在庫管理する工具 --}}
                    @foreach ($managedTools as $tool)
                        @php
                            $status = $statusOf($tool);
                            $orderedQty = (int) ($tool->ordered_quantity ?? 0);
                            if ($tool->stock_quantity === 0) {
                                $stockLevel = 'out';
                            } elseif ($tool->reorder_point > 0 && $tool->stock_quantity <= $tool->reorder_point) {
                                $stockLevel = 'warning';
                            } else {
                                $stockLevel = 'normal';
                            }
                        @endphp
                        <tr class="{{ $status === 'out' ? 'row-danger' : ($status === 'warning' ? 'row-warning' : '') }}"
                            data-name="{{ mb_strtolower($tool->name) }}"
                            data-category="{{ $tool->toolCategory->name ?? '' }}"
                            data-maker="{{ $tool->maker ?? '' }}"
                            data-model="{{ mb_strtolower($tool->model ?? '') }}"
                            data-status="{{ $status }}">
                            <td data-label="工具名">{{ $tool->name }}</td>
                            <td data-label="カテゴリー" class="text-muted">{{ $tool->toolCategory->name ?? '―' }}</td>
                            <td data-label="メーカー" class="text-muted">{{ $tool->maker ?? '―' }}</td>
                            <td data-label="型式" class="text-muted">{{ $tool->model ?? '―' }}</td>
                            <td data-label="在庫数" class="text-center">
                                <span class="stock-num {{ $stockLevel === 'out' ? 'stock-danger' : ($stockLevel === 'warning' ? 'stock-warning' : 'stock-normal') }}">
                                    {{ $tool->stock_quantity }}
                                </span>
                            </td>
                            <td data-label="発注点" class="text-center text-muted">
                                {{ $tool->reorder_point ?: '―' }}
                            </td>
                            <td data-label="発注中" class="text-center">
                                @if ($orderedQty > 0)
                                    <span class="badge badge-ordered">{{ $orderedQty }}</span>
                                @else
                                    <span class="text-muted">―</span>
                                @endif
                            </td>
                            <td data-label="ステータス" class="text-center">
                                @if ($status === 'ordered')
                                    <span class="badge badge-ordered">発注中</span>
                                @elseif ($status === 'out')
                                    <span class="badge badge-danger">在庫切れ</span>
                                @elseif ($status === 'warning')
                                    <span class="badge badge-warning">要発注</span>
                                @else
                                    <span class="badge badge-success">正常</span>
                                @endif
                            </td>
                            <td class="table-actions">
                                @if(auth()->user()->isAdmin())
                                <button class="btn-action btn-edit"
                                    onclick="openEditModal({{ $tool->id }}, '{{ addslashes($tool->name) }}', {{ $tool->stock_quantity }}, {{ $tool->reorder_point }}, 1, 'stock')"
                                >編集</button>
                                <button class="btn-action btn-detail"
                                    onclick="openOrderModal({{ $tool->id }}, '{{ addslashes($tool->name) }}')"
                                >発注</button>
                                @if ($orderedQty > 0)
                                    <button class="btn-action btn-receive"
                                        onclick="openReceiveModal({{ $tool->id }}, '{{ addslashes($tool->name) }}', {{ $orderedQty }})"
                                    >入荷</button>
                                @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    {{-- 管理外セパレーター --}}
                    @if ($unmanagedTools->isNotEmpty())
                        <tr class="inventory-separator" id="unmanagedSeparator">
                            <td colspan="9">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span style="font-size:0.78rem;font-weight:600;color:var(--color-text-muted);white-space:nowrap;">在庫管理外</span>
                                    <span style="flex:1;height:1px;background:var(--color-border);"></span>
                                    <span style="font-size:0.75rem;color:var(--color-text-muted);">{{ $unmanagedTools->count() }} 件</span>
                                </div>
                            </td>
                        </tr>
                    @endif

                    {{-- 在庫管理しない工具 --}}
                    @foreach ($unmanagedTools as $tool)
                        @php
                            $orderedQty = (int) ($tool->ordered_quantity ?? 0);
                            $rowStatus  = 'unmanaged' . ($orderedQty > 0 ? ' ordered' : '');
                        @endphp
                        <tr class="row-unmanaged"
                            data-name="{{ mb_strtolower($tool->name) }}"
                            data-category="{{ $tool->toolCategory->name ?? '' }}"
                            data-maker="{{ $tool->maker ?? '' }}"
                            data-model="{{ mb_strtolower($tool->model ?? '') }}"
                            data-status="{{ $rowStatus }}"
                            data-section="unmanaged">
                            <td data-label="工具名">{{ $tool->name }}</td>
                            <td data-label="カテゴリー" class="text-muted">{{ $tool->toolCategory->name ?? '―' }}</td>
                            <td data-label="メーカー" class="text-muted">{{ $tool->maker ?? '―' }}</td>
                            <td data-label="型式" class="text-muted">{{ $tool->model ?? '―' }}</td>
                            <td data-label="在庫数" class="text-center text-muted">―</td>
                            <td data-label="発注点" class="text-center text-muted">―</td>
                            <td data-label="発注中" class="text-center">
                                @if ($orderedQty > 0)
                                    <span class="badge badge-ordered">{{ $orderedQty }}</span>
                                @else
                                    <span class="text-muted">―</span>
                                @endif
                            </td>
                            <td data-label="ステータス" class="text-center">
                                <span class="badge badge-unmanaged">管理外</span>
                                @if ($orderedQty > 0)
                                    <span class="badge badge-ordered">発注中</span>
                                @endif
                            </td>
                            <td class="table-actions">
                                @if(auth()->user()->isAdmin())
                                <button class="btn-action btn-edit"
                                    onclick="openEditModal({{ $tool->id }}, '{{ addslashes($tool->name) }}', {{ $tool->stock_quantity }}, {{ $tool->reorder_point }}, 0, 'manages')"
                                >編集</button>
                                <button class="btn-action btn-detail"
                                    onclick="openOrderModal({{ $tool->id }}, '{{ addslashes($tool->name) }}')"
                                >発注</button>
                                @if ($orderedQty > 0)
                                    <button class="btn-action btn-receive"
                                        onclick="openReceiveModal({{ $tool->id }}, '{{ addslashes($tool->name) }}', {{ $orderedQty }})"
                                    >入荷</button>
                                @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection

{{-- 在庫管理 統合編集モーダル --}}
@push('modals')
<div class="modal-overlay" id="editModal" style="display:none">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title" id="editModalName"></h3>
                    <p class="modal-subtitle" id="editModalSub"></p>
                </div>
            </div>
            <button class="modal-close" onclick="closeEditModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- タブ --}}
        <div class="edit-tabs">
            <button type="button" class="edit-tab" id="tab-btn-stock"    onclick="switchEditTab('stock')">在庫調整</button>
            <button type="button" class="edit-tab" id="tab-btn-reorder"  onclick="switchEditTab('reorder')">発注点変更</button>
            <button type="button" class="edit-tab" id="tab-btn-manages"  onclick="switchEditTab('manages')">在庫管理</button>
        </div>

        <div class="modal-body">

            {{-- タブ: 在庫調整 --}}
            <div id="tab-stock" class="tab-panel">
                <form id="editStockForm" method="POST" class="form">
                    @csrf
                    @method('PUT')

                    <div class="stock-type-group">
                        <label class="stock-type-label">
                            <input type="radio" name="type" value="add" checked onchange="updateEditTypeLabel()">
                            <span class="stock-type-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                入庫
                            </span>
                        </label>
                        <label class="stock-type-label">
                            <input type="radio" name="type" value="use" onchange="updateEditTypeLabel()">
                            <span class="stock-type-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                使用
                            </span>
                        </label>
                        <label class="stock-type-label">
                            <input type="radio" name="type" value="set" onchange="updateEditTypeLabel()">
                            <span class="stock-type-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                直接入力
                            </span>
                        </label>
                    </div>

                    <div class="form-group" style="margin-top:20px;">
                        <label id="editStockQtyLabel">入庫数</label>
                        <div class="input-wrapper">
                            <input type="number" id="editStockQty" name="quantity" value="1" min="0" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeEditModal()">キャンセル</button>
                        <button type="submit" class="btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            更新する
                        </button>
                    </div>
                </form>
            </div>

            {{-- タブ: 発注点変更 --}}
            <div id="tab-reorder" class="tab-panel" style="display:none">
                <form id="editReorderForm" method="POST" class="form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="editReorderPoint">発注点（この数量以下になったら発注）</label>
                        <div class="input-wrapper">
                            <input type="number" id="editReorderPoint" name="reorder_point" min="0" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeEditModal()">キャンセル</button>
                        <button type="submit" class="btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            更新する
                        </button>
                    </div>
                </form>
            </div>

            {{-- タブ: 在庫管理 --}}
            <div id="tab-manages" class="tab-panel" style="display:none">
                <form id="editManagesForm" method="POST" class="form">
                    @csrf

                    <div class="form-group">
                        <label>在庫管理設定</label>
                        <div style="margin-top:10px;">
                            <input type="hidden" id="editManagesInput" name="manages_stock" value="1">
                            <label style="display:inline-flex;align-items:center;gap:12px;cursor:pointer;" onclick="toggleManagesSwitch()">
                                <span style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0;pointer-events:none;">
                                    <span id="editManagesTrack" style="position:absolute;inset:0;border-radius:12px;transition:background 0.2s;background:var(--color-primary,#4f8ef7);"></span>
                                    <span id="editManagesThumb" style="position:absolute;top:2px;width:20px;height:20px;background:#fff;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,.2);transition:left 0.2s;left:22px;"></span>
                                </span>
                                <span id="editManagesLabel" style="font-size:0.9rem;font-weight:500;color:var(--color-text);">在庫管理する</span>
                            </label>
                        </div>
                        <p style="font-size:0.8rem;color:var(--color-text-muted);margin-top:10px;">
                            管理外にすると在庫調整・発注点の設定が非表示になります
                        </p>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeEditModal()">キャンセル</button>
                        <button type="submit" class="btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            更新する
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endpush

{{-- 発注モーダル --}}
@push('modals')
<div class="modal-overlay" id="orderModal" style="display:none">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title" id="orderModalName"></h3>
                    <p class="modal-subtitle">発注数量を入力してください</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeOrderModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="orderForm" method="POST" class="form">
                @csrf
                <div class="form-group">
                    <label for="orderQty">発注数量</label>
                    <div class="input-wrapper">
                        <input type="number" id="orderQty" name="quantity" value="1" min="1" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeOrderModal()">キャンセル</button>
                    <button type="submit" class="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        発注する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

{{-- 入荷モーダル --}}
@push('modals')
<div class="modal-overlay" id="receiveModal" style="display:none">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title" id="receiveModalName"></h3>
                    <p class="modal-subtitle">発注中：<strong id="receiveModalOrdered"></strong> 個</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeReceiveModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="receiveForm" method="POST" class="form">
                @csrf
                <div class="form-group">
                    <label for="receiveQty">入荷数量（一部のみの場合は届いた数量を入力）</label>
                    <div class="input-wrapper">
                        <input type="number" id="receiveQty" name="quantity" value="1" min="1" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeReceiveModal()">キャンセル</button>
                    <button type="submit" class="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        入荷する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/inventory.css') }}?v={{ filemtime(public_path('css/admin/inventory.css')) }}">
<style>
.inventory-separator td { padding: 10px 16px; background: var(--color-bg, #f8fafc); }
.row-unmanaged td { opacity: 0.65; }
.badge-ordered {
    background: rgba(99,102,241,0.12); color: #6366f1;
    font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 20px;
}
.badge-unmanaged {
    background: rgba(107,114,128,0.12); color: #6b7280;
    font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 20px;
}
.btn-receive {
    background-color: rgba(34,197,94,0.1); color: #22c55e;
}
.btn-receive:hover { background-color: rgba(34,197,94,0.2); }
.summary-card-clickable { cursor: pointer; transition: transform 0.15s, box-shadow 0.15s; }
.summary-card-clickable:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.summary-card-clickable.is-active { outline: 2px solid var(--color-primary,#4f8ef7); outline-offset: -2px; }
.edit-tabs {
    display: flex; border-bottom: 1px solid var(--color-border);
    padding: 0 20px; background: var(--color-surface);
}
.edit-tab {
    padding: 10px 16px; font-size: 0.83rem; font-weight: 500;
    color: var(--color-text-muted); background: none; border: none;
    border-bottom: 2px solid transparent; cursor: pointer;
    margin-bottom: -1px; transition: color 0.15s, border-color 0.15s;
}
.edit-tab:hover { color: var(--color-text); }
.edit-tab.is-active { color: var(--color-primary,#4f8ef7); border-bottom-color: var(--color-primary,#4f8ef7); font-weight: 600; }
.tab-panel { animation: tabFadeIn 0.15s ease; }
@keyframes tabFadeIn { from { opacity:0; } to { opacity:1; } }
</style>
@endpush

@push('scripts')
<script>
    // ===== 統合編集モーダル =====
    const editModal = document.getElementById('editModal');

    function openEditModal(id, name, stock, reorder, manages, defaultTab) {
        document.getElementById('editModalName').textContent = name;
        document.getElementById('editModalSub').textContent = '現在の在庫数：' + stock;

        document.getElementById('editStockForm').action   = `/app/inventory/${id}`;
        document.getElementById('editReorderForm').action = `/app/inventory/${id}/reorder-point`;
        document.getElementById('editManagesForm').action = `/app/inventory/${id}/manages-stock`;

        // 在庫調整タブ初期化
        const addRadio = document.querySelector('#editStockForm input[name="type"][value="add"]');
        if (addRadio) addRadio.checked = true;
        document.getElementById('editStockQty').value = 1;
        updateEditTypeLabel();

        // 発注点タブ初期化
        document.getElementById('editReorderPoint').value = reorder;

        // 在庫管理タブ初期化
        setManagesToggle(manages);

        switchEditTab(defaultTab || 'stock');
        editModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        editModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function switchEditTab(tab) {
        ['stock', 'reorder', 'manages'].forEach(t => {
            document.getElementById(`tab-${t}`).style.display = t === tab ? '' : 'none';
            document.getElementById(`tab-btn-${t}`).classList.toggle('is-active', t === tab);
        });
    }

    function updateEditTypeLabel() {
        const checked = document.querySelector('#editStockForm input[name="type"]:checked');
        if (!checked) return;
        const labels = { add: '入庫数', use: '使用数', set: '在庫数（直接入力）' };
        document.getElementById('editStockQtyLabel').textContent = labels[checked.value];
    }

    function setManagesToggle(manages) {
        const on = !!manages;
        document.getElementById('editManagesTrack').style.background = on ? 'var(--color-primary,#4f8ef7)' : '#d1d5db';
        document.getElementById('editManagesThumb').style.left       = on ? '22px' : '2px';
        document.getElementById('editManagesLabel').textContent      = on ? '在庫管理する' : '在庫管理しない';
        document.getElementById('editManagesInput').value            = on ? '1' : '0';
    }

    function toggleManagesSwitch() {
        const current = document.getElementById('editManagesInput').value;
        setManagesToggle(current === '0');
    }

    editModal.addEventListener('click', e => { if (e.target === editModal) closeEditModal(); });

    // ===== 発注モーダル =====
    const orderModal = document.getElementById('orderModal');
    const orderForm  = document.getElementById('orderForm');

    function openOrderModal(id, name) {
        document.getElementById('orderModalName').textContent = name;
        orderForm.action = `/app/tools/${id}/orders`;
        document.getElementById('orderQty').value = 1;
        orderModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeOrderModal() {
        orderModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    orderModal.addEventListener('click', e => { if (e.target === orderModal) closeOrderModal(); });

    // ===== 入荷モーダル =====
    const receiveModal = document.getElementById('receiveModal');
    const receiveForm  = document.getElementById('receiveForm');

    function openReceiveModal(id, name, orderedQty) {
        document.getElementById('receiveModalName').textContent = name;
        document.getElementById('receiveModalOrdered').textContent = orderedQty;
        receiveForm.action = `/app/tools/${id}/orders/receive`;
        const qtyInput = document.getElementById('receiveQty');
        qtyInput.max = orderedQty;
        qtyInput.value = orderedQty;
        receiveModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeReceiveModal() {
        receiveModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    receiveModal.addEventListener('click', e => { if (e.target === receiveModal) closeReceiveModal(); });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeEditModal(); closeOrderModal(); closeReceiveModal(); }
    });

    // ===== テーブルフィルター =====
    const filterState = { name: '', category: '', maker: '', model: '', status: '' };

    function countActive() {
        return Object.values(filterState).filter(v => v !== '').length;
    }

    function updateBadge() {
        const badge = document.getElementById('filterActiveBadge');
        const btn   = document.getElementById('filterToggleBtn');
        const n     = countActive();
        if (!badge) return;
        if (n > 0) {
            badge.textContent = n;
            badge.style.display = 'inline-flex';
            if (btn) btn.classList.add('is-active');
        } else {
            badge.style.display = 'none';
            if (btn) btn.classList.remove('is-active');
        }
    }

    function clearActiveCard() {
        document.querySelectorAll('.summary-card-clickable').forEach(card => card.classList.remove('is-active'));
    }

    function filterByStatus(value, cardEl) {
        filterState.status = value;
        const mStatus = document.getElementById('m-status');
        if (mStatus) mStatus.value = value;

        clearActiveCard();
        if (cardEl) cardEl.classList.add('is-active');

        applyFilters();
    }

    function applyFilters() {
        const rows = document.querySelectorAll('#inventoryBody tr:not(.inventory-separator)');
        let visible = 0, unmanagedVisible = 0;

        rows.forEach(row => {
            const rowStatuses = (row.dataset.status || '').split(' ');
            const show =
                (row.dataset.name     || '').includes(filterState.name) &&
                (filterState.category === '' || (row.dataset.category || '') === filterState.category) &&
                (filterState.maker    === '' || (row.dataset.maker    || '') === filterState.maker)    &&
                (row.dataset.model    || '').includes(filterState.model) &&
                (filterState.status   === '' || rowStatuses.includes(filterState.status));

            row.style.display = show ? '' : 'none';
            if (show) {
                visible++;
                if (row.dataset.section === 'unmanaged') unmanagedVisible++;
            }
        });

        // セパレーター表示制御
        const sep = document.getElementById('unmanagedSeparator');
        if (sep) sep.style.display = unmanagedVisible > 0 ? '' : 'none';

        const countEl = document.getElementById('filterCount');
        if (countEl) countEl.textContent = visible;
        updateBadge();
    }

    function clearFilters() {
        filterState.name = filterState.category = filterState.maker =
        filterState.model = filterState.status = '';
        ['m-name','m-category','m-maker','m-model','m-status'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        clearActiveCard();
        applyFilters();
    }

    function toggleFilterBar() {
        const body = document.getElementById('filterBarBody');
        const btn  = document.getElementById('filterToggleBtn');
        body.classList.toggle('is-open');
        if (btn) btn.classList.toggle('is-open');
    }

    // フィルターバー
    document.getElementById('m-name')?.addEventListener('input', e => {
        filterState.name = e.target.value.toLowerCase();
        applyFilters();
    });
    document.getElementById('m-category')?.addEventListener('change', e => {
        filterState.category = e.target.value;
        applyFilters();
    });
    document.getElementById('m-maker')?.addEventListener('change', e => {
        filterState.maker = e.target.value;
        applyFilters();
    });
    document.getElementById('m-model')?.addEventListener('input', e => {
        filterState.model = e.target.value.toLowerCase();
        applyFilters();
    });
    document.getElementById('m-status')?.addEventListener('change', e => {
        filterState.status = e.target.value;
        clearActiveCard();
        applyFilters();
    });
</script>
@endpush
