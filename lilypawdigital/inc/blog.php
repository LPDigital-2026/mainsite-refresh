<?php
/**
 * Blog helpers: post cards, reading time, the latest-posts shortcode and
 * the structured data search engines read.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Minutes to read a post, at about 220 words a minute.
 */
function lpd_reading_time( $post = null ) {
	$text  = wp_strip_all_tags( strip_shortcodes( (string) get_post_field( 'post_content', $post ) ) );
	$words = str_word_count( $text );
	return max( 1, (int) round( $words / 220 ) );
}

/**
 * A post's picture: its featured image if it has one, otherwise the cover
 * the theme ships for that article (assets/images/blog/<slug>.jpg), or a
 * general cover. So every article has an image, and one set in WordPress
 * always takes over.
 */
function lpd_post_cover_url( $post = null ) {
	$post = get_post( $post );
	$dir  = get_template_directory() . '/assets/images/blog/';
	$file = ( $post && is_readable( $dir . $post->post_name . '.jpg' ) ) ? $post->post_name . '.jpg' : 'default.jpg';
	return get_template_directory_uri() . '/assets/images/blog/' . $file;
}

function lpd_post_image_url( $post = null, $size = 'large' ) {
	$post = get_post( $post );
	if ( $post && has_post_thumbnail( $post ) ) {
		$url = wp_get_attachment_image_url( get_post_thumbnail_id( $post ), $size );
		if ( $url ) {
			return $url;
		}
	}
	return lpd_post_cover_url( $post );
}

function lpd_post_image( $size = 'large', $attr = array() ) {
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( $size, $attr );
		return;
	}
	printf(
		'<img src="%s" alt="" width="1600" height="1000" loading="%s" decoding="async">',
		esc_url( lpd_post_cover_url() ),
		esc_attr( isset( $attr['loading'] ) ? $attr['loading'] : 'lazy' )
	);
}

/**
 * The first category, for the small label above a title.
 */
function lpd_primary_category( $post = null ) {
	$cats = get_the_category( $post ? get_post( $post )->ID : 0 );
	foreach ( (array) $cats as $cat ) {
		if ( 'uncategorized' !== $cat->slug ) {
			return $cat;
		}
	}
	return null;
}

/**
 * One post as a card. Used on the blog, archives, related posts and the
 * home page. $size is 'large' for the featured card, 'card' otherwise.
 */
function lpd_post_card( $size = 'card', $heading = 'h3' ) {
	$cat     = lpd_primary_category();
	$heading = in_array( $heading, array( 'h2', 'h3' ), true ) ? $heading : 'h3';
	?>
	<article <?php post_class( 'lpd-post-card' . ( 'large' === $size ? ' lpd-post-card-featured' : '' ) ); ?>>
		<a class="lpd-post-card-link" href="<?php the_permalink(); ?>">
			<figure class="lpd-post-card-image">
				<?php lpd_post_image( 'large' === $size ? 'large' : 'medium_large', array( 'loading' => 'large' === $size ? 'eager' : 'lazy' ) ); ?>
			</figure>
			<div class="lpd-post-card-body">
				<p class="lpd-post-card-meta">
					<?php if ( $cat ) : ?>
						<span class="lpd-post-card-cat"><?php echo esc_html( $cat->name ); ?></span>
					<?php endif; ?>
					<span><?php echo esc_html( sprintf( _n( '%d min read', '%d min read', lpd_reading_time(), 'lilypawdigital' ), lpd_reading_time() ) ); ?></span>
				</p>
				<<?php echo $heading; ?> class="lpd-post-card-title"><?php the_title(); ?></<?php echo $heading; ?>>
				<p class="lpd-post-card-excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
				<p class="lpd-post-card-date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
			</div>
		</a>
	</article>
	<?php
}

/**
 * [lpd_latest_posts count="3"] - the newest posts as cards. Prints nothing
 * when there are no posts, and the section around it hides itself.
 */
