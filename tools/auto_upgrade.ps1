# 開発環境：アップグレード
python admin_upgrade.py 'http://localhost' 'settings_dev.txt'
# 開発環境：ファイル修正、文字列置換スクリプトによる
docker exec darts_wordpress_1 php /tmp/fanciedarts/rewrite_after_upgrade.php
# 開発環境：自動テスト
$testResult = (python view_test.py 'http://localhost' 'settings_dev.txt')
# TODO:ここで引っかかった場合は、以下処理を中断する

# 本番環境：アップグレード
python admin_upgrade.py 'http://192.168.10.70/fanciedarts' 'settings_public.txt'

# 本番環境：ファイル修正、開発環境のファイルコピーによる
$files = @(
    '\wp-includes\general-template.php',
    '\wp-content\themes\dp-fancie-note-child\taxonomy-member_post.php',
    '\wp-content\themes\dp-fancie-note-child\taxonomy-member_office.php',
    '\wp-content\themes\dp-fancie-note-child\article-loop-member_tax.php',
    '\wp-content\themes\dp-fancie-note-child\single-member.php',
    '\wp-content\themes\dp-fancie-note\inc\scr\related_posts.php',
    '\wp-content\plugins\custom-field-template\custom-field-template.php'
)
foreach ($file in $files) {
    $srcFile = 'E:\works\darts\app' + $file
    $dstFile = '\\192.168.10.70\fanciedarts' + $file
    cp $srcFile $dstFile
} 

# 開発環境：自動テスト
$testResult = (python view_test.py 'http://192.168.10.70/fanciedarts' 'settings_public.txt')
# TODO:ここで引っかかった場合は、アラートを発生させる
