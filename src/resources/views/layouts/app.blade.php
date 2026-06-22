<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '加工機工具管理システム')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/theme-light.css') }}?v={{ filemtime(public_path('css/theme-light.css')) }}">
    @stack('styles')
</head>
<body>

    <div class="app-wrapper">

        {{-- サイドバー --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none">
                        <circle cx="32" cy="32" r="28" stroke="#4f8ef7" stroke-width="3"/>
                        <circle cx="32" cy="32" r="10" stroke="#4f8ef7" stroke-width="2.5"/>
                        <line x1="32" y1="4"  x2="32" y2="16" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="32" y1="48" x2="32" y2="60" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="4"  y1="32" x2="16" y2="32" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                        <line x1="48" y1="32" x2="60" y2="32" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="sidebar-brand">
                    <span class="sidebar-brand-main">加工機工具</span>
                    <span class="sidebar-brand-sub">管理システム</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-section-label">メインメニュー</span>
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                        </svg>
                        <span>ダッシュボード</span>
                    </a>
                </div>

                <div class="nav-section">
                    <span class="nav-section-label">管理メニュー</span>
                    <a href="{{ route('admin.machines.index') }}" class="nav-item {{ request()->routeIs('admin.machines*') ? 'is-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
                            <path d="M15.54 8.46a5 5 0 0 1 0 7.07M8.46 8.46a5 5 0 0 0 0 7.07"/>
                        </svg>
                        <span>機械管理</span>
                    </a>
                    <a href="{{ route('admin.magazines.index') }}" class="nav-item {{ request()->routeIs('admin.magazines*') ? 'is-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="4"/>
                            <line x1="12" y1="2" x2="12" y2="8"/>
                            <line x1="12" y1="16" x2="12" y2="22"/>
                            <line x1="2" y1="12" x2="8" y2="12"/>
                            <line x1="16" y1="12" x2="22" y2="12"/>
                        </svg>
                        <span>マガジン管理</span>
                    </a>
                    <a href="{{ route('admin.tools.index') }}" class="nav-item {{ request()->routeIs('admin.tools*') ? 'is-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <span>工具管理</span>
                    </a>
                    <a href="{{ route('admin.inventory.index') }}" class="nav-item {{ request()->routeIs('admin.inventory*') ? 'is-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                        <span>在庫管理</span>
                    </a>
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'is-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span>ユーザー管理</span>
                    </a>
                    @endif
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">{{ mb_substr(Auth::user()->name, 0, 1) }}</div>
                    <div class="user-detail">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">{{ Auth::user()->isAdmin() ? '管理者' : '作業者' }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout" title="ログアウト">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        {{-- モバイル用オーバーレイ --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- メインコンテンツ --}}
        <div class="main-wrapper">
            <header class="top-bar">
                <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="メニュー">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <h1 class="page-title">@yield('page-title')</h1>
                <div class="top-bar-right">
                    <span class="company-name">{{ Auth::user()->company->name ?? '' }}</span>
                </div>
            </header>

            <main class="main-content">
                @yield('content')
            </main>
        </div>

    </div>

    @stack('modals')

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                const isOpen = sidebar.classList.contains('is-open');
                isOpen ? closeSidebar() : openSidebar();
            } else {
                sidebar.classList.toggle('is-collapsed');
            }
        }

        function openSidebar() {
            sidebar.classList.add('is-open');
            overlay.classList.add('is-visible');
        }

        function closeSidebar() {
            sidebar.classList.remove('is-open');
            overlay.classList.remove('is-visible');
        }

        // サイドバー外クリックで閉じる
        document.addEventListener('click', function(e) {
            if (window.innerWidth > 768) return;
            if (!sidebar.classList.contains('is-open')) return;
            if (sidebar.contains(e.target)) return;
            if (e.target.closest('.sidebar-toggle')) return;
            closeSidebar();
        });
    </script>
    @stack('scripts')

</body>
</html>
