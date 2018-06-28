
import sys
import argparse

from selenium import webdriver

SETTINGS_FILE_PATH = "settings.txt"
MAX_WAIT_TIME = 30 # [s]

WP_PATH_ADMIN_TOP = "/wp-admin"
WP_PATH_UPGRADE = "/wp-admin/update-core.php"

settings = open(SETTINGS_FILE_PATH, 'r').readlines()
chrome_driver_path = settings[0][:-1] # 改行の削除
login_user = settings[1][:-1] # 改行の削除
login_password = settings[2][:-1] # 改行の削除

parser = argparse.ArgumentParser()
parser.add_argument('server_host')
arguments = parser.parse_args()

SERVER_HOST = arguments.server_host

browser = webdriver.Chrome(chrome_driver_path)

browser.get(SERVER_HOST + WP_PATH_ADMIN_TOP)

el1 = browser.find_element_by_id('user_login')
el1.send_keys(login_user)
el1 = browser.find_element_by_id('user_pass')
el1.send_keys(login_password)
el1 = browser.find_element_by_id('wp-submit')
el1.click()

action_count = 0

while action_count < 3:
    browser.get(SERVER_HOST + WP_PATH_UPGRADE)

    # WordPressの更新
    el1 = browser.find_element_by_id('upgrade')
    wp_upgrade_button_text = el1.get_attribute('value')
    if wp_upgrade_button_text == '今すぐ更新':
        el1.click()
        action_count += 1
        continue

    # プラグインの更新
    try:
        el1 = browser.find_element_by_id('plugins-select-all')
        el1.click()
        el1 = browser.find_element_by_id('upgrade-plugins')
        el1.click()
        action_count += 1
        continue
    except:
        pass

    # テーマの更新
    # TODO: テーマの更新部分実装
    # try:
    #     action_count += 1
    #     continue
    # except:
    #     pass

    break

browser.quit()
