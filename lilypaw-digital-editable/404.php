<?php
/**
 * 404 template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main id="primary" class="site-main">
	<div class="container" style="padding:100px 0;text-align:center;">
		<h1><?php esc_html_e( 'Page Not Found', 'lilypaw-digital' ); ?></h1>
		<p style="color:var(--lp-text-dim);max-width:480px;margin:0 auto 32px;">
			<?php esc_html_e( 'The page you were looking for does not seem to exist. Try heading back home.', 'lilypaw-digital' ); ?>
		</p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
			<?php esc_html_e( 'Back to Home', 'lilypaw-digital' ); ?>
		</a>
	</div>
</main>

<?php
get_footer();
