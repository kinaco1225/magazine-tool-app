@extends('layouts.app')

@section('title', '機械管理 | 加工機工具管理システム')
@section('page-title', '機械管理')

@section('content')

    {{-- ヘッダー --}}
    <div class="list-header">
        <div class="list-header-left">
            <p class="list-count">全 <span>{{ $machines->count() }}</span> 件</p>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.machines.create') }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            新規機械登録
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
        $uniqueMachineMakers = $machines->pluck('maker')->filter()->unique()->sort()->values();
    @endphp

    {{-- テーブル --}}
    <div class="table-card">
        @if ($machines->isEmpty())
            <div class="table-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                    <path d="M8 21h8M12 17v4"/>
                </svg>
                <p>機械が登録されていません</p>
                <a href="{{ route('admin.machines.create') }}" class="btn-primary">最初の機械を登録する</a>
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
                        <span id="filterCount">{{ $machines->count() }}</span> / {{ $machines->count() }} 件
                    </span>
                </div>
                <div class="filter-bar-body" id="filterBarBody">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label>機械名</label>
                            <input type="text" class="filter-input" id="m-name" placeholder="部分一致で絞込…">
                        </div>
                        <div class="filter-item">
                            <label>機械番号</label>
                            <input type="text" class="filter-input" id="m-number" placeholder="部分一致で絞込…">
                        </div>
                        <div class="filter-item">
                            <label>メーカー</label>
                            <select class="filter-select" id="m-maker">
                                <option value="">すべて</option>
                                @foreach ($uniqueMachineMakers as $maker)
                                    <option value="{{ $maker }}">{{ $maker }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label>設置場所</label>
                            <input type="text" class="filter-input" id="m-location" placeholder="部分一致で絞込…">
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
                        <th>機械名</th>
                        <th>機械番号</th>
                        <th>メーカー</th>
                        <th>設置場所</th>
                        <th>マガジンポット数</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="machinesBody">
                    @foreach ($machines as $machine)
                        <tr
                            data-name="{{ mb_strtolower($machine->name) }}"
                            data-number="{{ mb_strtolower($machine->machine_number ?? '') }}"
                            data-maker="{{ $machine->maker ?? '' }}"
                            data-location="{{ mb_strtolower($machine->location ?? '') }}"
                        >
                            <td data-label="機械名">{{ $machine->name }}</td>
                            <td data-label="機械番号" class="text-muted">{{ $machine->machine_number ?? '―' }}</td>
                            <td data-label="メーカー" class="text-muted">{{ $machine->maker ?? '―' }}</td>
                            <td data-label="設置場所" class="text-muted">{{ $machine->location ?? '―' }}</td>
                            <td data-label="マガジン数">
                                @if ($machine->magazine_capacity !== null)
                                    <span class="capacity-badge">{{ $machine->magazine_capacity }} 本</span>
                                @else
                                    <span class="text-muted">―</span>
                                @endif
                            </td>
                            <td class="table-actions">
                                <button
                                    class="btn-action btn-detail"
                                    onclick="openMachineModal(this)"
                                    data-name="{{ $machine->name }}"
                                    data-number="{{ $machine->machine_number ?? '―' }}"
                                    data-maker="{{ $machine->maker ?? '―' }}"
                                    data-model="{{ $machine->model ?? '―' }}"
                                    data-location="{{ $machine->location ?? '―' }}"
                                    data-capacity="{{ $machine->magazine_capacity !== null ? $machine->magazine_capacity.' 本' : '―' }}"
                                    data-active="{{ $machine->is_active ? '使用中' : '停止中' }}"
                                    data-note="{{ $machine->note ?? '―' }}"
                                    data-date="{{ $machine->created_at->format('Y/m/d') }}"
                                >詳細</button>
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.machines.edit', $machine) }}" class="btn-action btn-edit">編集</a>
                                <form action="{{ route('admin.machines.destroy', $machine) }}" method="POST" onsubmit="return confirm('この機械を削除しますか？')">
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

{{-- モーダルを body 直下に配置 --}}
@push('modals')
<div class="modal-overlay" id="machineModal" style="display:none">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <path d="M8 21h8M12 17v4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title" id="modalMachineName"></h3>
                    <p class="modal-subtitle" id="modalMachineNumber"></p>
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
                {{-- <div class="detail-row">
                    <dt>機械番号</dt>
                    <dd id="modalNumber"></dd>
                </div> --}}
                <div class="detail-row">
                    <dt>メーカー</dt>
                    <dd id="modalMaker"></dd>
                </div>
                <div class="detail-row">
                    <dt>型式</dt>
                    <dd id="modalModel"></dd>
                </div>
                <div class="detail-row">
                    <dt>設置場所</dt>
                    <dd id="modalLocation"></dd>
                </div>
                <div class="detail-row">
                    <dt>マガジン本数</dt>
                    <dd id="modalCapacity"></dd>
                </div>
                <div class="detail-row">
                    <dt>使用状態</dt>
                    <dd id="modalActive"></dd>
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
    const modal = document.getElementById('machineModal');

    function openMachineModal(btn) {
        document.getElementById('modalMachineName').textContent   = btn.dataset.name;
        document.getElementById('modalMachineNumber').textContent = btn.dataset.number !== '―' ? btn.dataset.number : '';
        document.getElementById('modalMaker').textContent         = btn.dataset.maker;
        document.getElementById('modalModel').textContent         = btn.dataset.model;
        document.getElementById('modalLocation').textContent      = btn.dataset.location;
        document.getElementById('modalCapacity').textContent      = btn.dataset.capacity;
        document.getElementById('modalActive').textContent        = btn.dataset.active;
        document.getElementById('modalNote').textContent          = btn.dataset.note;
        document.getElementById('modalDate').textContent          = btn.dataset.date;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeMachineModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeMachineModal();
    });

    document.getElementById('modalCloseBtn').addEventListener('click', closeMachineModal);

    // ===== テーブルフィルター =====
    const filterState = { name: '', number: '', maker: '', location: '' };

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
        const rows = document.querySelectorAll('#machinesBody tr');
        let visible = 0;
        rows.forEach(row => {
            const show =
                (row.dataset.name     || '').includes(filterState.name) &&
                (row.dataset.number   || '').includes(filterState.number) &&
                (filterState.maker    === '' || (row.dataset.maker || '') === filterState.maker) &&
                (row.dataset.location || '').includes(filterState.location);

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        const countEl = document.getElementById('filterCount');
        if (countEl) countEl.textContent = visible;
        updateBadge();
    }

    function clearFilters() {
        filterState.name = filterState.number = filterState.maker = filterState.location = '';
        ['m-name','m-number','m-maker','m-location'].forEach(id => {
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
    document.getElementById('m-number')?.addEventListener('input', e => {
        filterState.number = e.target.value.toLowerCase();
        applyFilters();
    });
    document.getElementById('m-maker')?.addEventListener('change', e => {
        filterState.maker = e.target.value;
        applyFilters();
    });
    document.getElementById('m-location')?.addEventListener('input', e => {
        filterState.location = e.target.value.toLowerCase();
        applyFilters();
    });
</script>
@endpush
