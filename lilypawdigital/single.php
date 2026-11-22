<?php
/**
 * A blog article: header, picture, a comfortable reading column, then
 * related articles and a way to get in touch.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

while ( have_posts() ) :
	the_post();
	$lpd_cat  = lpd_primary_category();
	$lpd_blog = (int) get_option( 'page_for_posts' );
	?>
	<main id="primary" class="site-main lpd-article-page">

		<header class="page-hero lpd-article-hero">
			<div class="container">
				<p class="lpd-article-meta">
					<?php if ( $lpd_cat ) : ?>
						<a class="lpd-article-cat" href="<?php echo esc_url( get_category_link( $lpd_cat ) ); ?>"><?php echo esc_html( $lpd_cat->name ); ?></a>
						<span aria-hidden="true">&middot;</span>
					<?php endif; ?>
					<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<span aria-hidden="true">&middot;</span>
					<span><?php echo esc_html( sprintf( _n( '%d min read', '%d min read', lpd_reading_time(), 'lilypawdigital' ), lpd_reading_time() ) ); ?></span>
				</p>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</div>
		</header>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'lpd-article' ); ?>>
			<div class="container">
				<nav class="lpd-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'lilypawdigital' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'lilypawdigital' ); ?></a>
					<?php if ( $lpd_blog ) : ?>
						<span aria-hidden="true">/</span> <a href="<?php echo esc_url( get_permalink( $lpd_blog ) ); ?>"><?php echo esc_html( get_the_title( $lpd_blog ) ); ?></a>
					<?php endif; ?>
					<span aria-hidden="true">/</span> <span aria-current="page"><?php the_title(); ?></span>
				</nav>
				<figure class="lpd-article-image"><?php lpd_post_image( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?></figure>
				<div class="entry-content lpd-article-body">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lilypawdigital' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>
				<?php
				$lpd_tags = get_the_tags();
				if ( $lpd_tags ) :
					?>
					<ul class="lpd-article-tags" aria-label="<?php esc_attr_e( 'Tags', 'lilypawdigital' ); ?>">
						<?php foreach ( $lpd_tags as $lpd_tag ) : ?>
							<li><a href="<?php echo esc_url( get_tag_link( $lpd_tag ) ); ?>"><?php echo esc_html( $lpd_tag->name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( comments_open() || get_comments_number() ) : ?>
					<div class="lpd-article-comments"><?php comments_template(); ?></div>
				<?php endif; ?>
			</div>
		</article>

		<?php
		$lpd_related = lpd_related_posts_query( get_the_ID() );
		if ( $lpd_related->have_posts() ) :
			?>
			<section class="lpd-related" aria-labelledby="lpd-related-title">
				<div class="container">
					<h2 id="lpd-related-title"><?php esc_html_e( 'Keep Reading', 'lilypawdigital' ); ?></h2>
					<div class="lpd-post-grid">
						<?php
						while ( $lpd_related->have_posts() ) {
							$lpd_related->the_post();
							lpd_post_card();
						}
						wp_reset_postdata();
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<section class="lpd-article-cta">
			<div class="container">
				<div class="lpd-cta-banner">
					<h2><?php esc_html_e( 'Need a Hand With This?', 'lilypawdigital' ); ?></h2>
					<p><?php esc_html_e( 'Tell us what you are trying to do and we will get back to you with clear advice. Real people, no jargon.', 'lilypawdigital' ); ?></p>
					<div class="wp-block-buttons"><div class="wp-block-button lpd-btn-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Talk to Us', 'lilypawdigital' ); ?></a></div></div>
				</div>
			</div>
		</section>

	</main>
	<?php
endwhile;

get_footer();
