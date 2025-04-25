# coachtech 勤怠管理アプリ

## 環境構築

1. リポジトリをクローン
```
git clone git@github.com:kobasikibo/coachtech-attendance-management-app.git
cd coachtech-attendance-management-app
```
2. .env.exampleファイルから.envを作成し、環境変数を変更
```
cp .env.example .env
```
``` text
APP_TIMEZONE=Asia/Tokyo
APP_URL=https://localhost

APP_LOCALE=ja
APP_FALLBACK_LOCALE=ja
APP_FAKER_LOCALE=ja_JP

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

SESSION_DRIVER=file

CACHE_STORE=file

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=25
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```
3. 自己署名証明書の作成
```sh
mkdir -p docker/nginx/ssl

openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout docker/nginx/ssl/server.key \
  -out docker/nginx/ssl/server.crt \
  -subj "/C=JP/ST=Tokyo/L=Shibuya/O=Coachtech/CN=localhost"
```
4. コンテナをビルド・起動
```
docker compose up -d --build
```
5. Laravelパッケージのインストール
```
docker compose exec --workdir=/var/www php composer install
```
6. Laravel Fortify をインストール
```
docker compose exec php sh
composer require laravel/fortify
```
7. Laravelアプリケーションの初期設定
```
php artisan key:generate
php artisan migrate --seed
```

**Mailhogの設定**
``` text
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 使用技術(実行環境)
- PHP8.3.11
- Laravel11.23.5
- MySQL9.1.0

## ER図
![ER](src/database/diagrams/er_diagram.png)

## URL
- 開発環境: http://localhost/
- phpMyAdmin: http://localhost:8081/
- MailhogのWebインターフェース: http://localhost:8025/
