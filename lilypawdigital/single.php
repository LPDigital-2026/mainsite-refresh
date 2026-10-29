<?php
/**
 * Single post template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main id="primary" class="site-main">

	<div class="page-hero">
		<div class="container">
			<?php while ( have_posts() ) : the_post(); ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<div class="entry-meta" style="color:var(--lp-text-dim);">
					<?php esc_html_e( 'Written by', 'lilypawdigital' ); ?> <?php the_author(); ?> | <?php lpd_posted_on(); ?>
				</div>
			<?php endwhile; ?>
		</div>
	</div>

	<div class="container">
		<div class="content-layout" style="display:grid;grid-template-columns:2fr 1fr;gap:40px;padding-bottom:56px;">
			<div>
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<div style="margin-bottom:24px;border-radius:14px;overflow:hidden;">
								<?php the_post_thumbnail( 'large' ); ?>
							</div>
						<?php endif; ?>

						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lilypawdigital' ),
								'after'  => '</div>',
							)
						);
						?>
					</article>

					<footer class="entry-footer" style="margin:24px 0;color:var(--lp-text-dim);">
						<?php lpd_entry_footer(); ?>
					</footer>

					<?php if ( comments_open() || get_comments_number() ) : ?>
						<?php comments_template(); ?>
					<?php endif; ?>

				<?php endwhile; ?>
			</div>

			<aside>
				<?php get_sidebar(); ?>
			</aside>
		</div>
	</div>
</main>

<?php
get_footer();
