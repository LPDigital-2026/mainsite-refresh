<?php
/**
 * Titles, descriptions, link previews and the per-page SEO box.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The key a page's suggested title and description are stored under: its
 * slug, or "home" for the front page.
 */
function lpd_seo_page_key( $post ) {
	if ( ! $post ) {
		return '';
	}
	if ( (int) get_option( 'page_on_front' ) === (int) $post->ID ) {
		return 'home';
	}
	return $post->post_name;
}

/** Shortens text to fit a search result, ending on a whole word. */
function lpd_seo_trim( $text, $max = 158 ) {
	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( strip_shortcodes( (string) $text ) ) ) );
	if ( function_exists( 'mb_strlen' ) ? mb_strlen( $text ) <= $max : strlen( $text ) <= $max ) {
		return $text;
	}
	$cut  = function_exists( 'mb_substr' ) ? mb_substr( $text, 0, $max - 1 ) : substr( $text, 0, $max - 1 );
	$cut  = preg_replace( '/\s+\S*$/', '', $cut );
	return rtrim( $cut, " ,.;:-" ) . '…';
}

/** The object the current request is about, if it is a single post or page. */
function lpd_seo_current_post() {
	if ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) {
		return get_post( (int) get_option( 'page_on_front' ) );
	}
	if ( is_home() && get_option( 'page_for_posts' ) ) {
		return get_post( (int) get_option( 'page_for_posts' ) );
	}
	if ( is_singular() ) {
		return get_queried_object();
	}
	return null;
}

function lpd_seo_title_for( $post ) {
	if ( ! $post ) {
		return '';
	}
	$custom = trim( (string) get_post_meta( $post->ID, '_lpd_seo_title', true ) );
	if ( $custom ) {
		return $custom;
	}
	$defaults = lpd_seo_defaults();
	$key      = lpd_seo_page_key( $post );
	if ( isset( $defaults[ $key ] ) ) {
		return $defaults[ $key ][0];
	}
	if ( 'post' === $post->post_type ) {
		$headline = wp_strip_all_tags( get_the_title( $post ) );
		$full     = $headline . ' | ' . get_bloginfo( 'name' );
		// Long headlines go out without the site name, so more of them shows.
		return ( function_exists( 'mb_strlen' ) ? mb_strlen( $full ) : strlen( $full ) ) <= 60 ? $full : $headline;
	}
	return '';
}

function lpd_seo_description_for( $post ) {
	if ( $post ) {
		$custom = trim( (string) get_post_meta( $post->ID, '_lpd_seo_description', true ) );
		if ( $custom ) {
			return lpd_seo_trim( $custom, 300 );
		}
		// An article's own excerpt comes first, so editing it changes what Google shows.
		if ( 'post' === $post->post_type && has_excerpt( $post ) ) {
			return lpd_seo_trim( $post->post_excerpt );
		}
		$defaults = lpd_seo_defaults();
		$key      = lpd_seo_page_key( $post );
		if ( isset( $defaults[ $key ] ) ) {
			return $defaults[ $key ][1];
		}
		if ( has_excerpt( $post ) ) {
			return lpd_seo_trim( $post->post_excerpt );
		}
		$text = lpd_seo_trim( $post->post_content );
		if ( strlen( $text ) > 50 ) {
			return $text;
		}
	}
	if ( is_category() || is_tag() ) {
		$desc = term_description();
		if ( $desc ) {
			return lpd_seo_trim( $desc );
		}
		/* translators: %s: category name */
		return sprintf( __( 'Articles about %s from Lily Paw Digital: plain-English guides for small businesses in the North East.', 'lilypaw-seo' ), single_term_title( '', false ) );
	}
	return lpd_seo_defaults()['home'][1];
}

/** The picture shown when a page is shared. */
function lpd_seo_image_for( $post ) {
	if ( $post && 'post' === $post->post_type && function_exists( 'lpd_post_image_url' ) ) {
		return lpd_post_image_url( $post );
	}
	if ( $post && has_post_thumbnail( $post ) ) {
		$url = wp_get_attachment_image_url( get_post_thumbnail_id( $post ), 'large' );
		if ( $url ) {
			return $url;
		}
	}
	if ( file_exists( get_template_directory() . '/assets/images/share.jpg' ) ) {
		return get_template_directory_uri() . '/assets/images/share.jpg';
	}
	return '';
}

/** Page titles in the browser tab and in search results. */
add_filter(
	'pre_get_document_title',
	function ( $title ) {
		if ( is_category() ) {
			/* translators: %s: category name */
			return sprintf( __( '%s Articles | Lily Paw Digital', 'lilypaw-seo' ), single_term_title( '', false ) );
		}
		$custom = lpd_seo_title_for( lpd_seo_current_post() );
		return $custom ? $custom : $title;
	},
	20
);

