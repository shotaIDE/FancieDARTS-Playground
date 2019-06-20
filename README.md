# Fancie DARTS

## 開発環境構築

開発用PCにDockerの環境を用意する

リポジトリを開発用PCにクローンする

本番DARTSの管理画面にログインし、「BackWPup」＞「バックアップ」からバックアップアーカイブをダウンロードする

アーカイブを解凍し、`wp-content/`の中身をリポジトリの`app/fanciedarts/wp-content/`に移動する

さらに、アーカイブのトップに格納されている `*.sql.gz` を、 `darts/sql` 内に移動する

サンプルファイルを元に開発用の環境変数ファイルを作成する

- `darts/db.env.sample` を `darts/db.env` としてコピーし、データベース名とユーザ名・パスワードを記入する

以下コマンドでコンテナを起動する

```
cd ${ClonedDir}
docker-compose stop
docker-compose rm -f
rm app/fanciedarts/wp-config.php
docker-compose up -d
```

コンテナの初回起動時はDBのデータ投入処理が行われるため、1分程度待ったのち以下コマンドでWordPressサイトの初期化処理を行う  
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
以下の手順で実施する

1. 開発環境のWordPress管理画面で各種更新をかける
2. 更新により消えたカスタマイズ処理を復元する
3. リグレッションテストを実施する
4. リモートリポジトリにPUSHする
5. 本番サーバにてリモートリポジトリからPULLする
6. 本番サーバのリグレッションテストを実施する

### 開発環境のWordPress管理画面で各種更新をかける
以下ページから、WordPress本体・プラグイン・テーマ等を更新する
http://localhost:10780/fanciedarts/wp-admin/update-core.php

### 更新により消えたカスタマイズ処理を復元する
以下コマンドにて、ファイル書き換え処理を実施する

```
PROJECT_HOME_DIR=`pwd`
docker run --rm -it \
    --volumes-from=fanciedarts-web \
    --volume="$PROJECT_HOME_DIR/tools/replace_code.php:/tmp/wordpress/replace_code.php" \
    --volume="$PROJECT_HOME_DIR/tools/replace_strings:/tmp/wordpress/replace_strings" \
    --net=container:fanciedarts-web \
    php:7.3.6-cli-alpine3.9 \
    php /tmp/wordpress/replace_code.php
```

### リグレッションテストを実施する
**自動で実施する場合**
_Pythonの環境が必要だが、手動でもそこまで時間がかからないため、方法は適宜選択する_

Pythonの環境を作成する

以下コマンドにて、依存パッケージをインストールする

```
cd tools
pip install -r requirements.txt
```

Selenium用のChromeDriverを[公式サイト](http://chromedriver.chromium.org/downloads)から入手し、任意の場所に設置する

`tools/test_settings.txt.sample`を`tools/test_settings_dev.txt`としてコピーし、ChromeDriverパスと開発環境でのWordPressの管理画面ログインユーザ名(admin)とパスワード(z)を記入する

以下コマンドでテストを実施し、結果が全てOKになることを確認する
NGがある場合は、カスタマイズ処理が正常に完了していないので、適宜対応する

```
cd tools
python view_test.py localhost:10780/fanciedarts test_settings_dev.txt
```

**手動で実施する場合**
（整備中）
