# DBとの紐付け
wp config create \
    --dbname=$MYSQL_DATABASE \
    --dbuser=$MYSQL_USER \
    --dbpass=$MYSQL_PASSWORD \
    --dbhost='db:3306'

# 基本データを設定
wp option update blogname 'DARTS-Dev'
WP_URL='http://localhost:10780/fanciedarts'
wp option update siteurl $WP_URL
wp option update home $WP_URL
wp config set \
    AUTOMATIC_UPDATER_DISABLED true --raw --type=constant

# パーマリンクの貼り直し
wp rewrite structure \
    '/%category%/%post_id%/'

# 開発環境用のAdminユーザを作成
wp user create admin admin@example.com --role=administrator --user_pass=z

# 開発環境用にプラグインの有効状態を変更
wp plugin deactivate \
    backwpup \
    user-activity-log