function lpd_latest_posts_shortcode( $atts ) {
	$atts  = shortcode_atts( array( 'count' => 3 ), $atts, 'lpd_latest_posts' );
	$query = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => max( 1, min( 12, (int) $atts['count'] ) ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( ! $query->have_posts() ) {
		return '';
	}
	ob_start();
	echo '<div class="lpd-post-grid">';
	while ( $query->have_posts() ) {
		$query->the_post();
		lpd_post_card();
	}
	echo '</div>';
	$blog = (int) get_option( 'page_for_posts' );
	if ( $blog ) {
		printf(
			'<div class="wp-block-buttons lpd-latest-more"><div class="wp-block-button lpd-btn-secondary"><a class="wp-block-button__link wp-element-button" href="%s">%s</a></div></div>',
			esc_url( get_permalink( $blog ) ),
			esc_html__( 'All Articles', 'lilypawdigital' )
		);
	}
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'lpd_latest_posts', 'lpd_latest_posts_shortcode' );

/**
 * Up to three other posts from the same category (or the newest posts if
 * there are none), for the end of an article.
 */
function lpd_related_posts_query( $post_id ) {
	$cat  = lpd_primary_category( $post_id );
	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);
	if ( $cat ) {
		$args['cat'] = $cat->term_id;
	}
	$query = new WP_Query( $args );
	if ( $cat && $query->post_count < 3 ) {
		unset( $args['cat'] );
		$query = new WP_Query( $args );
	}
	return $query;
}

/**
 * Structured data (JSON-LD). Articles get BlogPosting and breadcrumbs, so
 * search engines can show the date, image and site; the home page
 * describes the business. Skipped when an SEO plugin is active, since
 * those print their own.
 */
function lpd_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' ) || defined( 'SLIM_SEO_VER' );
}

function lpd_structured_data() {
	if ( lpd_seo_plugin_active() ) {
		return;
	}
	$logo = get_template_directory_uri() . '/assets/images/logo-stacked.png';
	$org  = array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
		'logo'  => array( '@type' => 'ImageObject', 'url' => $logo ),
	);
	$data = array();

	if ( is_front_page() ) {
		$data[] = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'ProfessionalService',
			'name'       => get_bloginfo( 'name' ),
			'url'        => home_url( '/' ),
			'logo'       => $logo,
			'image'      => get_template_directory_uri() . '/assets/images/share.jpg',
			'telephone'  => '+443331127191',
			'priceRange' => '££',
			'areaServed' => 'North East England',
			'address'    => array( '@type' => 'PostalAddress', 'addressLocality' => 'Newcastle upon Tyne', 'addressCountry' => 'GB' ),
		);
	}

	if ( is_singular( 'post' ) ) {
		$post  = get_queried_object();
		$image = lpd_post_image_url( $post );
		$data[] = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'BlogPosting',
			'headline'         => wp_strip_all_tags( get_the_title( $post ) ),
			'description'      => wp_strip_all_tags( get_the_excerpt( $post ) ),
			'image'            => $image,
			'datePublished'    => get_the_date( DATE_W3C, $post ),
			'dateModified'     => get_the_modified_date( DATE_W3C, $post ),
			'mainEntityOfPage' => get_permalink( $post ),
			'wordCount'        => str_word_count( wp_strip_all_tags( $post->post_content ) ),
			'author'           => $org,
			'publisher'        => $org,
		);
		$crumbs = array( array( get_bloginfo( 'name' ), home_url( '/' ) ) );
		$blog   = (int) get_option( 'page_for_posts' );
		if ( $blog ) {
			$crumbs[] = array( get_the_title( $blog ), get_permalink( $blog ) );
		}
		$crumbs[] = array( wp_strip_all_tags( get_the_title( $post ) ), get_permalink( $post ) );
		$items    = array();
		foreach ( $crumbs as $i => $crumb ) {
			$items[] = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => $crumb[0], 'item' => $crumb[1] );
		}
		$data[] = array( '@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items );
	}

	foreach ( $data as $item ) {
		echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'lpd_structured_data', 6 );

/**
 * Articles say they are articles to link previews, with their dates.
 */
function lpd_article_meta() {
	if ( lpd_seo_plugin_active() || ! is_singular( 'post' ) ) {
		return;
	}
	printf( '<meta property="article:published_time" content="%s">' . "\n", esc_attr( get_the_date( DATE_W3C ) ) );
	printf( '<meta property="article:modified_time" content="%s">' . "\n", esc_attr( get_the_modified_date( DATE_W3C ) ) );
}
add_action( 'wp_head', 'lpd_article_meta', 7 );

/**
 * Blog lists show 9 posts a page (a featured post and a grid of 8 looks
 * even), and archives the same.
 */
