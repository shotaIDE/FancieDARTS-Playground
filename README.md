# Fancie DARTS
各項目に進む前に、開発PCにて以下を実施する（1度だけで良い）

開発PCにDockerの環境を用意する

リポジトリを開発用PCにクローンする

## 開発環境構築
本番環境の管理画面にログインし、「BackWPup」＞「バックアップ」からバックアップアーカイブをダウンロードする

アーカイブを解凍し、`wp-content/`の中身をリポジトリの`app/fanciedarts/wp-content/`に移動する

さらに、アーカイブのトップに格納されている `*.sql.gz` を、 `sql/` 内に移動する

以下コマンドでコンテナを起動し、DBのコンテナログを監視する

```
docker-compose up -d
docker-compose logs -f db
```

すでにコンテナを起動したことがあり、一旦破棄する場合は、以下コマンドを事前に実行しておく

```
docker-compose stop
docker-compose rm -f
rm app/fanciedarts/wp-config.php
```

DBのコンテナログに「ready for connections」と表示されたら、Ctrl+C(Command+C)にてログ監視を終了し、以下コマンドでWordPressサイトの初期化処理を行う  
※クローン時の設定で改行コードがCR+LFになっている場合は上手く動かないので、LFに修正する

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

## 新しいバージョンの更新手順
WordPress本体・テーマ・プラグインは定期的に新しいバージョンが公開されるので、追従する必要がある  
しかし、DARTSでは各ソースコードを編集してカスタマイズしているため、単純に新しいバージョンを適用しただけでは、カスタマイズが無効化されてしまう  
そのため、新しいバージョン適用時に常にソースコード編集が必要になるが、その手順を自動で実施するスクリプトを用意しているので、ある程度は自動で実施できる

**更新**

以下ページから、WordPress本体・プラグイン・テーマ等を更新する  
http://localhost:10780/fanciedarts/wp-admin/update-core.php

**ソースコード編集**

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

**リグレッションテストを実施する**
_Pythonの環境が必要だが、手動でもそこまで時間がかからないため、方法は適宜選択する_

Pythonの環境を準備する

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
