<?php 
/*---------------------------------------
 * Javascript for autopager
 *--------------------------------------*/
function showScriptForAutopager($page_num) {
	global $COLUMN_NUM, $ARCHIVE_STYLE, $IS_MOBILE_DP, $options, $options_visual;
	
	$layout = $ARCHIVE_STYLE['layout'];
	$autopagerCode = '';
	$suffix_mb = "";

	if ($IS_MOBILE_DP){
		$suffix_mb = "_mb";
		if (strpos($layout, 'magazine') !== false) {
			$layout = 'normal';
		}
	}

	// ----------------------------------
	// Scripr for Auto pager 
	// ----------------------------------
	if ($options['autopager'.$suffix_mb] && !is_single() && !is_page()) :
		// Params
		$infiniteAddSelector = '.loop-div.autopager';
		$itemSelector 	= '.autopager .loop-article';
		$navSelector	= 'nav.navigation';
		$nextSelector	= '.nav_to_paged a';
		$lastMsg		= 'NO MORE CONTENTS';
		$duration 		= '600';	
		$callback 		= 
'var newElems = j$(this);
newElems.css("opacity",0);
dp_count_shares();
getAnchor();
clickArchiveThumb();';

		// Append callback for masonry style
		if ( ($layout === 'normal' || $layout === 'news') || ($layout === 'blog' && $COLUMN_NUM !== 1)) {
			$callback .= 
'var atPos=newElems.offset().top-60;
setTimeout(function(){
	newElems.animate({opacity:1},'.$duration.');
	j$("body,html").animate({
		scrollTop:atPos},'.$duration.',"easeInOutCubic");
	},400);';
		} else {
			$callback .= 
'var $msnry = j$("'.$infiniteAddSelector.'").masonry();
var dElm=document.documentElement,dBody=document.body ;
var nY=dElm.scrollTop || dBody.scrollTop;';
			if (!$IS_MOBILE_DP) {
				$callback .= 
'setTimeout(function(){
	j$("body,html").animate({scrollTop:nY+50},'.$duration.',"easeInOutCubic");
	$msnry.imagesLoaded(function(){
		$msnry.masonry("appended", newElems);
		newElems.animate({opacity:1},'.$duration.');
	});
},400);';
			} else {
				$callback .= 
'setTimeout(function(){
	j$("body,html").animate({scrollTop:nY+50},'.$duration.',"easeInOutCubic");
},400);';
			}
		}

		// JS
		$autopagerCode = <<< EOD
<script>
j$(function() {
	j$.autopager({
		autoLoad: false,
		content:'$itemSelector',
		appendTo:'$infiniteAddSelector',
		link:'$nextSelector',
		start: function(current, next){
			j$('$navSelector').before('<div id="pager-loading" class="dp_spinner icon-spinner5"></div>');
		},
		load: function(current, next){
			$callback
			j$('#pager-loading').remove();
			if　(current.page >= $page_num)　{
				j$('$navSelector').hide();
				j$('$navSelector').before('<div class="pager_msg_div"><div class="pager_last_msg">$lastMsg</div></div>');
				j$('.pager_msg_div').fadeIn();
				setTimeout(function(){
					j$('.pager_msg_div').fadeOut();
				}, 4000);
			}
		}
	});
    j$('$nextSelector').click(function() {
		j$.autopager('load');
		return false;
	});
});
</script>
EOD;
		$autopagerCode = str_replace(array("\r\n","\r","\n","\t"), '', $autopagerCode);
	endif;
	echo $autopagerCode;
}