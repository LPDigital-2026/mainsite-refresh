<?php
/**
 * Lily Paw Digital theme functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'LPD_VERSION', '4.4' );

function lpd_setup() {
	load_theme_textdomain( 'lilypawdigital', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'lilypawdigital' ),
			'footer'  => __( 'Footer Menu', 'lilypawdigital' ),
		)
	);
}
add_action( 'after_setup_theme', 'lpd_setup' );

function lpd_scripts() {
	wp_enqueue_style( 'lilypawdigital-style', get_stylesheet_uri(), array(), LPD_VERSION );
	wp_enqueue_script( 'lilypawdigital-nav', get_template_directory_uri() . '/js/navigation.js', array(), LPD_VERSION, true );
	wp_enqueue_script( 'lilypawdigital-interactions', get_template_directory_uri() . '/js/interactions.js', array(), LPD_VERSION, true );
	wp_localize_script( 'lilypawdigital-interactions', 'lpdTheme', array(
		'url' => get_template_directory_uri(),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'lpd_scripts' );

/**
 * Preload the one font file the whole site is set in (Sora, variable)
 * so text doesn't swap late.
 */
function lpd_preload_fonts() {
	$fonts = array( 'sora-latin-wght-normal.woff2' );
	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_template_directory_uri() . '/assets/fonts/' . $font )
		);
	}
}
add_action( 'wp_head', 'lpd_preload_fonts', 1 );

/**
 * Mark the document as scripted before first paint, so the headline can
 * wait for its entrance animation without flashing in unstyled first.
 */
function lpd_js_class() {
	echo "<script>document.documentElement.classList.add('lpd-js');</script>\n";
}
add_action( 'wp_head', 'lpd_js_class', 0 );

function lpd_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'lilypawdigital' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	$footer_cols = array( 'footer-1', 'footer-2', 'footer-3' );
	foreach ( $footer_cols as $col ) {
		register_sidebar(
			array(
				'name'          => sprintf( __( 'Footer %s', 'lilypawdigital' ), $col ),
				'id'            => $col,
				'before_widget' => '<div id="%1$s" class="%2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>',
			)
		);
	}
}
add_action( 'widgets_init', 'lpd_widgets_init' );

function lpd_fallback_menu() {
	echo '<ul>';
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
		)
	);
	echo '</ul>';
}

function lpd_excerpt_length( $length ) {
	return 22;
}
add_filter( 'excerpt_length', 'lpd_excerpt_length' );

function lpd_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'lpd_excerpt_more' );


/**
 * WordPress wraps the contents of every Group block in an extra
 * <div class="wp-block-group__inner-container"> on themes without a
 * theme.json. This theme's layouts are written against the markup as it
 * is saved in the editor (grids, flex columns and the pinned colocation
 * scene all rely on direct children), so that wrapper is switched off.
 */
remove_filter( 'render_block_core/group', 'wp_restore_group_inner_container', 10 );
remove_filter( 'render_block', 'wp_restore_group_inner_container', 10 );

/**
 * Belt and braces: if a plugin or a cached page still carries the
 * wrapper, take it back out before the page is sent.
 */
function lpd_strip_group_inner_container( $block_content, $block ) {
	if ( empty( $block['blockName'] ) || 'core/group' !== $block['blockName'] || false === strpos( $block_content, 'wp-block-group__inner-container' ) ) {
		return $block_content;
	}
	$unwrapped = preg_replace( '/^(\s*<(?:div|section|main|article|aside|header|footer)\b[^>]*>)\s*<div class="wp-block-group__inner-container[^"]*">(.*)<\/div>(\s*<\/(?:div|section|main|article|aside|header|footer)>\s*)$/s', '$1$2$3', $block_content );
	return null === $unwrapped ? $block_content : $unwrapped;
}
add_filter( 'render_block', 'lpd_strip_group_inner_container', 20, 2 );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/customizer-defaults.php';
require get_template_directory() . '/inc/page-setup.php';