function lpd_blog_posts_per_page( $query ) {
	if ( ! is_admin() && $query->is_main_query() && ( $query->is_home() || $query->is_archive() ) ) {
		$query->set( 'posts_per_page', 9 );
	}
}
add_action( 'pre_get_posts', 'lpd_blog_posts_per_page' );

/**
 * The blog and archive page body: a page header, then the newest post as a
 * large card and the rest in a grid, then page numbers.
 */
function lpd_blog_list( $title, $intro = '' ) {
	?>
	<main id="primary" class="site-main lpd-blog">
		<div class="page-hero">
			<div class="container">
				<h1 class="entry-title"><?php echo esc_html( $title ); ?></h1>
			</div>
		</div>
		<div class="lpd-blog-body">
			<div class="container">
				<?php if ( $intro ) : ?>
					<div class="lpd-blog-intro"><?php echo wp_kses_post( $intro ); ?></div>
				<?php endif; ?>

				<?php if ( have_posts() ) : ?>
					<?php
					$first = ! is_paged();
					if ( $first ) {
						the_post();
						lpd_post_card( 'large', 'h2' );
					}
					if ( have_posts() ) {
						echo '<div class="lpd-post-grid">';
						while ( have_posts() ) {
							the_post();
							lpd_post_card( 'card', 'h2' );
						}
						echo '</div>';
					}
					the_posts_pagination(
						array(
							'mid_size'  => 1,
							'prev_text' => esc_html__( 'Newer', 'lilypawdigital' ),
							'next_text' => esc_html__( 'Older', 'lilypawdigital' ),
						)
					);
					?>
				<?php else : ?>
					<div class="lpd-blog-empty">
						<h2><?php esc_html_e( 'Articles are on their way', 'lilypawdigital' ); ?></h2>
						<p><?php esc_html_e( 'We are writing our first articles now. In the meantime, get in touch with any question and we will answer it.', 'lilypawdigital' ); ?></p>
						<p><a class="btn btn-primary" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Talk to Us', 'lilypawdigital' ); ?></a></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</main>
	<?php
}

/**
 * An article's content with an id on every section heading (h2), so the
 * contents list can link to them, plus that list.
 * Returns array( 'html' => ..., 'toc' => array( array( id, text ), ... ) ).
 */
function lpd_article_content_and_toc() {
	$html = apply_filters( 'the_content', get_the_content() );
	$html = str_replace( ']]>', ']]&gt;', $html );
	$toc  = array();
	$used = array();
	$html = preg_replace_callback(
		'#<h2([^>]*)>(.*?)</h2>#is',
		function ( $m ) use ( &$toc, &$used ) {
			$attrs = $m[1];
			$text  = trim( wp_strip_all_tags( $m[2] ) );
			if ( '' === $text ) {
				return $m[0];
			}
			if ( preg_match( '#\sid=["\']([^"\']+)["\']#', $attrs, $id ) ) {
				$id = $id[1];
			} else {
				$base = sanitize_title( $text );
				$id   = $base ? $base : 'section';
				$n    = 2;
				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $n++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}
			$used[ $id ] = true;
			$toc[]       = array( $id, $text );
			return '<h2' . $attrs . '>' . $m[2] . '</h2>';
		},
		$html
	);
	return array( 'html' => $html, 'toc' => $toc );
}

/**
 * Share links for an article. Plain links (no tracking scripts), plus a
 * copy-link button that interactions.js wires up.
 */
function lpd_share_links() {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( wp_strip_all_tags( get_the_title() ) );
	$links = array(
		'LinkedIn' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
		'Facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
		'X'        => 'https://x.com/intent/post?url=' . $url . '&text=' . $title,
	);
	echo '<ul class="lpd-share">';
	foreach ( $links as $name => $href ) {
		printf(
			'<li><a href="%1$s" target="_blank" rel="noopener" aria-label="%2$s">%3$s</a></li>',
			esc_url( $href ),
			/* translators: %s: a social network */
			esc_attr( sprintf( __( 'Share on %s', 'lilypawdigital' ), $name ) ),
			esc_html( $name )
		);
	}
	printf(
		'<li><button type="button" class="lpd-share-copy" data-url="%1$s" data-done="%2$s">%3$s</button></li>',
		esc_url( get_permalink() ),
		esc_attr__( 'Link copied', 'lilypawdigital' ),
		esc_html__( 'Copy link', 'lilypawdigital' )
	);
	echo '</ul>';
}
