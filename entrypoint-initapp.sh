#!/bin/bash

# Wp-CLI ツールインストール
mkdir /tmp/wordpress
cd /tmp/wordpress
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp

# WordPressデータをバックアップデータと統合
cd /var/www/html
mv .htaccess .htaccess.back # 初期状態のファイルを退避
mv wp-config.php wp-config.php.back # Dockerにより生成されたファイルを退避
cp -Rf /tmp/wordpress/data/* ./ # バックアップデータをコピー
mv -f .htaccess.back .htaccess # 退避ファイルをリストア
mv -f wp-config.php.back wp-config.php # 退避ファイルをリストア

# WordPressを開発環境に適用
wp option update blogname 'DARTS-Dev' --allow-root
wp option update siteurl 'http://localhost' --allow-root
wp option update home 'http://localhost' --allow-root
wp rewrite flush --allow-root
wp user create admin admin@example.com --role=administrator --user_pass=z --allow-root

# テーマとプラグインの過不足を修正
wp theme delete --allow-root \
    twentyfifteen \
    twentyseventeen \
    twentysixteen 
wp plugin install --allow-root \
    all-in-one-event-calendar \
    backwpup \
    custom-field-template \
    custom-post-type-ui \
    intuitive-custom-post-order \
    media-from-ftp \
    user-activity-log \
    wordpress-importer \
    wp-multibyte-patch
wp plugin deactivate --allow-root \
    backwpup \
    user-activity-log
