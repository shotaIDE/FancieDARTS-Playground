# 基本データを更新
wp option update blogname 'DARTS-Dev'
wp option update siteurl 'http://localhost:10780/fanciedarts'
wp option update home 'http://localhost:10780/fanciedarts'
wp config set \
    AUTOMATIC_UPDATER_DISABLED true --raw --type=constant
wp rewrite structure \
    '/%category%/%post_id%/'

# 開発環境用のAdminユーザを作成
wp user create admin admin@example.com --role=administrator --user_pass=z

# 開発環境用にプラグインの有効状態を変更
wp plugin deactivate \
    backwpup \
    user-activity-log
