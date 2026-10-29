<?php
/**
 * Page template.
 *
 * Pages built from full-width bands (the content the theme ships in
 * inc/pages/, recognisable by the lpd-band class) render edge to edge
 * like the homepage, under a left-aligned page hero. Any other page
 * keeps the single clean reading column; Colocation's older content
 * gets a wider one.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$is_full       = false !== strpos( (string) get_post_field( 'post_content', get_queried_object_id() ), 'lpd-band' );
$is_colocation = is_page( 'colocation' );

if ( $is_full ) :
	?>
	<main id="primary" class="site-main lpd-page-full">
		<div class="page-hero">
			<div class="container">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</div>
		</div>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content lpd-bands' ); ?>>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	</main>
	<?php
	get_footer();
	return;
endif;
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
