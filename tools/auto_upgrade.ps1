
$logFilePath = [Environment]::GetFolderPath('Desktop') + '/auto_upgrade.log'

$toolsDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$rootDir = Split-Path -Parent $toolsDir
$appDir = $rootDir + '\app'

function SetUp() {
    start-transcript $logFilePath -append
    Set-Location $rootDir
    docker-compose up -d
}

function TearDown() {
    Set-Location $rootDir
    docker-compose stop
    stop-transcript
}

function WriteLog($tag, $msg) {
    $time = Get-Date -Format "yyyy/MM/dd hh:mm:ss.ffff"
    $logMessage = '[' + $time + '] ' + $tag + '/ ' + $msg
    Write-Host $logMessage
}

WriteLog 'AutoUpgrade' 'starting auto upgrade ...'

SetUp
Set-Location $toolsDir

# 開発環境：アップグレード
WriteLog 'AdminUpgrade' 'upgrading in dev ...'
python admin_upgrade.py 'http://localhost' 'test_settings_dev.txt'
if (!$?) {
    # アップグレードなし
    WriteLog 'AdminUpgrade' 'NO upgrade is available in dev, but auto upgrader continues.'
} else {
    # アップグレードあり
    WriteLog 'AdminUpgrade' 'upgrades in dev succeeded !'
}

# 開発環境：ファイル修正、文字列置換スクリプトによる
WriteLog 'PhpRewrite' 'rewriting and generating files in dev ...'
docker exec darts_wordpress_1 php /tmp/wordpress/rewrite_after_upgrade.php

# 開発環境：自動テスト
WriteLog 'ViewTest' 'testing views in dev ...'
python view_test.py 'http://localhost' 'test_settings_dev.txt'
if (!$?) {
    # テスト失敗
    WriteLog 'ViewTest' '[ERROR] test failed in dev, so auto upgrader stops'
    TearDown
    exit
}
WriteLog 'ViewTest' 'tests in dev succeeded !'

# 本番環境：アップグレード
WriteLog 'AdminUpgrade' 'upgrading in PUBLIC ...'
python admin_upgrade.py 'http://192.168.10.70/fanciedarts' 'test_settings_public.txt'
if (!$?) {
    # アップグレードなし
    WriteLog 'AdminUpgrade' 'NO upgrade is available in PUBLIC, so auto upgrader ends'
    TearDown
    exit
}
WriteLog 'AdminUpgrade' 'upgrades in PUBLIC succeeded !'

# 本番環境：デプロイ、開発環境のファイルコピーによる
WriteLog 'AutoDeploy' 'deploying to PUBLIC ...'
$uploadFiles = @(
    '\wp-includes\general-template.php',
    '\wp-content\themes\dp-fancie-note-child\taxonomy-member_post.php',
    '\wp-content\themes\dp-fancie-note-child\taxonomy-member_office.php',
    '\wp-content\themes\dp-fancie-note-child\article-loop-member_tax.php',
    '\wp-content\themes\dp-fancie-note-child\single-member.php',
    '\wp-content\themes\dp-fancie-note\inc\scr\related_posts.php',
    '\wp-content\plugins\custom-field-template\custom-field-template.php'
)
foreach ($file in $uploadFiles) {
    $srcFile = $appDir + $file
    $dstFile = '\\192.168.10.70\fanciedarts' + $file
    cp $srcFile $dstFile
}
WriteLog 'AutoDeploy' 'deploy to PUBLIC succeeded !'

# 開発環境：ビューのテスト
WriteLog 'ViewTest' 'testing views in PUBLIC ...'
python view_test.py 'http://192.168.10.70/fanciedarts' 'test_settings_public.txt'
if (!$?) {
    # テスト失敗
    WriteLog 'ViewTest' '[ERROR] test failed in PUBLIC, so auto upgrader ends. please check and fix public environment.'
    TearDown
    exit
}
WriteLog 'ViewTest' 'tests in PUBLIC succeeded !'

WriteLog 'AutoUpgrade' 'CONGRATURATIONS ! All process succeeded !'
TearDown
exit
