@extends('layouts.app')

@section('title', 'ユーザー管理 | 加工機工具管理システム')
@section('page-title', 'ユーザー管理')

@section('content')

    {{-- ヘッダー --}}
    <div class="list-header">
        <div class="list-header-left">
            <p class="list-count">全 <span>{{ $users->count() }}</span> 件</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            新規ユーザー登録
        </a>
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

    {{-- エラーメッセージ --}}
    @if (session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- テーブル --}}
    <div class="table-card">
        @if ($users->isEmpty())
            <div class="table-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <p>ユーザーが登録されていません</p>
                <a href="{{ route('admin.users.create') }}" class="btn-primary">最初のユーザーを登録する</a>
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
                        <span id="filterCount">{{ $users->count() }}</span> / {{ $users->count() }} 件
                    </span>
                </div>
                <div class="filter-bar-body" id="filterBarBody">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label>氏名</label>
                            <input type="text" class="filter-input" id="m-name" placeholder="部分一致で絞込…">
                        </div>
                        <div class="filter-item">
                            <label>メールアドレス</label>
                            <input type="text" class="filter-input" id="m-email" placeholder="部分一致で絞込…">
                        </div>
                        <div class="filter-item">
                            <label>権限</label>
                            <select class="filter-select" id="m-role">
                                <option value="">すべて</option>
                                <option value="admin">管理者</option>
                                <option value="worker">作業者</option>
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
                        <th>氏名</th>
                        <th>メールアドレス</th>
                        <th>権限</th>
                        <th>登録日</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="usersBody">
                    @foreach ($users as $user)
                        <tr
                            data-name="{{ mb_strtolower($user->name) }}"
                            data-email="{{ mb_strtolower($user->email ?? '') }}"
                            data-role="{{ $user->role }}"
                        >
                            <td data-label="氏名">
                                <div class="user-cell">
                                    <div class="user-avatar-sm {{ $user->role === 'worker' ? 'is-worker' : '' }}">{{ mb_substr($user->name, 0, 1) }}</div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td data-label="メールアドレス" class="text-muted">{{ $user->email ?? '―' }}</td>
                            <td data-label="権限">
                                <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-worker' }}">
                                    {{ $user->role === 'admin' ? '管理者' : '作業者' }}
                                </span>
                            </td>
                            <td data-label="登録日" class="text-muted">{{ $user->created_at->format('Y/m/d') }}</td>
                            <td class="table-actions">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-action btn-edit">編集</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('このユーザーを削除しますか？')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection

@push('scripts')
<script>
    // ===== テーブルフィルター =====
    const filterState = { name: '', email: '', role: '' };

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
        const rows = document.querySelectorAll('#usersBody tr');
        let visible = 0;
        rows.forEach(row => {
            const show =
                (row.dataset.name  || '').includes(filterState.name) &&
                (row.dataset.email || '').includes(filterState.email) &&
                (filterState.role  === '' || (row.dataset.role || '') === filterState.role);

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        const countEl = document.getElementById('filterCount');
        if (countEl) countEl.textContent = visible;
        updateBadge();
    }

    function clearFilters() {
        filterState.name = filterState.email = filterState.role = '';
        ['m-name','m-email','m-role'].forEach(id => {
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
    document.getElementById('m-email')?.addEventListener('input', e => {
        filterState.email = e.target.value.toLowerCase();
        applyFilters();
    });
    document.getElementById('m-role')?.addEventListener('change', e => {
        filterState.role = e.target.value;
        applyFilters();
    });
</script>
@endpush
