<?php
// Pocket subscribers
function dp_get_pocket_subscribers(){
	global $post;

	$key = 'count_pk_' . get_the_ID();
	$count = get_transient( $key );
	if ($count !== false) return $count;

	$url = rawurlencode( get_permalink() );
	$res = '0';
	$count = wp_remote_get( 'https://widgets.getpocket.com/v1/button?label=pocket&count=vertical&v=1&url='.$url.'&src='.$url );

	if ( !is_wp_error( $count ) && $count['response']['code'] === 200 ) {
		$doc = new DOMDocument( '1.0', 'UTF-8' );
		$doc->preserveWhiteSpace = false;
		$doc->loadHTML( $count['body']);
		$xpath = new DOMXPath( $doc );
		$count = $xpath->query( '//em[@id = "cnt"]' )->item(0);
		$count = isset( $count->nodeValue ) ? $count->nodeValue : '-';
		set_transient( $key, $count, 60 * 60 * 24 );
		$res = $count;
	}
	return $res;
}

// Feedly subscribers
function dp_get_feedly_subscribers(){
	$count = get_transient('feedly_subscribers');
	if ($count !== false) return $count;

	$feed_url = rawurlencode( get_bloginfo( 'rss2_url' ) );
	$res = '0';
	$count = wp_remote_get( "https://cloud.feedly.com/v3/feeds/feed%2F".$feed_url );

	if (!is_wp_error( $count ) && $count["response"]["code"] === 200) {
		$count = json_decode( $count['body'] );
		if ( $count ) {
			$count = $count->subscribers;
			set_transient( 'feedly_subscribers', $count, 60 * 60 * 24 );
			$res = ($count ? $count : '-');
		}
	}
	return $res;
}