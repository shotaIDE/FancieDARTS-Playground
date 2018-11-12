
import sys
import argparse

import re

import unittest
from selenium import webdriver
from selenium.common.exceptions import NoSuchElementException

class FancieDartsViewTest(unittest.TestCase):
    def setUp(self):
        self.driver = webdriver.Chrome(CHROME_DRIVER_PATH)

    # FIXME: テストコンテンツが準備できていないので、テスト対象から外している
    def tmp_test_taxnomy_archive_page(self):
        self.driver.get(SERVER_HOST + '/member_post/devifbc/')

        element = self.driver.find_element_by_class_name("hd-title")
        self.assertIsNone(
            re.search("所属部署.+", element.text),
            'タイトルにタクソノミーラベルがついていない')

        element = self.driver.find_element_by_class_name("fancie_darts_taxonomy_name")
        self.assertEqual(
            element.text,
            'iFBC/i-フィルター課',
            '所属部署説明欄のタイトルが表示されている')

        element = self.driver.find_element_by_class_name("fancie_darts_taxonomy_description")
        self.assertIsNotNone(
            element,
            '所属部署説明欄の説明が存在する')

        self.driver.get(SERVER_HOST + '/member_office/hq/')

        element = self.driver.find_element_by_class_name("hd-title")
        self.assertIsNone(
            re.search("営業所.+", element.text),
            'タイトルにタクソノミーラベルがついていない')

        element = self.driver.find_element_by_class_name("fancie_darts_taxonomy_name")
        self.assertEqual(
            element.text,
            '本社',
            '営業所説明欄のタイトルが表示されている')

        element = self.driver.find_element_by_class_name("fancie_darts_taxonomy_description")
        self.assertIsNotNone(
            element,
            '営業所説明欄の説明が存在する')

    def test_member_single_page(self):
        self.driver.get(SERVER_HOST + '/member/member-0-40/')

        element = self.driver.find_element_by_class_name("fancie_darts_member_image")
        self.assertEqual(
            element.get_attribute('width'),
            '360',
            'アイキャッチ画像の幅が正しい')

        element = self.driver.find_element_by_class_name("fancie_darts_member_description")
        self.assertIsNotNone(
            element,
            'カスタムフィールドに入力した社員の情報テーブルが存在する')

        with self.assertRaises(NoSuchElementException,
            msg='社員情報ページに関連する記事が表示されていない'):
            self.driver.find_element_by_class_name("dp_related_posts")

    def test_custom_field_templete(self):
        self.driver.get(SERVER_HOST + '/wp-admin/post.php?post=26404&action=edit')

        # ログイン
        element = self.driver.find_element_by_id('user_login')
        element.send_keys(LOGIN_USER)
        element = self.driver.find_element_by_id('user_pass')
        element.send_keys(LOGIN_PASSWORD)
        element = self.driver.find_element_by_id('wp-submit')
        element.click()

        el_cft = self.driver.find_element_by_class_name('cft')
        el_cft_ps = el_cft.find_elements_by_tag_name('p')
        for el_cft_p in el_cft_ps:
            self.assertNotIn(
                'label',
                el_cft_p.get_attribute('class'),
                'カスタムフィールドテンプレートの項目名にクラス名 label がついていない')

    def tearDown(self):
        self.driver.close()

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('server_host')
    parser.add_argument('settings_path')
    arguments = parser.parse_args()

    SERVER_HOST = arguments.server_host

    settings = open(arguments.settings_path, 'r').readlines()
    CHROME_DRIVER_PATH = settings[0][:-1]
    LOGIN_USER = settings[1][:-1] # 改行の削除
    LOGIN_PASSWORD = settings[2][:-1] # 改行の削除

    suite = unittest.TestSuite()
    suite.addTests(unittest.makeSuite(FancieDartsViewTest))
    result = unittest.TextTestRunner().run(suite)

    num_failed = len(result.errors) + len(result.failures)
    sys.exit(num_failed)
