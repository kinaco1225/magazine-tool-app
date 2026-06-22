<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン | 加工機工具管理システム</title>
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

        {{-- 右パネル：ログインフォーム --}}
        <div class="login-form-panel">
            <div class="login-form-inner">
            <div class="login-card">
                <div class="login-header">
                    <h2>ログイン</h2>
                    <p>アカウント情報を入力してください</p>
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

                {{-- セッションメッセージ --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST" class="login-form">
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

                    <div class="form-group">
                        <label for="password">
                            パスワード
                            <a href="{{ route('password.request') }}" class="forgot-link">パスワードを忘れた方</a>
                        </label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="パスワードを入力"
                                autocomplete="current-password"
                                required
                                class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="パスワードを表示">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" id="remember">
                            <span class="checkbox-custom"></span>
                            ログイン状態を保持する
                        </label>
                    </div>

                    <button type="submit" class="btn-login">
                        <span>ログイン</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </button>
                </form>

                <p class="register-login-link">
                    アカウントをお持ちでない方は
                    <a href="{{ route('register') }}">新規アカウント作成</a>
                </p>
            </div>
            </div>{{-- /.login-form-inner --}}

            <p class="login-footer">
                &copy; {{ date('Y') }} 加工機工具管理システム
            </p>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>`;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>`;
            }
        }
    </script>

</body>
</html>
