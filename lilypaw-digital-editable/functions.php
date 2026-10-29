<?php
/**
 * Lily Paw Digital theme functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'LPD_VERSION', '2.82' );

function lpd_setup() {
	load_theme_textdomain( 'lilypaw-digital', get_template_directory() . '/languages' );

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
			'primary' => __( 'Primary Menu', 'lilypaw-digital' ),
			'footer'  => __( 'Footer Menu', 'lilypaw-digital' ),
		)
	);
}
add_action( 'after_setup_theme', 'lpd_setup' );

function lpd_scripts() {
	wp_enqueue_style( 'lilypaw-digital-style', get_stylesheet_uri(), array(), LPD_VERSION );
	wp_enqueue_script( 'lilypaw-digital-nav', get_template_directory_uri() . '/js/navigation.js', array(), LPD_VERSION, true );
	wp_enqueue_script( 'lilypaw-digital-interactions', get_template_directory_uri() . '/js/interactions.js', array(), LPD_VERSION, true );
	wp_localize_script( 'lilypaw-digital-interactions', 'lpdTheme', array(
		'url' => get_template_directory_uri(),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'lpd_scripts' );

function lpd_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'lilypaw-digital' ),
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
				'name'          => sprintf( __( 'Footer %s', 'lilypaw-digital' ), $col ),
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

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/customizer-defaults.php';
