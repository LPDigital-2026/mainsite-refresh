<?php
/**
 * Main template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<div class="content-layout" style="display:grid;grid-template-columns:2fr 1fr;gap:40px;padding:56px 0;">
			<div class="posts-column">

				<?php if ( have_posts() ) : ?>

					<?php while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?> style="margin-bottom:20px;">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>" style="display:block;margin:-26px -26px 18px;">
									<?php the_post_thumbnail( 'large', array( 'style' => 'border-radius:14px 14px 0 0;' ) ); ?>
								</a>
							<?php endif; ?>

							<h2 class="entry-title" style="font-size:1.35rem;">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<div class="entry-meta" style="color:var(--lp-text-dim);font-size:0.83rem;margin-bottom:12px;">
								<?php lpd_posted_on(); ?>
							</div>

							<div class="entry-summary">
								<?php the_excerpt(); ?>
							</div>

							<p style="margin-top:14px;">
								<a href="<?php the_permalink(); ?>" class="btn btn-secondary">
									<?php esc_html_e( 'Read more', 'lilypaw-digital' ); ?>
								</a>
							</p>
						</article>
					<?php endwhile; ?>

					<div class="pagination">
						<?php the_posts_pagination(); ?>
					</div>

				<?php else : ?>

					<div class="card">
						<h2><?php esc_html_e( 'Nothing found', 'lilypaw-digital' ); ?></h2>
						<p><?php esc_html_e( 'It looks like there is no content here yet.', 'lilypaw-digital' ); ?></p>
					</div>

				<?php endif; ?>

			</div>

			<aside class="sidebar-column">
				<?php get_sidebar(); ?>
			</aside>
		</div>
	</div>
</main>

<?php
get_footer();
