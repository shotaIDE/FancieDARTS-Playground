# DARTS on Fancie NOTE by DigiPress

## 開発環境構築

- 記事やメディアを含む全てが本番環境と同じ環境を構築
- システムのみ本番環境と同じ環境を構築

### 記事やメディアを含む全てが本番環境と同じ環境を構築

1. LANP環境を構築
2. WP-CLIツールを有効化（[インストール手順](http://wp-cli.org/ja/)）
3. リポジトリをクローン

4. WordPressコアファイルをインストール
```
$ cd /path/to/repository
$ wp core download --locale=ja
```

5. MySQLにWordPress用の空のデータベースとユーザを作成
```
$ mysql -u root -p
mysql> CREATE DATABASE darts_wp DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
mysql> CREATE USER 'darts'@'localhost' IDENTIFIED BY 'z';
mysql> GRANT ALL PRIVILEGES ON darts_wp.* TO 'darts'@'localhost';
mysql> FLUSH PRIVILEGES;
mysql> exit
```

6. 本番環境からデータベースの内容をコピー

本番環境から[データベース](https://wpdocs.osdn.jp/%E3%83%87%E3%83%BC%E3%82%BF%E3%83%99%E3%83%BC%E3%82%B9%E3%81%AE%E3%83%90%E3%83%83%E3%82%AF%E3%82%A2%E3%83%83%E3%83%97)をエクスポートし，`public.sql`に保存
```
$ mysql -u root -p darts_wp < public.sql
```

7. WordPressの設定ファイル`wp-config.php`を生成
```
$ wp core config --dbname=darts_wp --dbuser=darts --dbpass=z --dbhost=localhost --dbprefix=wp_
```

8. WordPressのファイル書き込みシステムを変更

`wp-config.php`ファイルに以下を追記
```
define('FS_METHOD', 'direct');
```

9. 有効化済みのサイトの設定を開発環境用に修正
```
$ wp option update blogname 'DARTS Dev"
$ wp option update siteurl 'http://localhost'
$ wp option update home 'http://localhost'
```

10. 親テーマと専用プラグインを追加
```
$ cp -r /path/to/downloadfile/dp-fancie-note/ wp-content/themes/
$ cp -r /path/to/downloadfile/dp-ex-popular-posts/ wp-content/plugins/
$ cp -r /path/to/downloadfile/dp-ex-simple-rating/ wp-content/plugins/
$ cp -r /path/to/downloadfile/shortcodes-for-digipress/ wp-content/plugins/
```

11. プラグインを追加
```
$ wp plugin install custom-post-type-ui custom-field-template wordpress-importer
$ wp plugin install backwpup user-activity-log
```

12. 不要なテーマとプラグインを削除
```
$ wp theme delete twentyfifteen twentyseventeen twentysixteen
$ wp plugin delete akismet
$ rm wp-content/languages/plugins/akismet-ja.*
```

13. アップロードファイルをコピー

本番環境からアップロードファイル`/wp-content/uploads/`をダウンロードし，開発環境にコピー

14. リンク制御を生成

`localhost`をブラウザで表示し，管理画面からパーマリンク設定を上書き保存することで，`.htaccess`ファイルを作成

#### 補足情報

- ログインユーザを追加
```
$ wp user create ide ide@gmail.com --role=administrator
```

### システムのみ本番環境と同じ環境を構築

1. LANP環境を構築
2. WP-CLIツールを有効化（[インストール手順](http://wp-cli.org/ja/)）
3. リポジトリをクローン

4. WordPressコアファイルをインストール
```
$ cd /path/to/repository
$ wp core download --locale=ja
```

6. WordPressの設定ファイル`wp-config.php`を生成
```
$ wp core config --dbname=darts_wp --dbuser=darts --dbpass=z --dbhost=localhost --dbprefix=wp_
```

7. WordPressのファイル書き込みシステムを変更

`wp-config.php`ファイルに以下を追記
```
define('FS_METHOD', 'direct');
```

8. WordPressを有効化
```
$ wp core install --url=http://localhost --title='DARTS Dev' --admin_user=ide --admin_password=z --admin_email=shota.ide@daj.co.jp
```

9. 親テーマと専用プラグインを追加
```
$ cp -r /path/to/downloadfile/dp-fancie-note/ wp-content/themes/
$ cp -r /path/to/downloadfile/dp-ex-popular-posts/ wp-content/plugins/
$ cp -r /path/to/downloadfile/dp-ex-simple-rating/ wp-content/plugins/
$ cp -r /path/to/downloadfile/shortcodes-for-digipress/ wp-content/plugins/
```

10. プラグインを追加
```
$ wp plugin install custom-post-type-ui custom-field-template wordpress-importer
$ wp plugin install backwpup user-activity-log
```

11. 不要なテーマとプラグインを削除
```
$ wp theme delete twentyfifteen twentyseventeen twentysixteen
$ wp plugin delete akismet
$ rm wp-content/languages/plugins/akismet-ja.*
```
