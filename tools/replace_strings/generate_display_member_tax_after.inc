// ***********************************
// for FancieDARTS [START]: 社員テンプレート情報の表示用HTML生成
// ***********************************
$member_notation = post_custom($DARTS_MEMBER_TAX_NOTATION);
$member_pronounciation = post_custom($DARTS_MEMBER_TAX_PRONOUNCIATION);
$member_joined_raw = post_custom($DARTS_MEMBER_TAX_JOINED);
if ($member_joined_raw) {
	$member_joined = date('d/m/Y', strtotime($member_joined_raw));
}
$member_post_list = get_the_terms($post->ID, $DARTS_MEMBER_TAX_POST);
asort($member_post_list);
$html_member_post_list = "";
$num = 0;
foreach ($member_post_list as $key => $val) {
	if ($num >= 1) $html_member_post_list .= ' / ';
	$html_member_post_list .= '<a href="' .get_term_link($val->slug , $DARTS_MEMBER_TAX_POST) .'">';
	$html_member_post_list .= $val->name .'</a>';
	$num++;
}
$member_office_list = get_the_terms($post->ID, $DARTS_MEMBER_TAX_OFFICE);
asort($member_office_list);
$html_member_office_list = "";
$num = 0;
foreach ($member_office_list as $key => $val) {
	if ($num >= 1) $html_member_office_list .= ' / ';
	$html_member_office_list .= '<a href="' .get_term_link($val->slug , $DARTS_MEMBER_TAX_OFFICE) .'">';
	$html_member_office_list .= $val->name .'</a>';
	$num++;
}
// ***********************************
// for FancieDARTS [END]: 社員テンプレート情報の表示用HTML生成
// ***********************************

// **********************************
// show posts
