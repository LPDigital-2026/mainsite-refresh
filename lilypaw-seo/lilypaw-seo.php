<?php
/**
 * Plugin Name: Lily Paw SEO
 * Description: Sitemaps for Google and Bing, page titles and descriptions, link previews and sensible indexing rules for lilypaw.digital.
 * Version: 1.0.9
 * Author: Lily Paw Digital
 * Author URI: https://lilypaw.digital
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * Text Domain: lilypaw-seo
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'LPD_SEO_VERSION', '1.0.9' );
define( 'LPD_SEO_DIR', __DIR__ );

require LPD_SEO_DIR . '/includes/defaults.php';
require LPD_SEO_DIR . '/includes/meta.php';
require LPD_SEO_DIR . '/includes/sitemap.php';
require LPD_SEO_DIR . '/includes/indexing.php';
require LPD_SEO_DIR . '/includes/admin.php';

register_activation_hook(
	__FILE__,
	function () {
		lpd_seo_sitemap_rewrites();
		flush_rewrite_rules();
	}
);
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
