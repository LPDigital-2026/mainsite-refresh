<?php
/**
 * Category, tag, author and date archives.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

lpd_blog_list( wp_strip_all_tags( get_the_archive_title() ), is_paged() ? '' : get_the_archive_description() );

get_footer();
