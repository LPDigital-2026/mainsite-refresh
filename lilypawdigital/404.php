<?php
/**
 * 404 template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$lpd_404_links = array(
	array( home_url( '/' ), __( 'Home', 'lilypawdigital' ), __( 'Web design, hosting and colocation, with prices.', 'lilypawdigital' ) ),
	array( home_url( '/our-work/' ), __( 'Our Work', 'lilypawdigital' ), __( 'Sites we have built, and what their owners said.', 'lilypawdigital' ) ),
	array( home_url( '/colocation/' ), __( 'Colocation', 'lilypawdigital' ), __( 'Rack space in our Newcastle data centre.', 'lilypawdigital' ) ),
	array( home_url( '/contact-us/' ), __( 'Contact Us', 'lilypawdigital' ), __( 'Ask us anything. A real person replies.', 'lilypawdigital' ) ),
);
?>

<main id="primary" class="site-main lpd-page-full lpd-404">
	<header class="page-hero">
		<div class="container">
			<h1 class="entry-title"><?php esc_html_e( 'Page Not Found', 'lilypawdigital' ); ?></h1>
		</div>
	</header>

	<div class="lpd-bands">
		<div class="lpd-band">
			<div class="lpd-inner">
				<p class="lpd-lead"><?php esc_html_e( 'The page you were looking for does not seem to exist. It may have moved, or the link may be out of date. One of these should get you back on track.', 'lilypawdigital' ); ?></p>

				<ul class="lpd-404-links">
					<?php foreach ( $lpd_404_links as $lpd_404_link ) : ?>
						<li>
							<a href="<?php echo esc_url( $lpd_404_link[0] ); ?>">
								<span class="lpd-404-title"><?php echo esc_html( $lpd_404_link[1] ); ?></span>
								<span class="lpd-404-text"><?php echo esc_html( $lpd_404_link[2] ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
