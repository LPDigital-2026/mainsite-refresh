<?php
/**
 * Search results template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main id="primary" class="site-main">
	<div class="page-hero">
		<div class="container">
			<h1 class="entry-title">
				<?php
				/* translators: %s: search query. */
				printf( esc_html__( 'Search Results for: %s', 'lilypaw-digital' ), '<span>' . get_search_query() . '</span>' );
				?>
			</h1>
		</div>
	</div>

	<div class="container" style="padding-bottom:56px;">
		<?php if ( have_posts() ) : ?>
			<div class="grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
						<h2 class="entry-title" style="font-size:1.15rem;">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="entry-summary"><?php the_excerpt(); ?></div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="pagination"><?php the_posts_pagination(); ?></div>

		<?php else : ?>
			<div class="card">
				<p><?php esc_html_e( 'Nothing found for that search.', 'lilypaw-digital' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
