<?php
/**
 * Page template - single clean column, no sidebar. Most pages on this
 * site (About Us, Get Started, Contact Us) don't need a blog-style
 * sidebar competing for space next to a form or a short story.
 *
 * The Colocation page gets a wider layout (it's a pricing/spec page,
 * not a narrow reading column).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$is_colocation = is_page( 'colocation' );
?>

<main id="primary" class="site-main">

	<div class="page-hero">
		<div class="container">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</div>
	</div>

	<div class="container">
		<div class="lpd-page-content<?php echo $is_colocation ? ' lpd-page-content-wide' : ''; ?>">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
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

				<?php if ( comments_open() || get_comments_number() ) : ?>
					<?php comments_template(); ?>
				<?php endif; ?>

			<?php endwhile; ?>
		</div>
	</div>
</main>

<?php
get_footer();
