#!/bin/bash

# Wp-CLI ツールインストール
mkdir /tmp/wordpress
cd /tmp/wordpress
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp

# WordPressデータをバックアップデータと統合
cd /var/www/html
mv wp-admin fanciedarts/wp-admin
mv wp-content fanciedarts/wp-content
mv wp-includes fanciedarts/wp-includes
mv .htaccess fanciedarts/
mv *.php fanciedarts/
mv license.txt fanciedarts/
mv readme.html fanciedarts/
mv fanciedarts/wp-config.php wp-config.php # Dockerにより修正されたファイルを退避
cp -Rf /tmp/fanciedarts/data/* fanciedarts/ # バックアップデータをコピー
mv -f wp-config.php fanciedarts/wp-config.php # Dockerにより修正されたファイルを格納

# WordPressを開発環境に適用
cd /var/www/html/fanciedarts
wp option update blogname 'DARTS-Dev' --allow-root
wp option update siteurl 'http://localhost/fanciedarts' --allow-root
wp option update siteurl 'http://localhost/fanciedarts' --allow-root
wp option update home 'http://localhost/fanciedarts' --allow-root
wp rewrite flush --allow-root
wp user create admin admin@example.com --role=administrator --user_pass=z --allow-root

# テーマとプラグインの過不足を修正
wp theme delete twentyfifteen twentyseventeen twentysixteen --allow-root
wp plugin install wordpress-importer intuitive-custom-post-order backwpup custom-field-template wp-multibyte-patch user-activity-log wp-konami-code custom-post-type-ui --allow-root
wp plugin deactivate backwpup user-activity-log --allow-root