/** Description and link-preview tags. */
add_action(
	'wp_head',
	function () {
		if ( is_404() || is_search() ) {
			return;
		}
		$post  = lpd_seo_current_post();
		$title = lpd_seo_title_for( $post );
		$title = $title ? $title : wp_get_document_title();
		$desc  = lpd_seo_description_for( $post );
		$url   = $post ? get_permalink( $post ) : ( is_category() || is_tag() ? get_term_link( get_queried_object() ) : home_url( '/' ) );
		if ( is_front_page() ) {
			$url = home_url( '/' );
		}
		$image = lpd_seo_image_for( $post );
		$type  = ( $post && 'post' === $post->post_type ) ? 'article' : 'website';

		echo "\n<!-- Lily Paw SEO -->\n";
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
		printf( '<meta property="og:locale" content="en_GB">' . "\n" );
		printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $type ) );
		printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
		if ( ! is_wp_error( $url ) ) {
			printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
		}
		if ( $image ) {
			printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		}
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		if ( 'article' === $type ) {
			printf( '<meta property="article:published_time" content="%s">' . "\n", esc_attr( get_the_date( DATE_W3C, $post ) ) );
			printf( '<meta property="article:modified_time" content="%s">' . "\n", esc_attr( get_the_modified_date( DATE_W3C, $post ) ) );
		}
		// WordPress only adds a canonical link to single posts and pages; add
		// one to the blog and category pages too.
		if ( is_home() && ! is_front_page() ) {
			printf( '<link rel="canonical" href="%s">' . "\n", esc_url( get_pagenum_link( max( 1, (int) get_query_var( 'paged' ) ) ) ) );
		}
		if ( ( is_category() || is_tag() ) && ! is_wp_error( $url ) ) {
			printf( '<link rel="canonical" href="%s">' . "\n", esc_url( get_pagenum_link( max( 1, (int) get_query_var( 'paged' ) ) ) ) );
		}
		echo "<!-- / Lily Paw SEO -->\n";
	},
	4
);

/**
 * The SEO box on the page and post editor: a title, a description and a
 * "hide from search engines" switch.
 */
add_action(
	'add_meta_boxes',
	function () {
		foreach ( array( 'page', 'post' ) as $type ) {
			add_meta_box( 'lpd-seo', __( 'Search results (SEO)', 'lilypaw-seo' ), 'lpd_seo_meta_box', $type, 'normal', 'low' );
		}
	}
);

function lpd_seo_meta_box( $post ) {
	wp_nonce_field( 'lpd_seo_save', 'lpd_seo_nonce' );
	$title = (string) get_post_meta( $post->ID, '_lpd_seo_title', true );
	$desc  = (string) get_post_meta( $post->ID, '_lpd_seo_description', true );
	$hide  = (bool) get_post_meta( $post->ID, '_lpd_seo_noindex', true );
	$d     = lpd_seo_defaults();
	$key   = lpd_seo_page_key( $post );
	$ph_t  = isset( $d[ $key ] ) ? $d[ $key ][0] : ( 'post' === $post->post_type ? wp_strip_all_tags( $post->post_title ) . ' | ' . get_bloginfo( 'name' ) : '' );
	$ph_d  = isset( $d[ $key ] ) ? $d[ $key ][1] : ( $post->post_excerpt ? lpd_seo_trim( $post->post_excerpt ) : '' );
	?>
	<p><label for="lpd-seo-title"><strong><?php esc_html_e( 'Title in search results', 'lilypaw-seo' ); ?></strong></label><br>
	<input type="text" id="lpd-seo-title" name="lpd_seo_title" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php echo esc_attr( $ph_t ); ?>" style="width:100%" maxlength="120"></p>
	<p class="description"><?php esc_html_e( 'Leave empty to use the suggestion shown. Aim for under 60 characters.', 'lilypaw-seo' ); ?></p>
	<p><label for="lpd-seo-desc"><strong><?php esc_html_e( 'Description in search results', 'lilypaw-seo' ); ?></strong></label><br>
	<textarea id="lpd-seo-desc" name="lpd_seo_description" rows="3" style="width:100%" maxlength="300" placeholder="<?php echo esc_attr( $ph_d ); ?>"><?php echo esc_textarea( $desc ); ?></textarea></p>
	<p class="description"><?php esc_html_e( 'Leave empty to use the suggestion (or, for articles, the excerpt). Aim for 120 to 160 characters.', 'lilypaw-seo' ); ?></p>
	<p><label><input type="checkbox" name="lpd_seo_noindex" value="1" <?php checked( $hide ); ?>> <?php esc_html_e( 'Hide this page from search engines (and leave it out of the sitemap)', 'lilypaw-seo' ); ?></label></p>
	<?php
}

add_action(
	'save_post',
	function ( $post_id ) {
		if ( ! isset( $_POST['lpd_seo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['lpd_seo_nonce'] ) ), 'lpd_seo_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$title = isset( $_POST['lpd_seo_title'] ) ? sanitize_text_field( wp_unslash( $_POST['lpd_seo_title'] ) ) : '';
		$desc  = isset( $_POST['lpd_seo_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['lpd_seo_description'] ) ) : '';
		$title ? update_post_meta( $post_id, '_lpd_seo_title', $title ) : delete_post_meta( $post_id, '_lpd_seo_title' );
		$desc ? update_post_meta( $post_id, '_lpd_seo_description', $desc ) : delete_post_meta( $post_id, '_lpd_seo_description' );
		! empty( $_POST['lpd_seo_noindex'] ) ? update_post_meta( $post_id, '_lpd_seo_noindex', 1 ) : delete_post_meta( $post_id, '_lpd_seo_noindex' );
	}
);
