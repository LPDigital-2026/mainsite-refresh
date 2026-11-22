<?php
/**
 * The blog (the page chosen as the "Posts page"). Its title and content
 * become the heading and introduction.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$lpd_blog_id = (int) get_option( 'page_for_posts' );
$lpd_title   = $lpd_blog_id ? get_the_title( $lpd_blog_id ) : __( 'Blog', 'lilypawdigital' );
$lpd_intro   = $lpd_blog_id ? apply_filters( 'the_content', get_post_field( 'post_content', $lpd_blog_id ) ) : '';

lpd_blog_list( $lpd_title, is_paged() ? '' : $lpd_intro );

get_footer();
