<?php
/**
 * Display comments
 * @return [type] [description]
 */
function dp_amp_show_comment_list(){
	if (!is_single() && !is_page()) return;
	if (!comments_open()) return;
	global $options; ?>
<section class="comment_section">
<h3 id="comments" class="inside-title"><span class="title"><?php
	if (isset($options['comments_main_title_amp']) && !empty($options['comments_main_title_amp'])) :
		echo htmlspecialchars_decode($options['comments_main_title_amp']);
	else :
		_e('COMMENTS', 'DigiPress');
	endif;?>
</span></h3><?php
	$comments = get_comments(array(
		'post_id' => get_the_ID(),
		'status' => 'approve')
	);
	if (!empty($comments)) :?>
<ol class="commentlist"><?php
		wp_list_comments(array(
			'callback' => 'dp_amp_list_comments_callback',
			'per_page' => 100,
			'type'     => 'comment',
		), $comments);?>
</ol><?php
	else : // No comment yet ?>
<p class="comment"><?php _e('No commented yet.', 'DigiPress') ?></p><?php
	endif; // End of !$comments
echo '<div id="respond" class="leave-comment"><a href="'.get_permalink().'?nonamp=1#respond" class="btn">'.$options['comment_form_title'].'</a></div>'; ?>
</section><?php
}

/**
 * Comment source
 * @param  [type] $comment [description]
 * @param  [type] $args    [description]
 * @param  [type] $depth   [description]
 * @return [type]          [description]
 */
function dp_amp_list_comments_callback ($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	$avatar_img = get_avatar( $comment, 40, '', 'avatar' );
	$avatar_img = preg_replace('/<img ([^>]+?)\/?>/i', '<amp-img $1></amp-img>', $avatar_img);?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
<div id="comment-<?php comment_ID(); ?>">
<div class="comment-author clearfix">
<div class="comment-avatar com-inline"><?php echo $avatar_img; ?></div>
<div class="com-inline meta"><?php
printf(__('<cite class="comment_author_name">By %s</cite>', 'DigiPress'), get_comment_author_link()) ?>
<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php
printf(__('%1$s at %2$s', 'DigiPress'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link('(Edit)','  ','');
comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
</div>
</div>
</div><?php
if ($comment->comment_approved == '0') : ?>
<p><?php _e('Your comment is awaiting moderation.', 'DigiPress') ?></p><?php
endif;
echo get_comment_text(); ?>
</div><?php
}

/**
 * [dp_amp_facebook_comment_box description]
 * @return [type] [description]
 */
function dp_amp_facebook_comment_box() {
	if (post_password_required() && !empty($post->post_password)) {
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {
			return;
		}
	}
	if ( get_post_meta(get_the_ID(), 'dp_hide_fb_comment', true) ) return;
	global $options;
	if ( ($options['facebookcomment'] && is_single()) || ($options['facebookcomment_page'] && is_page()) ) {
		// Main title
		$fb_comment_title = isset($options['fb_comments_title']) && !empty($options['fb_comments_title']) ? htmlspecialchars_decode($options['fb_comments_title']) : __('COMMENT ON FACEBOOK', 'DigiPress');
		$fb_comment_title = '<h3 class="inside-title"><span>'.$fb_comment_title.'</span></h3>';

		echo '<div class="dp_fb_comments_div">'.$fb_comment_title.'<amp-facebook-comments width=486 height=657 layout="responsive" data-numposts="'.$options['number_fb_comment'].'" data-href="'.get_permalink().'"></amp-facebook-comments></div>';
	}
}