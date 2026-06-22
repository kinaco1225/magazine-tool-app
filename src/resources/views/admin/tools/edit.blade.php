@extends('layouts.app')

@section('title', '工具編集 | 加工機工具管理システム')
@section('page-title', '工具管理')

@section('content')

    <div class="content-header">
        @if ($tool->tool_category_id)
            <a href="{{ route('admin.tools.category', $tool->tool_category_id) }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                カテゴリー一覧に戻る
            </a>
        @else
            <a href="{{ route('admin.tools.index') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                工具管理に戻る
            </a>
        @endif
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>工具情報編集</h2>
            <p>{{ $tool->name }} の情報を編集します</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.tools.update', $tool) }}" method="POST" class="form">
            @csrf
            @method('PUT')

            <div class="form-section-label">基本情報</div>

            <div class="form-row">
                <div class="form-group">
                    <label for="name">工具名 <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $tool->name) }}"
                            placeholder="例：φ10 エンドミル"
                            required
                            class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="tool_category_id">カテゴリー <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <select id="tool_category_id" name="tool_category_id" class="{{ $errors->has('tool_category_id') ? 'is-invalid' : '' }}">
                            <option value="">選択なし</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('tool_category_id', $tool->tool_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section-label">工具情報</div>

            <div class="form-row">
                <div class="form-group">
                    <label for="maker">メーカー <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        <input
                            type="text"
                            id="maker"
                            name="maker"
                            value="{{ old('maker', $tool->maker) }}"
                            placeholder="例：OSG"
                            required
                            class="{{ $errors->has('maker') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="model">型式 <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/>
                        </svg>
                        <input
                            type="text"
                            id="model"
                            name="model"
                            value="{{ old('model', $tool->model) }}"
                            placeholder="例：AE-EM-DIN6535HA"
                            required
                            class="{{ $errors->has('model') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>
            </div>

            <div class="form-section-label">在庫情報</div>

            <div class="form-row">
                <div class="form-group">
                    <label for="stock_quantity">在庫数 <span class="optional">（任意）</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                        <input
                            type="number"
                            id="stock_quantity"
                            name="stock_quantity"
                            value="{{ old('stock_quantity', $tool->stock_quantity) }}"
                            min="0"
                            class="{{ $errors->has('stock_quantity') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="reorder_point">発注点 <span class="optional">（任意）</span></label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                        <input
                            type="number"
                            id="reorder_point"
                            name="reorder_point"
                            value="{{ old('reorder_point', $tool->reorder_point) }}"
                            min="0"
                            class="{{ $errors->has('reorder_point') ? 'is-invalid' : '' }}"
                        >
                    </div>
                </div>
            </div>

            @php $managesStock = old('manages_stock', $tool->manages_stock ? '1' : '0'); @endphp
            <div class="form-group">
                <label>在庫管理</label>
                <div style="margin-top:6px;">
                    <input type="hidden" name="manages_stock" value="0">
                    <label style="display:inline-flex;align-items:center;gap:10px;cursor:pointer;">
                        <span style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0;">
                            <input type="checkbox" id="manages_stock" name="manages_stock" value="1"
                                {{ $managesStock !== '0' ? 'checked' : '' }}
                                style="position:absolute;opacity:0;width:0;height:0;"
                                onchange="updateStockToggle(this)">
                            <span id="manages_stock_track" style="position:absolute;inset:0;border-radius:12px;transition:background 0.2s;background:{{ $managesStock !== '0' ? 'var(--color-primary,#4f8ef7)' : '#d1d5db' }};"></span>
                            <span id="manages_stock_thumb" style="position:absolute;top:2px;width:20px;height:20px;background:#fff;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,.2);transition:left 0.2s;left:{{ $managesStock !== '0' ? '22px' : '2px' }};"></span>
                        </span>
                        <span id="manages_stock_label" style="font-size:0.9rem;color:var(--color-text);">{{ $managesStock !== '0' ? '在庫管理する' : '在庫管理しない' }}</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="note">備考 <span class="optional">（任意）</span></label>
                <textarea
                    id="note"
                    name="note"
                    rows="3"
                    placeholder="メモや特記事項を入力してください"
                    class="form-textarea {{ $errors->has('note') ? 'is-invalid' : '' }}"
                >{{ old('note', $tool->note) }}</textarea>
            </div>

            <div class="form-actions">
                <a href="{{ $tool->tool_category_id ? route('admin.tools.category', $tool->tool_category_id) : route('admin.tools.index') }}" class="btn-cancel">キャンセル</a>
                <button type="submit" class="btn-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    更新する
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
function updateStockToggle(el) {
    const on = el.checked;
    document.getElementById('manages_stock_track').style.background = on ? 'var(--color-primary,#4f8ef7)' : '#d1d5db';
    document.getElementById('manages_stock_thumb').style.left = on ? '22px' : '2px';
    document.getElementById('manages_stock_label').textContent = on ? '在庫管理する' : '在庫管理しない';
}
</script>
@endpush
