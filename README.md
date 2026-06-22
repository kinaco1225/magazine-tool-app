# magazine-tool（加工機工具管理）

## 環境構築
### Docker のビルドからマイグレーション、シーディングまでを行い開発環境を構築
- `docker-compose up -d --build` コンテナが作成
- `docker-compose exec php bash` PHPコンテナ内にログイン
- `composer install` をインストール
- `cp .env.example .env` ファイルをコピー(.env作成)
- `.env` の設定変更
- `php artisan key:generate`  アプリキー生成
- `php artisan migrate --seed` によりデータベースをセットアップ
---
## テスト用ユーザー情報
#### 本アプリには 動作確認用のテストユーザーとして、管理者と作業者の2名用意しています。いずれも 同一のパスワードでログイン可能です。
##### 管理者
- login-email `admin@example.com`
- login-password `password`
##### 作業者
- login-email `worker@example.com`
- login-password `password`
- -- 
## テスト実行方法
#### 本アプリでは Feature テストを用意しています。
- `php artisan test --testsuite=Feature`テスト実行
---
## 使用技術（実行環境）
- PHP 8.3
- Laravel 13.x
- MySQL 8.0
- WSL2 + Docker（開発環境）
