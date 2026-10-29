<?php
/**
 * Central place to edit repeated site details (stats, contact info)
 * without hunting through every template file.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'lpd_get_stats' ) ) :
	function lpd_get_stats() {
		return array(
			array( 'number' => '99.9%', 'label' => __( 'Uptime', 'lilypawdigital' ) ),
			array( 'number' => '+15', 'label' => __( 'Domains With Us', 'lilypawdigital' ) ),
			array( 'number' => '+4', 'label' => __( 'Years of Experience', 'lilypawdigital' ) ),
			array( 'number' => '+5000', 'label' => __( 'Emails Sent Through Our Network', 'lilypawdigital' ) ),
		);
	}
endif;
