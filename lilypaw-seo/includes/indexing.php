<?php
/**
 * What search engines should and shouldn't index. Thin or duplicate pages
 * (tag, author and date archives, search results, attachment pages) are
 * kept out of search results so Google spends its attention on the pages
 * that matter. Their links are still followed.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_filter(
	'wp_robots',
	function ( $robots ) {
		$post = function_exists( 'lpd_seo_current_post' ) ? lpd_seo_current_post() : null;
		$thin = is_search() || is_tag() || is_author() || is_date() || is_attachment() || is_404();
		if ( $thin || ( $post && get_post_meta( $post->ID, '_lpd_seo_noindex', true ) ) ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
			unset( $robots['max-image-preview'] );
		} elseif ( get_option( 'blog_public' ) ) {
			// Let Google show large picture previews and full snippets.
			$robots['max-image-preview'] = 'large';
			$robots['max-snippet']       = '-1';
		}
		return $robots;
	}
);

/**
 * Author pages show your WordPress username in the address (useful to
 * anyone trying to break in) and repeat the blog. Send them to About Us.
 * Attachment pages go to the file's parent article, or the file itself.
 */
add_action(
	'template_redirect',
	function () {
		if ( is_author() ) {
			$about = get_page_by_path( 'about-us' );
			wp_safe_redirect( $about ? get_permalink( $about ) : home_url( '/' ), 301 );
			exit;
		}
		if ( is_attachment() ) {
			$post   = get_queried_object();
			$target = ( $post && $post->post_parent ) ? get_permalink( $post->post_parent ) : wp_get_attachment_url( $post ? $post->ID : 0 );
			if ( $target ) {
				wp_safe_redirect( $target, 301 );
				exit;
			}
		}
	},
	1
);

// Stop WordPress publishing user names through the REST API to visitors who aren't logged in.
add_filter(
	'rest_endpoints',
	function ( $endpoints ) {
		if ( ! is_user_logged_in() ) {
			unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
		return $endpoints;
	}
);
