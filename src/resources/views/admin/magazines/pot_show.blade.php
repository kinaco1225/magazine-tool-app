@extends('layouts.app')

@section('title', $machine->name . ' ポット' . $pot->pot_number . ' 詳細 | 加工機工具管理システム')
@section('page-title', 'ポット詳細')

@section('content')

    <a href="{{ route('admin.magazines.show', $machine) }}" class="back-link" style="display:inline-flex;align-items:center;gap:4px;margin-bottom:20px;font-size:0.85rem;color:var(--color-text-muted);text-decoration:none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        {{ $machine->name }} に戻る
    </a>

    {{-- ポットヘッダー --}}
    <div class="magazine-show-header" style="margin-bottom:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(79,142,247,0.1);color:var(--color-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.4rem;font-weight:800;">
                    {{ $pot->pot_number }}
                </div>
                <div>
                    <h2 style="font-size:1.1rem;font-weight:700;color:var(--color-text);margin-bottom:3px;">
                        ポット {{ $pot->pot_number }}
                    </h2>
                    <p style="font-size:0.8rem;color:var(--color-text-muted);">
                        {{ $machine->name }}{{ $machine->machine_number ? ' No.'.$machine->machine_number : '' }}
                    </p>
                </div>
            </div>
            @if(auth()->user()->isAdmin())
            <div style="display:flex;gap:8px;">
                <a href="{{ route('admin.magazines.editPot', [$machine, $pot]) }}" class="btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    編集
                </a>
                <button type="button" class="btn-danger" onclick="openRemoveModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/>
                    </svg>
                    取り外す
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- 工具一覧 --}}
    <div class="table-card">
        <div style="padding:16px 20px;border-bottom:1px solid var(--color-border);display:flex;align-items:center;justify-content:space-between;">
            <p style="font-size:0.85rem;font-weight:600;color:var(--color-text);">登録工具一覧</p>
            <span style="font-size:0.82rem;color:var(--color-text-muted);">{{ $pot->tools->count() }} 件</span>
        </div>

        @if ($pot->tools->isEmpty())
            <div class="table-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
                <p>工具が登録されていません</p>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.magazines.editPot', [$machine, $pot]) }}" class="btn-primary">工具を登録する</a>
                @endif
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>工具名</th>
                        <th>カテゴリー</th>
                        <th>メーカー</th>
                        <th>型式</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pot->tools as $tool)
                        <tr>
                            <td data-label="工具名">{{ $tool->name }}</td>
                            <td data-label="カテゴリー" class="text-muted">{{ $tool->toolCategory->name ?? '―' }}</td>
                            <td data-label="メーカー"   class="text-muted">{{ $tool->maker ?? '―' }}</td>
                            <td data-label="型式"       class="text-muted">{{ $tool->model ?? '―' }}</td>
                            <td class="table-actions">
                                @if(auth()->user()->isAdmin())
                                <button type="button" class="btn-action btn-edit"
                                    onclick="openOrderModal({{ $tool->id }}, '{{ addslashes($tool->name) }}')"
                                >発注</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- 取り外しモーダル --}}
    <div id="removeModal" class="modal-overlay" onclick="if(event.target===this) closeRemoveModal()">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">ポット {{ $pot->pot_number }} を取り外す</h3>
                <button type="button" class="modal-close" onclick="closeRemoveModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <p class="modal-desc">取り外した工具をどうしますか？</p>
            <div class="modal-choices">
                <form method="POST" action="{{ route('admin.magazines.destroyPot', [$machine, $pot]) }}">
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
                <form method="POST" action="{{ route('admin.magazines.destroyPotWithTools', [$machine, $pot]) }}">
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

    {{-- 発注モーダル --}}
    <div id="orderModal" class="modal-overlay" onclick="if(event.target===this) closeOrderModal()">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">「<span id="orderModalName"></span>」を発注</h3>
                <button type="button" class="modal-close" onclick="closeOrderModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
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
                    <button type="submit" class="btn-submit">発注する</button>
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
function openRemoveModal()  { document.getElementById('removeModal').classList.add('is-open'); }
function closeRemoveModal() { document.getElementById('removeModal').classList.remove('is-open'); }

function openOrderModal(id, name) {
    document.getElementById('orderModalName').textContent = name;
    document.getElementById('orderForm').action = `/app/tools/${id}/orders`;
    document.getElementById('orderQty').value = 1;
    document.getElementById('orderModal').classList.add('is-open');
}
function closeOrderModal() { document.getElementById('orderModal').classList.remove('is-open'); }

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeRemoveModal(); closeOrderModal(); }
});
</script>
@endpush
