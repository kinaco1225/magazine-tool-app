@extends('layouts.app')

@section('title', $toolCategory->name . ' | 工具管理 | 加工機工具管理システム')
@section('page-title', '工具管理 → ' . $toolCategory->name)

@section('content')

    {{-- 戻るリンク --}}
    <a href="{{ route('admin.tools.index') }}" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        工具管理トップへ戻る
    </a>

    {{-- ヘッダー --}}
    <div class="list-header">
        <div class="list-header-left">
            <p class="list-count">全 <span>{{ $tools->count() }}</span> 件</p>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.tools.create', ['tool_category_id' => $toolCategory->id]) }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            工具を登録する
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

    @php
        $uniqueToolMakers = $tools->pluck('maker')->filter()->unique()->sort()->values();
    @endphp

    {{-- テーブル --}}
    <div class="table-card">
        @if ($tools->isEmpty())
            <div class="table-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
                <p>このカテゴリーに工具が登録されていません</p>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.tools.create', ['tool_category_id' => $toolCategory->id]) }}" class="btn-primary">最初の工具を登録する</a>
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
                        <span id="filterCount">{{ $tools->count() }}</span> / {{ $tools->count() }} 件
                    </span>
                </div>
                <div class="filter-bar-body" id="filterBarBody">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label>工具名</label>
                            <input type="text" class="filter-input" id="m-name" placeholder="部分一致で絞込…">
                        </div>
                        <div class="filter-item">
                            <label>メーカー</label>
                            <select class="filter-select" id="m-maker">
                                <option value="">すべて</option>
                                @foreach ($uniqueToolMakers as $maker)
                                    <option value="{{ $maker }}">{{ $maker }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>型式</label>
                            <input type="text" class="filter-input" id="m-model" placeholder="部分一致で絞込…">
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
                        <th>メーカー</th>
                        <th>型式</th>
                        <th>在庫数</th>
                        <th>発注点</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="toolsBody">
                    @foreach ($tools as $tool)
                        <tr
                            data-name="{{ mb_strtolower($tool->name) }}"
                            data-maker="{{ $tool->maker ?? '' }}"
                            data-model="{{ mb_strtolower($tool->model ?? '') }}"
                        >
                            <td data-label="工具名">{{ $tool->name }}</td>
                            <td data-label="メーカー" class="text-muted">{{ $tool->maker ?? '―' }}</td>
                            <td data-label="型式" class="text-muted">{{ $tool->model ?? '―' }}</td>
                            <td data-label="在庫数" class="text-center">{{ $tool->stock_quantity ?: '―' }}</td>
                            <td data-label="発注点" class="text-center">{{ $tool->reorder_point ?: '―' }}</td>
                            <td class="table-actions">
                                <button
                                    class="btn-action btn-detail"
                                    onclick="openToolModal(this)"
                                    data-name="{{ $tool->name }}"
                                    data-maker="{{ $tool->maker ?? '―' }}"
                                    data-model="{{ $tool->model ?? '―' }}"
                                    data-stock="{{ $tool->stock_quantity }}"
                                    data-order="{{ $tool->reorder_point }}"
                                    data-note="{{ $tool->note ?? '―' }}"
                                    data-date="{{ $tool->created_at->format('Y/m/d') }}"
                                >詳細</button>
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.tools.edit', $tool) }}" class="btn-action btn-edit">編集</a>
                                <form action="{{ route('admin.tools.destroy', $tool) }}" method="POST" onsubmit="return confirm('この工具を削除しますか？')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">削除</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection

{{-- モーダル --}}
@push('modals')
<div class="modal-overlay" id="toolModal" style="display:none">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title" id="modalToolName"></h3>
                </div>
            </div>
            <button class="modal-close" id="modalCloseBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <dl class="detail-list">
                <div class="detail-row">
                    <dt>メーカー</dt>
                    <dd id="modalMaker"></dd>
                </div>
                <div class="detail-row">
                    <dt>型式</dt>
                    <dd id="modalModel"></dd>
                </div>
                <div class="detail-row">
                    <dt>在庫数</dt>
                    <dd id="modalStock"></dd>
                </div>
                <div class="detail-row">
                    <dt>発注点</dt>
                    <dd id="modalOrder"></dd>
                </div>
                <div class="detail-row">
                    <dt>備考</dt>
                    <dd id="modalNote"></dd>
                </div>
                <div class="detail-row">
                    <dt>登録日</dt>
                    <dd id="modalDate"></dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    const toolModal = document.getElementById('toolModal');

    function openToolModal(btn) {
        document.getElementById('modalToolName').textContent = btn.dataset.name;
        document.getElementById('modalMaker').textContent   = btn.dataset.maker;
        document.getElementById('modalModel').textContent   = btn.dataset.model;
        document.getElementById('modalStock').textContent   = btn.dataset.stock > 0 ? btn.dataset.stock : '―';
        document.getElementById('modalOrder').textContent   = btn.dataset.order > 0 ? btn.dataset.order : '―';
        document.getElementById('modalNote').textContent    = btn.dataset.note;
        document.getElementById('modalDate').textContent    = btn.dataset.date;
        toolModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeToolModal() {
        toolModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    toolModal.addEventListener('click', function(e) {
        if (e.target === toolModal) closeToolModal();
    });

    document.getElementById('modalCloseBtn').addEventListener('click', closeToolModal);

    // ===== テーブルフィルター =====
    const filterState = { name: '', maker: '', model: '' };

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

    function applyFilters() {
        const rows = document.querySelectorAll('#toolsBody tr');
        let visible = 0;
        rows.forEach(row => {
            const show =
                (row.dataset.name  || '').includes(filterState.name) &&
                (filterState.maker === '' || (row.dataset.maker || '') === filterState.maker) &&
                (row.dataset.model || '').includes(filterState.model);

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        const countEl = document.getElementById('filterCount');
        if (countEl) countEl.textContent = visible;
        updateBadge();
    }

    function clearFilters() {
        filterState.name = filterState.maker = filterState.model = '';
        ['m-name','m-maker','m-model'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
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
    document.getElementById('m-maker')?.addEventListener('change', e => {
        filterState.maker = e.target.value;
        applyFilters();
    });
    document.getElementById('m-model')?.addEventListener('input', e => {
        filterState.model = e.target.value.toLowerCase();
        applyFilters();
    });
</script>
@endpush
