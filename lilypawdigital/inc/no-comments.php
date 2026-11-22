<?php
/**
 * Comments are switched off across the whole site (owner's request):
 * no comment forms, no existing comments shown, no pingbacks or
 * trackbacks, and nothing about comments in the dashboard. Nothing is
 * deleted, so removing this file brings back any comments left before.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Closed everywhere, whatever the post or page's own setting says.
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

// Hide comments that already exist.
add_filter( 'comments_array', '__return_empty_array', 10 );
add_filter( 'get_comments_number', '__return_zero' );

// Stop new comments arriving by any route, including direct posts to
// wp-comments-post.php and XML-RPC pingbacks.
add_action(
	'pre_comment_on_post',
	function () {
		wp_die( esc_html__( 'Comments are closed.', 'lilypawdigital' ), '', array( 'response' => 403 ) );
	}
);
add_filter(
	'xmlrpc_methods',
	function ( $methods ) {
		unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );
		return $methods;
	}
);
add_filter(
	'wp_headers',
	function ( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
);

// No comment feeds.
add_filter( 'feed_links_show_comments_feed', '__return_false' );

// Remove comment and trackback support from every post type, and tidy the
// dashboard: no Comments menu, no comments in the toolbar, no Recent
// Comments box, and the comments screen sends you back to the dashboard.
add_action(
	'init',
	function () {
		foreach ( get_post_types() as $type ) {
			if ( post_type_supports( $type, 'comments' ) ) {
				remove_post_type_support( $type, 'comments' );
				remove_post_type_support( $type, 'trackbacks' );
			}
		}
	},
	100
);
add_action(
	'admin_menu',
	function () {
		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	}
);
add_action(
	'admin_init',
	function () {
		global $pagenow;
		if ( in_array( $pagenow, array( 'edit-comments.php', 'comment.php', 'options-discussion.php' ), true ) ) {
			wp_safe_redirect( admin_url() );
			exit;
		}
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}
);
add_action(
	'admin_bar_menu',
	function ( $bar ) {
		$bar->remove_node( 'comments' );
	},
	999
);
add_action(
	'widgets_init',
	function () {
		unregister_widget( 'WP_Widget_Recent_Comments' );
	}
);
