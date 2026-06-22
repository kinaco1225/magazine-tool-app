<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>加工機工具管理システム｜中小製造業の工具管理をシンプルに</title>
    <link rel="stylesheet" href="{{ asset('css/lp.css') }}">
</head>
<body>

    {{-- ナビゲーション --}}
    <nav class="nav">
        <div class="nav-brand">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="28" stroke="#4f8ef7" stroke-width="3"/>
                <circle cx="32" cy="32" r="10" stroke="#4f8ef7" stroke-width="2.5"/>
                <line x1="32" y1="4"  x2="32" y2="16" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="32" y1="48" x2="32" y2="60" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="4"  y1="32" x2="16" y2="32" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="48" y1="32" x2="60" y2="32" stroke="#4f8ef7" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
            加工機工具管理システム
        </div>
        <a href="{{ route('login') }}" class="nav-login">ログイン</a>
    </nav>

    {{-- ヒーロー --}}
    <section class="hero">
        <div class="hero-pain">⚠️ 紙・Excel管理のまま、損していませんか？</div>
        <h1>
            工具管理の<span class="red">ムダ・ミス・手間</span>を<br>
            <span class="accent">まるごと解決</span>する
        </h1>
        <p class="hero-sub">
            「工具がどこにあるかわからない」「交換時期を見落とした」<br>
            <strong>中小製造業の現場あるある</strong>を、このシステムが一気に解消します。
        </p>
        <a href="{{ route('login') }}" class="btn-hero">
            無料で使ってみる
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
        <p class="hero-note">難しい操作は不要。Excelが使えれば今日から使えます。</p>
    </section>

    {{-- 共感セクション --}}
    <section class="sympathy">
        <div class="sympathy-inner">
            <h2>こんなお悩み、<span>ありませんか？</span></h2>
            <div class="pain-list">
                <div class="pain-item">
                    <div class="pain-check">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <p><strong>工具の場所がわからない</strong><br>探すのに時間がかかり、作業が止まる</p>
                </div>
                <div class="pain-item">
                    <div class="pain-check">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <p><strong>交換時期を見落とす</strong><br>工具の折損・品質不良につながる</p>
                </div>
                <div class="pain-item">
                    <div class="pain-check">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <p><strong>在庫が把握できない</strong><br>気づいたら在庫ゼロで緊急発注</p>
                </div>
                <div class="pain-item">
                    <div class="pain-check">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <p><strong>Excelの更新が面倒</strong><br>誰かが更新しないと情報がズレる</p>
                </div>
                <div class="pain-item">
                    <div class="pain-check">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <p><strong>段取り替えに時間がかかる</strong><br>どの工具をどこに入れるか毎回確認</p>
                </div>
                <div class="pain-item">
                    <div class="pain-check">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <p><strong>担当者しか情報を知らない</strong><br>休んだら誰もわからない属人化</p>
                </div>
            </div>
            <p class="sympathy-closing">
                これらは<strong>仕組みを変えるだけで解決できます。</strong><br>
                大きなIT投資は不要。今日から始められます。
            </p>
        </div>
    </section>

    {{-- 解決策 --}}
    <section class="solution" id="features">
        <div class="section-inner">
            <div class="section-header">
                <span class="section-eyebrow">Solution</span>
                <h2 class="section-title">このシステムが解決します</h2>
                <p class="section-desc">現場の困りごとに直結した4つの機能</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(79,142,247,0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4f8ef7" stroke-width="1.8">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                    </div>
                    <h3>工具の情報を一か所に集約</h3>
                    <p>種類・在庫数・寿命・保管場所をまとめて登録。「あの工具どこ？」がなくなります。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(52,211,153,0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="1.8">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="4"/>
                            <line x1="12" y1="2" x2="12" y2="8"/>
                            <line x1="12" y1="16" x2="12" y2="22"/>
                            <line x1="2" y1="12" x2="8" y2="12"/>
                            <line x1="16" y1="12" x2="22" y2="12"/>
                        </svg>
                    </div>
                    <h3>マガジン構成を見える化</h3>
                    <p>どの機械にどの工具が入っているかを一目で確認。段取り替えの準備時間を大幅に短縮します。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(167,139,250,0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="1.8">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
                        </svg>
                    </div>
                    <h3>機械ごとに管理</h3>
                    <p>複数の加工機をまとめて管理。機械ごとの工具割り当てを整理して、混乱をなくします。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(251,146,60,0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="1.8">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                        </svg>
                    </div>
                    <h3>チームで共有できる</h3>
                    <p>担当者ごとにアカウントを発行。誰でも同じ情報にアクセスでき、属人化から脱却できます。</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Before / After 比較 --}}
    <section class="compare">
        <div class="section-inner">
            <div class="section-header">
                <span class="section-eyebrow">Before / After</span>
                <h2 class="section-title">導入前後の変化</h2>
            </div>
            <table class="compare-table">
                <thead>
                    <tr>
                        <th></th>
                        <th class="before">😓 導入前（紙・Excel）</th>
                        <th class="after">😊 導入後</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>工具の場所確認</td>
                        <td class="before"><span class="badge-bad">手作業で探す・聞き回る</span></td>
                        <td class="after"><span class="badge-good">システムで即確認</span></td>
                    </tr>
                    <tr>
                        <td>交換時期の管理</td>
                        <td class="before"><span class="badge-bad">見落としリスクあり</span></td>
                        <td class="after"><span class="badge-good">寿命を一覧で管理</span></td>
                    </tr>
                    <tr>
                        <td>在庫確認</td>
                        <td class="before"><span class="badge-bad">棚に行って数える</span></td>
                        <td class="after"><span class="badge-good">画面で即把握</span></td>
                    </tr>
                    <tr>
                        <td>情報の共有</td>
                        <td class="before"><span class="badge-bad">担当者しか知らない</span></td>
                        <td class="after"><span class="badge-good">チーム全員で共有</span></td>
                    </tr>
                    <tr>
                        <td>段取り替えの準備</td>
                        <td class="before"><span class="badge-bad">毎回時間がかかる</span></td>
                        <td class="after"><span class="badge-good">構成を事前に確認</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- 安心感 --}}
    <section class="easy" id="easy">
        <div class="section-inner">
            <div class="section-header">
                <span class="section-eyebrow">Simple & Easy</span>
                <h2 class="section-title">難しくありません</h2>
                <p class="section-desc">ITが苦手な方でも、すぐに使いこなせます</p>
            </div>
            <div class="easy-grid">
                <div class="easy-card">
                    <div class="num">PC<small>だけ</small></div>
                    <h4>特別な機器は不要</h4>
                    <p>ブラウザがあれば使えます。専用端末の購入は不要です。</p>
                </div>
                <div class="easy-card">
                    <div class="num">即<small>日</small></div>
                    <h4>その日から使える</h4>
                    <p>難しい設定は不要。アカウントを作ればすぐに入力を始められます。</p>
                </div>
                <div class="easy-card">
                    <div class="num">Excel<small>感覚</small></div>
                    <h4>シンプルな操作</h4>
                    <p>Excelが使えれば操作できます。複雑な機能は一切ありません。</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="cta">
        <h2>まず、使ってみてください</h2>
        <p>紙やExcelでの管理に限界を感じているなら、<br>一度試してみる価値があります。</p>
        <a href="{{ route('login') }}" class="btn-cta">
            今すぐ無料で始める
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
        <p class="cta-note">登録後すぐにお使いいただけます</p>
    </section>

    {{-- フッター --}}
    <footer>
        <p>&copy; {{ date('Y') }} 加工機工具管理システム. All rights reserved.</p>
    </footer>

</body>
</html>
