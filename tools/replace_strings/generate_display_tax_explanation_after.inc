// ***********************************
// for FancieDARTS [START]: タクソノミーの説明を表示
// ***********************************
$term_code = '';
$term = get_queried_object();
$term_name = $term->name;
$term_description = term_description(); // 整形されたデータ取得のため、$term->description は利用しない
if ($term_description !== '') {
	$term_code = sprintf('<div class="widget-content bottom clearfix"><div id="dprecentcustompostswidget-2" class="widget-box dp_recent_custom_posts_widget slider_fx"><h3 class="fancie_darts_taxonomy_name inside-title wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;"><span>%s</span></h3>
		<div class="fancie_darts_taxonomy_description entry entry-content" style="padding-left: 10px">%s</div></div></div>',
		$term_name,
		$term_description);
}
echo $term_code;
// ***********************************
// for FancieDARTS [END]: タクソノミーの説明を表示
// ***********************************
/**
 * Show post list
