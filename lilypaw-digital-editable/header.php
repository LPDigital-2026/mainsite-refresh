<?php
/**
 * Header template
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'lilypaw-digital' ); ?></a>

<header id="masthead" class="site-header">
	<div class="container header-inner">
		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<p class="site-title lpd-brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img
							class="lpd-logo-img"
							src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-stacked.png' ); ?>"
							alt="<?php bloginfo( 'name' ); ?>"
							width="175"
							height="64"
						>
					</a>
				</p>
			<?php endif; ?>
		</div>

		<nav id="site-navigation" class="main-navigation">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'fallback_cb'    => 'lpd_fallback_menu',
					'container'      => false,
				)
			);
			?>
			<a href="https://billing.lilypawdigital.com/login" class="lpd-client-login btn btn-secondary" target="_blank" rel="noopener">
				<?php esc_html_e( 'Client Login', 'lilypaw-digital' ); ?>
			</a>
		</nav>

		<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false">
			<span class="menu-toggle-bars" aria-hidden="true">
				<span></span><span></span><span></span>
			</span>
			<span class="menu-toggle-paw" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'lilypaw-digital' ); ?></span>
		</button>
	</div>
</header>

<div id="content" class="site-content-wrap">
