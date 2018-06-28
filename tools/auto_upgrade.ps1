
# 開発環境：アップグレード
python admin_upgrade.py 'http://localhost'
# 開発環境：ファイル修正、文字列置換スクリプトによる
docker exec darts_wordpress_1 php /tmp/fanciedarts/rewrite_after_upgrade.php
# 開発環境：自動テスト
python view_test.py 'http://localhost' 'settings.txt'
# TODO:ここで引っかかった場合は、以下処理を中断する
exit

# 本番環境：アップグレード

# 本番環境：ファイル修正、開発環境のファイルコピーによる
