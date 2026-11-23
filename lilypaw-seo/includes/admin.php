<?php
/**
 * Settings → SEO: the sitemap address to give Google and Bing, and every
 * page's title and description at a glance, with any that are too long,
 * too short or repeated flagged.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action(
	'admin_menu',
	function () {
		add_options_page( __( 'SEO', 'lilypaw-seo' ), __( 'SEO', 'lilypaw-seo' ), 'manage_options', 'lpd-seo', 'lpd_seo_admin_page' );
	}
);

add_filter(
	'plugin_action_links_' . plugin_basename( LPD_SEO_DIR . '/lilypaw-seo.php' ),
	function ( $links ) {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=lpd-seo' ) ) . '">' . esc_html__( 'Settings', 'lilypaw-seo' ) . '</a>' );
		return $links;
	}
);

/** Rows for the overview: every published page and article. */
function lpd_seo_overview_rows() {
	$rows  = array();
	$items = get_posts( array( 'post_type' => array( 'page', 'post' ), 'post_status' => 'publish', 'numberposts' => 500, 'orderby' => 'type title', 'order' => 'ASC' ) );
	$seen  = array();
	foreach ( $items as $item ) {
		$title = lpd_seo_title_for( $item );
		$title = $title ? $title : wp_strip_all_tags( get_the_title( $item ) ) . ' – ' . get_bloginfo( 'name' );
		$desc  = lpd_seo_description_for( $item );
		$notes = array();
		$tl    = function_exists( 'mb_strlen' ) ? mb_strlen( $title ) : strlen( $title );
		$dl    = function_exists( 'mb_strlen' ) ? mb_strlen( $desc ) : strlen( $desc );
		if ( $tl > 60 ) {
			$notes[] = __( 'Title may be cut short in Google', 'lilypaw-seo' );
		}
		if ( $dl < 70 ) {
			$notes[] = __( 'Description is short', 'lilypaw-seo' );
		} elseif ( $dl > 160 ) {
			$notes[] = __( 'Description may be cut short', 'lilypaw-seo' );
		}
		if ( isset( $seen[ $desc ] ) ) {
			/* translators: %s: page title */
			$notes[] = sprintf( __( 'Same description as "%s"', 'lilypaw-seo' ), $seen[ $desc ] );
		}
		$seen[ $desc ] = get_the_title( $item );
		if ( get_post_meta( $item->ID, '_lpd_seo_noindex', true ) ) {
			$notes[] = __( 'Hidden from search engines', 'lilypaw-seo' );
		}
		$rows[] = array( 'post' => $item, 'title' => $title, 'desc' => $desc, 'tl' => $tl, 'dl' => $dl, 'notes' => $notes );
	}
	return $rows;
}

function lpd_seo_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$sitemap = lpd_seo_sitemap_url();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'SEO', 'lilypaw-seo' ); ?></h1>

		<?php if ( ! get_option( 'blog_public' ) ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'Search engines are being asked not to index this site. Untick "Discourage search engines from indexing this site" under Settings → Reading.', 'lilypaw-seo' ); ?></p></div>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Your sitemap', 'lilypaw-seo' ); ?></h2>
		<p><a href="<?php echo esc_url( $sitemap ); ?>" target="_blank" rel="noopener"><code><?php echo esc_html( $sitemap ); ?></code></a></p>
		<p class="description" style="max-width:720px"><?php esc_html_e( 'It updates itself whenever you publish or edit a page or article, and robots.txt already points to it. To tell Google about it once:', 'lilypaw-seo' ); ?></p>
		<ol style="max-width:720px">
			<li><?php esc_html_e( 'Open Google Search Console (search.google.com/search-console) and add lilypaw.digital as a Domain property, following its steps to verify you own it.', 'lilypaw-seo' ); ?></li>
			<li><?php esc_html_e( 'Choose Sitemaps in the menu, type sitemap.xml and press Submit.', 'lilypaw-seo' ); ?></li>
			<li><?php esc_html_e( 'Do the same in Bing Webmaster Tools (bing.com/webmasters), which can import everything from Search Console in one click.', 'lilypaw-seo' ); ?></li>
		</ol>

		<h2 style="margin-top:28px"><?php esc_html_e( 'Titles and descriptions', 'lilypaw-seo' ); ?></h2>
		<p class="description" style="max-width:720px"><?php esc_html_e( 'What Google shows for each page. To change one, edit the page and use the "Search results (SEO)" box under the content.', 'lilypaw-seo' ); ?></p>
		<table class="widefat striped" style="margin-top:12px">
			<thead><tr><th><?php esc_html_e( 'Page', 'lilypaw-seo' ); ?></th><th><?php esc_html_e( 'Title and description', 'lilypaw-seo' ); ?></th><th><?php esc_html_e( 'Check', 'lilypaw-seo' ); ?></th></tr></thead>
			<tbody>
			<?php foreach ( lpd_seo_overview_rows() as $row ) : ?>
				<tr>
					<td><a href="<?php echo esc_url( get_edit_post_link( $row['post'] ) ); ?>"><?php echo esc_html( get_the_title( $row['post'] ) ); ?></a><br><small><?php echo esc_html( 'post' === $row['post']->post_type ? __( 'Article', 'lilypaw-seo' ) : __( 'Page', 'lilypaw-seo' ) ); ?></small></td>
					<td><strong style="color:#1a0dab"><?php echo esc_html( $row['title'] ); ?></strong> <small>(<?php echo (int) $row['tl']; ?>)</small><br><?php echo esc_html( $row['desc'] ); ?> <small>(<?php echo (int) $row['dl']; ?>)</small></td>
					<td><?php echo $row['notes'] ? '<span style="color:#b32d2e">' . esc_html( implode( '. ', $row['notes'] ) ) . '</span>' : '<span style="color:#00a32a">' . esc_html__( 'Good', 'lilypaw-seo' ) . '</span>'; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}
