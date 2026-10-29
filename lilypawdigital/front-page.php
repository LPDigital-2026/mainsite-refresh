<?php
/**
 * Front page template.
 *
 * Renders whatever content lives in the Page assigned as your homepage
 * (Settings -> Reading -> Your homepage displays -> A static page).
 * Edit that page with the block editor as normal - this template just
 * displays it. If no static front page is set, it falls back to the
 * standard blog loop.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

if ( is_page() ) :

	while ( have_posts() ) :
		the_post();
		?>
		<main id="primary" class="site-main lpd-front-page">
			<?php the_content(); ?>
		</main>
		<?php
	endwhile;

else :
	?>
	<main id="primary" class="site-main">
		<div class="container">
			<div class="content-layout" style="display:grid;grid-template-columns:2fr 1fr;gap:40px;padding:56px 0;">
				<div class="posts-column">
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?> style="margin-bottom:20px;">
								<h2 class="entry-title" style="font-size:1.35rem;">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<div class="entry-meta" style="color:var(--lp-text-dim);font-size:0.83rem;margin-bottom:12px;">
									<?php lpd_posted_on(); ?>
								</div>
								<div class="entry-summary"><?php the_excerpt(); ?></div>
							</article>
						<?php endwhile; ?>
						<div class="pagination"><?php the_posts_pagination(); ?></div>
					<?php endif; ?>
				</div>
				<aside class="sidebar-column">
					<?php get_sidebar(); ?>
				</aside>
			</div>
		</div>
	</main>
	<?php
endif;

get_footer();
