# 開発環境：アップグレード
python admin_upgrade.py 'http://localhost' 'test_settings_dev.txt'
# 開発環境：ファイル修正、文字列置換スクリプトによる
docker exec darts_wordpress_1 php /tmp/fanciedarts/rewrite_after_upgrade.php
# 開発環境：自動テスト
python view_test.py 'http://localhost' 'test_settings_dev.txt'
if (!$?) {
    # テスト失敗
    echo '[ERROR] test failed in develop'
    exit
}

# 本番環境：アップグレード
python admin_upgrade.py 'http://192.168.10.70/fanciedarts' 'test_settings_public.txt'
if (!$?) {
    # アップグレードなし
    echo 'no upgrade is available in PUBLIC, end auto upgrader'
    exit
}

# 本番環境：ファイル修正、開発環境のファイルコピーによる
$myPath = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $myPath
$uploadSettingsFilePath = $myPath + '\deploy_settings.txt'
$uploadSettingsFile = New-Object System.IO.StreamReader($uploadSettingsFilePath, [System.Text.Encoding]::GetEncoding("utf-8"))
$localAppDir = $uploadSettingsFile.ReadLine()

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
    $srcFile = $localAppDir + $file
    $dstFile = '\\192.168.10.70\fanciedarts' + $file
    cp $srcFile $dstFile
} 

# 開発環境：自動テスト
python view_test.py 'http://192.168.10.70/fanciedarts' 'test_settings_public.txt'
if (!$?) {
    # テスト失敗
    echo '[ERROR] test failed in PUBLIC'
    exit
}
