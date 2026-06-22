@extends('layouts.app')

@section('title', '待機工具一覧 | 加工機工具管理システム')
@section('page-title', '待機工具一覧')

@section('content')

    {{-- ヘッダー --}}
    <div class="list-header">
        <div class="list-header-left">
            <a href="{{ route('admin.magazines.index') }}" class="back-link" style="display:inline-flex;align-items:center;gap:4px;font-size:0.85rem;color:var(--color-text-muted);text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                マガジン管理に戻る
            </a>
            <p class="list-count" style="margin-top:6px;">待機セット <span>{{ $sets->count() }}</span> 件</p>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.standby.create') }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            待機工具を登録
        </a>
        @endif
    </div>

    {{-- セッションメッセージ --}}
    @if (session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($sets->isEmpty())
        <div class="table-card">
            <div class="table-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <p>待機中の工具セットはありません</p>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.standby.create') }}" class="btn-primary">待機工具を登録する</a>
                @endif
            </div>
        </div>
    @else
        <div class="standby-card-grid">
            @foreach ($sets as $set)
            <div class="standby-card">
                {{-- 機械タグ --}}
                <div class="standby-card-machine">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/>
                    </svg>
                    {{ $set->machine->name ?? '機械未設定' }}
                    @if ($set->machine && $set->machine->machine_number)
                        No.{{ $set->machine->machine_number }}
                    @endif
                </div>

                {{-- 工具情報 --}}
                <div class="standby-card-body">
                    <p class="standby-card-firstname">
                        {{ $set->tools->first()?->name ?? '(工具なし)' }}
                    </p>
                    <p class="standby-card-count">{{ $set->tools_count }} 工具</p>
                    <ul class="standby-card-toollist">
                        @foreach ($set->tools->take(4) as $tool)
                            <li>
                                <span class="standby-tool-cat">{{ $tool->toolCategory->name ?? '' }}</span>
                                {{ $tool->name }}
                            </li>
                        @endforeach
                        @if ($set->tools->count() > 4)
                            <li class="standby-tool-more">… 他 {{ $set->tools->count() - 4 }} 件</li>
                        @endif
                    </ul>
                </div>

                {{-- 登録日 --}}
                <p class="standby-card-date">{{ $set->created_at->format('Y/m/d') }} 登録</p>

                {{-- アクション --}}
                @if(auth()->user()->isAdmin())
                <div class="standby-card-actions">
                    <button type="button" class="pot-btn pot-btn-detail"
                            onclick="openAssignModal({{ $set->id }}, '{{ addslashes($set->machine->name ?? '') }}')">
                        ポットへ割り当て
                    </button>
                    <form action="{{ route('admin.standby.destroy', $set) }}" method="POST"
                          onsubmit="return confirm('この待機工具セットを削除しますか？')">
                        @csrf @method('DELETE')
                        <button type="submit" class="pot-btn pot-btn-remove">削除</button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    @endif

    {{-- ポット割り当てモーダル --}}
    <div id="assignModal" class="modal-overlay" onclick="if(event.target===this) closeAssignModal()">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">ポットに割り当て</h3>
                <button type="button" class="modal-close" onclick="closeAssignModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <p class="modal-desc">割り当て先の機械とポット番号を選択してください。</p>

            <form id="assignForm" method="POST">
                @csrf
                <div style="display:flex;flex-direction:column;gap:14px;margin-bottom:20px;">
                    <div>
                        <label style="font-size:0.78rem;font-weight:600;color:var(--color-text-muted);display:block;margin-bottom:6px;">機械</label>
                        <select name="machine_id" id="assignMachine" class="cat-select" onchange="updateCapacity(this)" required>
                            <option value="">-- 機械を選択 --</option>
                            @foreach ($machines as $m)
                                <option value="{{ $m->id }}" data-cap="{{ $m->magazine_capacity }}">
                                    {{ $m->name }}{{ $m->machine_number ? ' No.'.$m->machine_number : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="font-size:0.78rem;font-weight:600;color:var(--color-text-muted);display:block;margin-bottom:6px;">
                            ポット番号
                            <span id="capHint" style="font-weight:400;margin-left:6px;"></span>
                        </label>
                        <input type="number" name="pot_number" id="assignPotNumber" min="1"
                               class="cat-select" placeholder="例: 3" required>
                    </div>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="button" class="modal-cancel" onclick="closeAssignModal()">キャンセル</button>
                    <button type="submit" class="btn-submit" style="flex:1">割り当て</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/magazine.css') }}?v={{ filemtime(public_path('css/admin/magazine.css')) }}">
@endpush

@push('scripts')
<script>
function openAssignModal(setId, machineName) {
    document.getElementById('assignForm').action = '/app/standby/' + setId + '/assign';
    document.getElementById('assignMachine').value = '';
    document.getElementById('assignPotNumber').value = '';
    document.getElementById('capHint').textContent = '';
    document.getElementById('assignModal').classList.add('is-open');
}
function closeAssignModal() {
    document.getElementById('assignModal').classList.remove('is-open');
}
function updateCapacity(sel) {
    const cap = sel.options[sel.selectedIndex]?.dataset.cap;
    document.getElementById('capHint').textContent = cap ? `（最大 ${cap} 本）` : '';
    document.getElementById('assignPotNumber').max = cap || '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAssignModal(); });
</script>
@endpush
