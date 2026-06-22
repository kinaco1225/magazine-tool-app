@extends('layouts.app')

@section('title', '待機工具登録 | 加工機工具管理システム')
@section('page-title', '待機工具登録')

@section('content')

    <a href="{{ route('admin.standby.index') }}" class="back-link" style="display:inline-flex;align-items:center;gap:4px;margin-bottom:20px;font-size:0.85rem;color:var(--color-text-muted);text-decoration:none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        待機工具一覧に戻る
    </a>

    <div class="form-card">
        <div class="form-card-header" style="padding:20px 24px 0;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                <div style="width:44px;height:44px;border-radius:12px;background:rgba(79,142,247,0.1);color:var(--color-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                </div>
                <div>
                    <h3 style="font-size:1.1rem;font-weight:700;color:var(--color-text);">待機工具セットの登録</h3>
                    <p style="font-size:0.8rem;color:var(--color-text-muted);margin-top:2px;">関連する機械と工具を登録してください</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-error" style="margin:0 24px 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form id="standbyForm" method="POST" action="{{ route('admin.standby.store') }}" style="padding:0 24px 24px;">
            @csrf

            {{-- 機械選択 --}}
            <div style="margin-bottom:20px;">
                <label style="font-size:0.82rem;font-weight:600;color:var(--color-text-muted);display:block;margin-bottom:6px;">
                    関連機械（任意）
                </label>
                <select name="machine_id" class="cat-select" style="max-width:360px;">
                    <option value="">-- 機械を選択（省略可） --</option>
                    @foreach ($machines as $machine)
                        <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>
                            {{ $machine->name }}{{ $machine->machine_number ? ' No.'.$machine->machine_number : '' }}
                        </option>
                    @endforeach
                </select>
                <p style="font-size:0.75rem;color:var(--color-text-muted);margin-top:4px;">どの機械用の待機工具か記録するために使用します。</p>
            </div>

            <hr style="border:none;border-top:1px solid var(--color-border);margin-bottom:20px;">

            <div style="display:flex;align-items:flex-start;gap:8px;background:rgba(79,142,247,0.06);border:1px solid rgba(79,142,247,0.25);border-radius:8px;padding:10px 14px;margin-bottom:20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" style="flex-shrink:0;margin-top:1px;">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p style="font-size:0.82rem;color:var(--color-primary);line-height:1.6;margin:0;">
                    カテゴリーを選択して工具を登録してください。工具を選択すると次の行が自動で追加されます。
                </p>
            </div>

            <div id="toolRowsContainer"></div>

            <div class="form-actions" style="padding-top:8px;">
                <a href="{{ route('admin.standby.index') }}" class="btn-cancel">キャンセル</a>
                <button type="submit" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    登録する
                </button>
            </div>
        </form>
    </div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/magazine.css') }}?v={{ filemtime(public_path('css/admin/magazine.css')) }}">
@endpush

@push('scripts')
<script>
const toolsByCategory = @json($toolsByCategoryJson);
const categoriesData  = @json($categories);

function buildRow(index, catId = null, toolId = null) {
    const div = document.createElement('div');
    div.className = 'pot-form-row';
    div.dataset.index = index;

    let catOptions = '<option value="">-- カテゴリーを選択 --</option>';
    categoriesData.forEach(c => {
        const sel = catId && catId == c.id ? 'selected' : '';
        catOptions += `<option value="${c.id}" ${sel}>${c.name}</option>`;
    });

    let toolOptions = '<option value="">-- 工具を選択 --</option>';
    if (catId && toolsByCategory[catId]) {
        toolsByCategory[catId].forEach(t => {
            const sel = toolId && toolId == t.id ? 'selected' : '';
            toolOptions += `<option value="${t.id}" ${sel}>${t.name}${t.maker ? '（'+t.maker+'）' : ''}</option>`;
        });
    }

    div.innerHTML = `
        <span class="row-num">${index + 1}</span>
        <div class="row-fields">
            <div class="row-field">
                <label class="row-label">カテゴリー</label>
                <select class="cat-select" onchange="onCatChange(this)">${catOptions}</select>
            </div>
            <div class="row-field">
                <label class="row-label">工具名</label>
                <select class="tool-select" name="tool_ids[]" ${catId ? '' : 'disabled'} onchange="onToolChange(this)">${toolOptions}</select>
            </div>
        </div>
        <button type="button" class="row-remove-btn" onclick="removeRow(this)">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>`;
    return div;
}

function onCatChange(select) {
    const row = select.closest('.pot-form-row');
    const toolSel = row.querySelector('.tool-select');
    const catId = select.value;
    toolSel.innerHTML = '<option value="">-- 工具を選択 --</option>';
    toolSel.disabled = !catId;
    if (catId && toolsByCategory[catId]) {
        toolsByCategory[catId].forEach(t => {
            toolSel.add(new Option(t.name + (t.maker ? `（${t.maker}）` : ''), t.id));
        });
    }
}

function onToolChange(select) {
    if (!select.value) return;
    const container = document.getElementById('toolRowsContainer');
    const rows = container.querySelectorAll('.pot-form-row');
    if (select.closest('.pot-form-row') === rows[rows.length - 1]) {
        container.appendChild(buildRow(rows.length));
        updateRemoveBtns();
    }
}

function removeRow(btn) {
    btn.closest('.pot-form-row').remove();
    document.querySelectorAll('.pot-form-row').forEach((r, i) => {
        r.dataset.index = i;
        r.querySelector('.row-num').textContent = i + 1;
    });
    updateRemoveBtns();
}

function updateRemoveBtns() {
    const rows = document.querySelectorAll('.pot-form-row');
    rows.forEach(r => { r.querySelector('.row-remove-btn').style.display = rows.length > 1 ? '' : 'none'; });
}

document.getElementById('standbyForm').addEventListener('submit', function(e) {
    document.querySelectorAll('.pot-form-row').forEach(row => {
        const toolSel = row.querySelector('.tool-select');
        if (!toolSel.value || toolSel.disabled) {
            toolSel.disabled = true;
            row.querySelector('.cat-select').disabled = true;
        }
    });
    const hasValid = Array.from(document.querySelectorAll('.tool-select[name="tool_ids[]"]'))
        .some(s => !s.disabled && s.value);
    if (!hasValid) { e.preventDefault(); alert('工具を1つ以上選択してください。'); }
});

(function init() {
    const container = document.getElementById('toolRowsContainer');
    container.appendChild(buildRow(0));
    updateRemoveBtns();
})();
</script>
@endpush
