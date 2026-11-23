<?php
/**
 * XML sitemaps at /sitemap.xml: an index pointing to one sitemap of pages,
 * one of articles (with their pictures) and one of categories. Pages and
 * articles hidden from search engines are left out. WordPress's own
 * sitemap (/wp-sitemap.xml) is switched off and redirects here, so search
 * engines see one sitemap only. Opening a sitemap in a browser shows a
 * readable table.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function lpd_seo_sitemap_types() {
	return array( 'pages', 'posts', 'categories' );
}

function lpd_seo_sitemap_rewrites() {
	add_rewrite_rule( '^sitemap\.xml$', 'index.php?lpd_sitemap=index', 'top' );
	add_rewrite_rule( '^sitemap-(pages|posts|categories)\.xml$', 'index.php?lpd_sitemap=$matches[1]', 'top' );
	add_rewrite_rule( '^sitemap\.xsl$', 'index.php?lpd_sitemap=xsl', 'top' );
}
add_action( 'init', 'lpd_seo_sitemap_rewrites' );

add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'lpd_sitemap';
		return $vars;
	}
);

add_filter( 'wp_sitemaps_enabled', '__return_false' );

function lpd_seo_sitemap_url( $type = 'index' ) {
	$file = 'index' === $type ? 'sitemap.xml' : ( 'xsl' === $type ? 'sitemap.xsl' : 'sitemap-' . $type . '.xml' );
	if ( get_option( 'permalink_structure' ) ) {
		return home_url( '/' . $file );
	}
	return add_query_arg( 'lpd_sitemap', $type, home_url( '/' ) );
}

/** Is a post hidden from search engines? */
function lpd_seo_is_hidden( $post ) {
	return (bool) get_post_meta( $post->ID, '_lpd_seo_noindex', true ) || '' !== $post->post_password || 'publish' !== $post->post_status;
}

/**
 * The entries of one sitemap: arrays of loc, lastmod and (for articles)
 * image.
 */
function lpd_seo_sitemap_entries( $type ) {
	$entries = array();
	if ( 'pages' === $type ) {
		$front = (int) get_option( 'page_on_front' );
		$pages = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 500, 'orderby' => 'menu_order title', 'order' => 'ASC' ) );
		if ( ! $front ) {
			$entries[] = array( 'loc' => home_url( '/' ), 'lastmod' => get_lastpostmodified( 'gmt' ) );
		}
		foreach ( $pages as $page ) {
			if ( lpd_seo_is_hidden( $page ) ) {
				continue;
			}
			$entries[] = array(
				'loc'     => $page->ID === $front ? home_url( '/' ) : get_permalink( $page ),
				'lastmod' => $page->post_modified_gmt,
			);
		}
		// The home page first.
		usort( $entries, function ( $a, $b ) { return ( home_url( '/' ) === $b['loc'] ) - ( home_url( '/' ) === $a['loc'] ); } );
	} elseif ( 'posts' === $type ) {
		foreach ( get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 1000 ) ) as $post ) {
			if ( lpd_seo_is_hidden( $post ) ) {
				continue;
			}
			$entries[] = array(
				'loc'     => get_permalink( $post ),
				'lastmod' => $post->post_modified_gmt,
				'image'   => lpd_seo_image_for( $post ),
				'title'   => wp_strip_all_tags( get_the_title( $post ) ),
			);
		}
	} elseif ( 'categories' === $type ) {
		foreach ( get_terms( array( 'taxonomy' => 'category', 'hide_empty' => true ) ) as $term ) {
			if ( 'uncategorized' === $term->slug ) {
				continue;
			}
			$latest    = get_posts( array( 'post_type' => 'post', 'numberposts' => 1, 'cat' => $term->term_id ) );
			$entries[] = array(
				'loc'     => get_term_link( $term ),
				'lastmod' => $latest ? $latest[0]->post_modified_gmt : '',
			);
		}
	}
	return $entries;
}

function lpd_seo_w3c( $gmt ) {
	if ( ! $gmt || '0000-00-00 00:00:00' === $gmt ) {
		return '';
	}
	return gmdate( 'Y-m-d\TH:i:s+00:00', strtotime( $gmt . ' UTC' ) );
}

function lpd_seo_sitemap_xml( $type ) {
	$x   = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$x  .= '<?xml-stylesheet type="text/xsl" href="' . esc_url( lpd_seo_sitemap_url( 'xsl' ) ) . '"?>' . "\n";
	if ( 'index' === $type ) {
		$x .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
		foreach ( lpd_seo_sitemap_types() as $t ) {
			$entries = lpd_seo_sitemap_entries( $t );
			if ( ! $entries ) {
				continue;
			}
			$mods = array_filter( wp_list_pluck( $entries, 'lastmod' ) );
			rsort( $mods );
			$x .= "\t<sitemap><loc>" . esc_url( lpd_seo_sitemap_url( $t ) ) . '</loc>' . ( $mods ? '<lastmod>' . lpd_seo_w3c( $mods[0] ) . '</lastmod>' : '' ) . "</sitemap>\n";
		}
		return $x . '</sitemapindex>' . "\n";
	}
	$x .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
	foreach ( lpd_seo_sitemap_entries( $type ) as $e ) {
		if ( is_wp_error( $e['loc'] ) ) {
			continue;
		}
		$x .= "\t<url><loc>" . esc_url( $e['loc'] ) . '</loc>';
		if ( ! empty( $e['lastmod'] ) ) {
			$x .= '<lastmod>' . lpd_seo_w3c( $e['lastmod'] ) . '</lastmod>';
		}
		if ( ! empty( $e['image'] ) ) {
			$x .= '<image:image><image:loc>' . esc_url( $e['image'] ) . '</image:loc></image:image>';
		}
		$x .= "</url>\n";
	}
	return $x . '</urlset>' . "\n";
}

add_action(
	'template_redirect',
	function () {
		// WordPress's own sitemap now lives here.
		$path = (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH );
		if ( preg_match( '#/wp-sitemap[^/]*\.xml$#', $path ) ) {
			wp_safe_redirect( lpd_seo_sitemap_url(), 301 );
			exit;
		}
		$type = get_query_var( 'lpd_sitemap' );
		if ( ! $type ) {
			return;
		}
		if ( 'xsl' === $type ) {
			header( 'Content-Type: text/xsl; charset=UTF-8' );
			header( 'X-Robots-Tag: noindex' );
			readfile( LPD_SEO_DIR . '/includes/sitemap.xsl' );
			exit;
		}
		if ( 'index' !== $type && ! in_array( $type, lpd_seo_sitemap_types(), true ) ) {
			return;
		}
		status_header( 200 );
		header( 'Content-Type: application/xml; charset=UTF-8' );
		header( 'X-Robots-Tag: noindex, follow' );
		echo lpd_seo_sitemap_xml( $type ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts
		exit;
	},
	0
);

/** Point robots.txt at the sitemap. */
add_filter(
	'robots_txt',
	function ( $output, $public ) {
		if ( ! $public ) {
			return $output;
		}
		$output = preg_replace( '/^Sitemap:.*$\n?/mi', '', (string) $output );
		return rtrim( $output ) . "\n\nSitemap: " . lpd_seo_sitemap_url() . "\n";
	},
	20,
	2
);

/** Don't add a trailing slash to the sitemap addresses. */
add_filter(
	'redirect_canonical',
	function ( $redirect ) {
		return get_query_var( 'lpd_sitemap' ) ? false : $redirect;
	}
);
