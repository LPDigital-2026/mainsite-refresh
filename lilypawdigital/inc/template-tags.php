<?php
/**
 * Custom template tags
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'lpd_posted_on' ) ) :
	function lpd_posted_on() {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);
		echo '<span class="posted-on">' . $time_string . '</span>'; // phpcs:ignore
	}
endif;

if ( ! function_exists( 'lpd_entry_footer' ) ) :
	function lpd_entry_footer() {
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( __( 'Leave a comment', 'lilypawdigital' ), __( '1 Comment', 'lilypawdigital' ), __( '% Comments', 'lilypawdigital' ) );
			echo '</span>';
		}
	}
endif;
