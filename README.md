# DARTS on Fancie NOTE by DigiPress

## 開発環境構築

開発用PCにDockerの環境を用意する

リポジトリを開発用PCにクローンする

本番DARTSの管理画面にログインし、「BackWPup」＞「バックアップ」からバックアップアーカイブをダウンロードする

アーカイブを解凍し、`wp-content/`の中身をリポジトリの`app/fanciedarts/wp-content/`に移動する

さらに、アーカイブのトップに格納されている `*.sql.gz` を、 `darts/sql` 内に移動する

サンプルファイルを元に開発用の環境変数ファイルを作成する

- `darts/db.env.sample` を `darts/db.env` としてコピーし、データベース名とユーザ名・パスワードを記入する
- `darts/wordpress.env.sample` を `darts/wordpress.env` としてコピーし、データベース名とユーザ名・パスワードを記入する

以下コマンドでDockerコンテナを起動する

```
cd ${ClonedDir}
docker-compose up
```

WordPressコンテナの初期化処理が終了したら、さらに以下コマンドで独自の初期化処理を行う  
_クローン時の設定で改行コードがCR+LFになっている場合は上手く動かないので、LFに修正する_

```
cd ${ClonedDir}
PROJECT_HOME_DIR=`pwd`
docker run --rm -it \
    --volumes-from=fanciedarts-web \
    --volume="$PROJECT_HOME_DIR/entrypoint-initapp.sh:/tmp/entrypoint-initapp.sh" \
    --workdir="/var/www/html/fanciedarts" \
    --env-file="./db.env" \
    --net=container:fanciedarts-web \
    wordpress:cli \
    sh /tmp/entrypoint-initapp.sh
```

ブラウザで http://localhost:10780/fanciedarts にアクセスし、正常に閲覧できるかを確かめる

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
