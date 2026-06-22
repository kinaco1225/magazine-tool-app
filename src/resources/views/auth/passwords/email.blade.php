<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワードをお忘れの方 | 加工機工具管理システム</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

    <div class="login-wrapper">

        {{-- 左パネル：ブランディング --}}
        <div class="login-brand">
            <div class="brand-content">
                <div class="brand-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none">
                        <circle cx="32" cy="32" r="28" stroke="#4f8ef7" stroke-width="3"/>
                        <circle cx="32" cy="32" r="10" stroke="#4f8ef7" stroke-width="2.5"/>
                        <line x1="32" y1="4"  x2="32" y2="16" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="32" y1="48" x2="32" y2="60" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="4"  y1="32" x2="16" y2="32" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="48" y1="32" x2="60" y2="32" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="12" y1="12" x2="20" y2="20" stroke="#4f8ef7" stroke-width="2" stroke-linecap="round"/>
                        <line x1="44" y1="44" x2="52" y2="52" stroke="#4f8ef7" stroke-width="2" stroke-linecap="round"/>
                        <line x1="52" y1="12" x2="44" y2="20" stroke="#4f8ef7" stroke-width="2" stroke-linecap="round"/>
                        <line x1="20" y1="44" x2="12" y2="52" stroke="#4f8ef7" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <h1 class="brand-title">加工機工具<br>管理システム</h1>
                <p class="brand-subtitle">加工機工具管理システム</p>
                <ul class="brand-features">
                    <li>加工機工具の一元管理</li>
                    <li>工具寿命・交換時期のトラッキング</li>
                    <li>段取り替え作業の効率化</li>
                    <li>工具在庫・発注状況の可視化</li>
                </ul>
            </div>
            <div class="brand-decoration">
                <div class="deco-circle deco-1"></div>
                <div class="deco-circle deco-2"></div>
                <div class="deco-circle deco-3"></div>
            </div>
        </div>

        {{-- 右パネル：フォーム --}}
        <div class="login-form-panel">
            <div class="login-form-inner">
            <div class="login-card">
                <div class="login-header">
                    <h2>パスワードをお忘れの方</h2>
                    <p>登録済みのメールアドレスを入力してください。<br>パスワード再設定用のリンクをお送りします。</p>
                </div>

                {{-- エラーメッセージ --}}
                @if ($errors->any())
                    <div class="alert alert-error">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- 送信完了メッセージ --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="login-form">
                    @csrf

                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="example@company.com"
                                autocomplete="email"
                                required
                                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        <span>再設定メールを送信</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </button>
                </form>

                <p class="register-login-link">
                    <a href="{{ route('login') }}">ログイン画面に戻る</a>
                </p>
            </div>
            </div>{{-- /.login-form-inner --}}

            <p class="login-footer">
                &copy; {{ date('Y') }} 加工機工具管理システム
            </p>
        </div>

    </div>

</body>
</html>
