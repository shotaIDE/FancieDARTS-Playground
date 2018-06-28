
import sys
import argparse

from selenium import webdriver

parser = argparse.ArgumentParser()
parser.add_argument('server_host')
parser.add_argument('settings_path')
arguments = parser.parse_args()

SERVER_HOST = arguments.server_host
SETTINGS_PATH = arguments.settings_path
WP_PATH_ADMIN_TOP = "/wp-admin"
WP_PATH_UPGRADE = "/wp-admin/update-core.php"

settings = open(SETTINGS_PATH, 'r').readlines()
chrome_driver_path = settings[0][:-1] # 改行の削除
login_user = settings[1][:-1] # 改行の削除
login_password = settings[2][:-1] # 改行の削除

browser = webdriver.Chrome(chrome_driver_path)

browser.get(SERVER_HOST + WP_PATH_ADMIN_TOP)

el1 = browser.find_element_by_id('user_login')
el1.send_keys(login_user)
el1 = browser.find_element_by_id('user_pass')
el1.send_keys(login_password)
el1 = browser.find_element_by_id('wp-submit')
el1.click()

num_upgrade = 0

while num_upgrade < 3:
    browser.get(SERVER_HOST + WP_PATH_UPGRADE)

    # WordPressの更新
    el1 = browser.find_element_by_id('upgrade')
    wp_upgrade_button_text = el1.get_attribute('value')
    if wp_upgrade_button_text == '今すぐ更新':
        el1.click()
        num_upgrade += 1
        continue

    # プラグインの更新
    try:
        el1 = browser.find_element_by_id('plugins-select-all')
        el1.click()
        el1 = browser.find_element_by_id('upgrade-plugins')
        el1.click()
        num_upgrade += 1
        continue
    except:
        pass

    # テーマの更新
    try:
        el1 = browser.find_element_by_id('themes-select-all')
        el1.click()
        el1 = browser.find_element_by_id('upgrade-themes')
        el1.click()
        num_upgrade += 1
        continue
    except:
        pass

    break

browser.quit()

is_upgrade = num_upgrade >= 1
sys.exit(0 if is_upgrade else 1)
