@extends('layouts.app')

@section('title', $machine->name . ' マガジン | 加工機工具管理システム')
@section('page-title', 'マガジンポット管理')

@section('content')

    {{-- 機械ヘッダー --}}
    <div class="magazine-show-header">
        <a href="{{ route('admin.magazines.index') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            マガジン管理に戻る
        </a>

        <div class="magazine-show-machine">
            <div class="magazine-show-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="4"/>
                    <line x1="12" y1="2" x2="12" y2="8"/>
                    <line x1="12" y1="16" x2="12" y2="22"/>
                    <line x1="2" y1="12" x2="8" y2="12"/>
                    <line x1="16" y1="12" x2="22" y2="12"/>
                </svg>
            </div>
            <div>
                <h2 class="magazine-show-name">{{ $machine->name }}</h2>
                <p class="magazine-show-number">{{ $machine->machine_number ? 'No. '.$machine->machine_number : '番号未設定' }}</p>
            </div>
        </div>

        @php
            $occupiedCount = $pots->where('is_disabled', false)->count();
            $disabledCount = $pots->where('is_disabled', true)->count();
            $freeCount     = $machine->magazine_capacity - $occupiedCount - $disabledCount;
        @endphp
        <div class="magazine-show-stats">
            <div class="show-stat">
                <span class="show-stat-label">全ポット</span>
                <span class="show-stat-value">{{ $machine->magazine_capacity }}<span class="show-stat-unit">本</span></span>
            </div>
            <div class="show-stat">
                <span class="show-stat-label">使用中</span>
                <span class="show-stat-value" style="color:var(--color-primary)">{{ $occupiedCount }}<span class="show-stat-unit">本</span></span>
            </div>
            <div class="show-stat">
                <span class="show-stat-label">空き</span>
                <span class="show-stat-value {{ $freeCount === 0 ? 'stat-danger' : ($freeCount <= ($machine->magazine_capacity * 0.2) ? 'stat-warning' : '') }}">
                    {{ $freeCount }}<span class="show-stat-unit">本</span>
                </span>
            </div>
            @if ($disabledCount > 0)
            <div class="show-stat">
                <span class="show-stat-label">使用不可</span>
                <span class="show-stat-value" style="color:#9ca3af">{{ $disabledCount }}<span class="show-stat-unit">本</span></span>
            </div>
            @endif
        </div>
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

    {{-- ポットグリッド --}}
    <div class="pot-grid">
        @for ($i = 1; $i <= $machine->magazine_capacity; $i++)
            @php $pot = $pots->get($i); @endphp

            @if ($pot && $pot->is_disabled)
                {{-- 使用不可ポット --}}
                <div class="pot-cell pot-disabled">
                    <span class="pot-number">{{ $i }}</span>
                    <div class="pot-disabled-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                        </svg>
                        <span>使用不可</span>
                    </div>
                    @if(auth()->user()->isAdmin())
                    <form action="{{ route('admin.magazines.enablePot', [$machine, $pot]) }}" method="POST" style="width:100%">
                        @csrf
                        <button type="submit" class="pot-btn pot-btn-enable"
                                onclick="return confirm('ポット {{ $i }} を使用可能に戻しますか？')">使用可能に戻す</button>
                    </form>
                    @endif
                </div>

            @elseif ($pot)
                {{-- 使用中ポット --}}
                <div class="pot-cell pot-occupied">
                    <span class="pot-number">{{ $i }}</span>
                    <div class="pot-content">
                        <p class="pot-first-name">{{ $pot->tools->first()?->name ?? '' }}</p>
                        <p class="pot-count-sub">{{ $pot->tools_count }} 工具</p>
                    </div>
                    <div class="pot-actions">
                        <a href="{{ route('admin.magazines.showPot', [$machine, $pot]) }}" class="pot-btn pot-btn-detail">詳細</a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.magazines.editPot',  [$machine, $pot]) }}" class="pot-btn pot-btn-edit">編集</a>
                        <button type="button" class="pot-btn pot-btn-remove"
                                onclick="openRemoveModal(
                                    '{{ route('admin.magazines.destroyPot',          [$machine, $pot]) }}',
                                    '{{ route('admin.magazines.destroyPotWithTools', [$machine, $pot]) }}',
                                    {{ $i }}
                                )">取り外す</button>
                        @endif
                    </div>
                </div>

            @else
                {{-- 空きポット --}}
                <div class="pot-cell pot-empty">
                    <span class="pot-number">{{ $i }}</span>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.magazines.createPot', [$machine, $i]) }}" class="pot-empty-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        <span>空き</span>
                    </a>
                    <form action="{{ route('admin.magazines.disablePot', [$machine, $i]) }}" method="POST" style="width:100%">
                        @csrf
                        <button type="submit" class="pot-btn pot-btn-disable"
                                onclick="return confirm('ポット {{ $i }} を使用不可にしますか？')">使用不可</button>
                    </form>
                    @else
                    <div class="pot-empty-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                        <span>空き</span>
                    </div>
                    @endif
                </div>
            @endif

        @endfor
    </div>

    {{-- 取り外しモーダル --}}
    <div id="removeModal" class="modal-overlay" onclick="if(event.target===this) closeRemoveModal()">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">ポット <span id="modalPotNumber"></span> を取り外す</h3>
                <button type="button" class="modal-close" onclick="closeRemoveModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <p class="modal-desc">取り外した工具をどうしますか？</p>
            <div class="modal-choices">
                <form id="standbyForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="modal-choice modal-choice-standby">
                        <span class="modal-choice-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </span>
                        <span class="modal-choice-text">
                            <strong>待機工具として保管</strong>
                            <small>工具データを残し、後で別のポットに登録できます</small>
                        </span>
                    </button>
                </form>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="modal-choice modal-choice-delete">
                        <span class="modal-choice-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/>
                            </svg>
                        </span>
                        <span class="modal-choice-text">
                            <strong>待機に移さず取り外す</strong>
                            <small>ポットから外すのみ。工具データは工具管理に残ります</small>
                        </span>
                    </button>
                </form>
            </div>
            <button type="button" class="modal-cancel" onclick="closeRemoveModal()">キャンセル</button>
        </div>
    </div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/magazine.css') }}?v={{ filemtime(public_path('css/admin/magazine.css')) }}">
@endpush

@push('scripts')
<script>
function openRemoveModal(standbyUrl, deleteUrl, potNumber) {
    document.getElementById('standbyForm').action = standbyUrl;
    document.getElementById('deleteForm').action   = deleteUrl;
    document.getElementById('modalPotNumber').textContent = potNumber;
    document.getElementById('removeModal').classList.add('is-open');
}
function closeRemoveModal() {
    document.getElementById('removeModal').classList.remove('is-open');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeRemoveModal(); });
</script>
@endpush
