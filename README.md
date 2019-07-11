# FancieDARTS-Playground
DARTSの開発環境用リポジトリ

環境構築の前提としてDockerの環境が必要なので、インストールしておく

## 開発環境構築
本番環境の管理画面にログインし、「BackWPup」＞「バックアップ」からバックアップアーカイブをダウンロードする

アーカイブを解凍し、`wp-content/` の中身をリポジトリの `app/fanciedarts/wp-content/` に上書きする

さらに、アーカイブのトップに格納されている `*.sql.gz` を、 `sql/` 内にコピーする

以下コマンドでコンテナを起動し、DBのコンテナログを監視する

```
docker-compose up -d
docker-compose logs -f db
```

すでにコンテナを起動したことがある場合は、以下コマンドを事前に実行しておく

```
docker-compose stop
docker-compose rm -f
rm app/fanciedarts/wp-config.php
```

DBのコンテナログに「ready for connections」と表示されたら、Ctrl+Cにてログ監視を終了し、以下コマンドでWordPressサイトの初期化処理を行う  
※クローン時の設定で改行コードがCR+LFになっている場合は動かないので、LFに修正する

```
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

各記事に遷移したらNotFoundと表示される場合は、パーマリンク設定を貼り直す

1. http://localhost:10780/fanciedarts/wp-admin にアクセスし、admin/z でログイン
2. 設定＞パーマリンク設定から、何も設定を変更せずに「変更を保存」ボタンをクリックする

## 新しいバージョンの更新手順
WordPress本体・テーマ・プラグインは定期的に新しいバージョンが公開されるので、追従する必要がある  
しかし、FancieDARTSではソースコードを編集してカスタマイズしているため、単純に新しいバージョンを適用しただけでは、カスタマイズが無効化されてしまう  
そのため、新しいバージョン適用時に常にソースコード編集が必要になるが、その手順を自動で実施するスクリプトを用意しているので、ある程度は自動で実施できる

**更新**

以下ページから、WordPress本体・プラグイン・テーマ等を更新する  
http://localhost:10780/fanciedarts/wp-admin/update-core.php

**ソースコード編集**

以下コマンドにて、ソースコード書き換えスクリプトを実行する

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
