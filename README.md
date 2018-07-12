# DARTS on Fancie NOTE by DigiPress

## 開発環境構築

リポジトリをクローンし、親フォルダの名前を `darts` に変更する

管理画面にログインし、「BackWPup」＞「バックアップ」からバックアップアーカイブをダウンロードする

アーカイブを解凍し、中身を `darts/backup` に移動する

さらに、アーカイブのトップに格納されている `*.sql.gz` を、 `darts/sql` 内に移動する

`darts/wp-config.php` を参考に、コンテナの環境変数ファイルを作成する

- `darts/db.env.sample` を `darts/db.env` としてコピーし、データベース名とユーザ名・パスワードを記入する
- `darts/wordpress.env.sample` を `darts/wordpress.env` としてコピーし、データベース名とユーザ名・パスワードを記入する

Dockerを起動し、以下コマンドでDockerコンテナを起動する

```
> cd ${darts}
> docker-compose up
```

WordPressコンテナの初期化処理が終了したら、さらに以下コマンドで独自の初期化処理を行う

```
> docker exec darts_wordpress_1 bash /tmp/wordpress/entrypoint-initapp.sh
```

ブラウザで https://localhost/wp-admin からログインし、パーマリンクを変更を加えずに保存する

## アップグレード処理

WordPress本体やテーマ、プラグインの更新が来ている場合は、以下手順でアップグレードを行う

Selenium用のChromeDriverを[公式サイト](http://chromedriver.chromium.org/downloads)から入手し、任意の場所に設置する

自動テスト用環境設定ファイルを作成する

- `darts/tools/test_settings.txt.sample` を `darts/tools/test_settings_dev.txt` としてコピーし、ChromeDriverパスと開発環境でのWordPressの管理画面ログインユーザ名(admin)とパスワード(z)を記入する
- `darts/tools/test_settings.txt.sample` を `darts/tools/test_settings_public.txt` としてコピーし、ChromeDriverパスと本番環境でのWordPressの管理画面ログインユーザ名とパスワードを記入する

自動デプロイ用環境設定ファイルを作成する

- `darts/tools/deploy_settings.txt.sample` を `darts/tools/deploy_settings.txt` としてコピーし、`darts/app` のフルパスを記入する

Dockerコンテナを起動したのち、以下コマンドでアップグレードを行う

```
> cd ${darts}/tools
> ./auto_upgrade.ps1
```
