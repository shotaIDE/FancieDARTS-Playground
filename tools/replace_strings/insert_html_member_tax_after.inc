// Show eyecatch image
		// ***********************************
		// for FancieDARTS [START]: 指定サイズでアイキャッチ画像を表示し、社員テンプレート情報を表示
		// ***********************************
		$image_id	= get_post_thumbnail_id();
		$image_data	= wp_get_attachment_image_src($image_id, array($width, $height), true);
		$image_url 	= is_ssl() ? str_replace('http:', 'https:', $image_data[0]) : $image_data[0];
		$img_tag	= '<img src="'.$image_url.'" class="fancie_darts_member_image alignnone" alt="'.strip_tags(get_the_title()).'" width="'.$DARTS_MEMBER_IMG_WIDTH.'" />';
		echo '<p>' . $img_tag . '</p>';

		if ($member_notation || $member_joined || $html_member_office_list || $html_member_post_list) {
			$member_template_info_code = '<table class="fancie_darts_member_description"><tbody>';
			if ($member_notation) {
				$member_template_info_code .= '<tr><th class="al-c">Name</th>'
					. '<td>'
						. $member_notation
						. (($member_pronounciation) ? '（' . $member_pronounciation . '）' : '' )
					. '</td>'
					. '</tr>';
			}
			if ($member_joined) {
				$member_template_info_code .= '<tr><th class="al-c">Joined</th>' . '<td>' . $member_joined . '</td></tr>';
			}
			if ($html_member_office_list) {
				$member_template_info_code .= '<tr><th class="al-c">Office</th>' . '<td>' . $html_member_office_list . '</td></tr>';
			}
			if ($html_member_post_list) {
				$member_template_info_code .= '<tr><th class="al-c">Post</th>' . '<td>' . $html_member_post_list . '</td></tr>';
			}
			$member_template_info_code .= '</tbody></table>';
			echo $member_template_info_code;
		}
		// ***********************************
		// for FancieDARTS [END]: 指定サイズでアイキャッチ画像を表示し、社員テンプレート情報を表示
		// ***********************************
		// Content
