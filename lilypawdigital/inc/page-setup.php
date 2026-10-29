<?php
/**
 * Page setup: builds the site's pages from the content shipped in
 * inc/pages/, so a fresh install needs no copy-pasting.
 *
 * On activation the theme creates any standard page that doesn't exist
 * yet, makes Home the front page (only if no static front page is set)
 * and builds the primary menu (only if none is assigned). It never
 * overwrites a page that already exists: that is an explicit, per-page
 * "Replace" on Appearance -> Page Setup, and WordPress keeps the old
 * content as a revision so it can be restored.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The site's pages, in menu order. Keys are the page slugs the theme's
 * links and templates rely on (e.g. page.php widens /colocation/).
 */
function lpd_setup_pages() {
	return array(
		'home'        => array(
			'title' => __( 'Home', 'lilypawdigital' ),
			'file'  => 'home.html',
		),
		'colocation'  => array(
			'title' => __( 'Colocation', 'lilypawdigital' ),
			'file'  => 'colocation.html',
		),
		'our-work'    => array(
			'title' => __( 'Our Work', 'lilypawdigital' ),
			'file'  => 'our-work.html',
		),
		'about-us'    => array(
			'title' => __( 'About Us', 'lilypawdigital' ),
			'file'  => 'about-us.html',
		),
		'contact-us'  => array(
			'title' => __( 'Contact Us', 'lilypawdigital' ),
			'file'  => 'contact-us.html',
		),
		'get-started' => array(
			'title'   => __( 'Get Started', 'lilypawdigital' ),
			'file'    => 'get-started.html',
			'warning' => __( 'Replacing this page removes anything added to it since, such as a price calculator.', 'lilypawdigital' ),
		),
	);
}

/**
 * Block content for a page, with theme asset paths filled in.
 */
function lpd_setup_page_content( $slug ) {
	$pages = lpd_setup_pages();
	if ( empty( $pages[ $slug ] ) ) {
		return '';
	}

	$path = get_template_directory() . '/inc/pages/' . $pages[ $slug ]['file'];
	if ( ! is_readable( $path ) ) {
		return '';
	}

	$content = (string) file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file
	return str_replace( '{{theme_uri}}', get_template_directory_uri(), $content );
}

function lpd_setup_find_page( $slug ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	return ( $page && 'trash' !== $page->post_status ) ? $page : null;
}

/**
 * Create one page if it doesn't exist yet. Returns the page ID, or a
 * WP_Error. An existing page is returned untouched.
 */
function lpd_setup_create_page( $slug ) {
	$existing = lpd_setup_find_page( $slug );
	if ( $existing ) {
		return (int) $existing->ID;
	}

	$pages = lpd_setup_pages();
	return wp_insert_post(
		wp_slash(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $pages[ $slug ]['title'],
				'post_name'    => $slug,
				'post_content' => lpd_setup_page_content( $slug ),
			)
		),
		true
	);
}

/**
 * Overwrite an existing page's content with the theme's version.
 * wp_update_post() stores the previous content as a revision.
 */
function lpd_setup_replace_page( $slug ) {
	$page = lpd_setup_find_page( $slug );
	if ( ! $page ) {
		return lpd_setup_create_page( $slug );
	}

	return wp_update_post(
		wp_slash(
			array(
				'ID'           => $page->ID,
				'post_content' => lpd_setup_page_content( $slug ),
			)
		),
		true
	);
}

/**
 * Create every missing page. Returns the slugs that were created.
 */
function lpd_setup_create_missing() {
	$created = array();
	foreach ( array_keys( lpd_setup_pages() ) as $slug ) {
		if ( lpd_setup_find_page( $slug ) ) {
			continue;
		}
		$result = lpd_setup_create_page( $slug );
		if ( ! is_wp_error( $result ) ) {
			$created[] = $slug;
		}
	}
	return $created;
}

function lpd_setup_set_front_page() {
	$home = lpd_setup_find_page( 'home' );
	if ( ! $home ) {
		return false;
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', (int) $home->ID );
	return true;
}

/**
 * Top up the header menu with the site's pages. Uses the menu already in
 * the primary location if there is one; otherwise finds or creates "Main
 * Menu" and assigns it. Pages already in the menu aren't added twice.
 */
function lpd_setup_build_menu() {
	$locations = (array) get_theme_mod( 'nav_menu_locations', array() );
	$menu      = ! empty( $locations['primary'] ) ? wp_get_nav_menu_object( $locations['primary'] ) : false;
	if ( ! $menu ) {
		$menu = wp_get_nav_menu_object( __( 'Main Menu', 'lilypawdigital' ) );
	}
	$menu_id = $menu ? (int) $menu->term_id : wp_create_nav_menu( __( 'Main Menu', 'lilypawdigital' ) );
	if ( is_wp_error( $menu_id ) ) {
		return $menu_id;
	}

	$in_menu = array();
	foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $item ) {
		if ( 'page' === $item->object ) {
			$in_menu[] = (int) $item->object_id;
		}
	}

	foreach ( lpd_setup_pages() as $slug => $page_def ) {
		$page = lpd_setup_find_page( $slug );
		if ( ! $page || in_array( (int) $page->ID, $in_menu, true ) ) {
			continue;
		}
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $page_def['title'],
				'menu-item-object'    => 'page',
				'menu-item-object-id' => (int) $page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}

	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	return $menu_id;
}

function lpd_setup_has_primary_menu() {
	$locations = get_nav_menu_locations();
	return ! empty( $locations['primary'] ) && wp_get_nav_menu_object( $locations['primary'] );
}

/**
 * First activation: only fill gaps, never change what's already there.
 * Runs after WordPress has carried menu locations over from the previous
 * theme (core does that at priority 10).
 */
function lpd_setup_on_activate() {
	if ( ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'publish_pages' ) ) {
		return;
	}

	$summary = array( 'created' => lpd_setup_create_missing() );

	if ( 'page' !== get_option( 'show_on_front' ) || ! get_option( 'page_on_front' ) ) {
		$summary['front_page'] = lpd_setup_set_front_page();
	}

	if ( ! lpd_setup_has_primary_menu() ) {
		$summary['menu'] = ! is_wp_error( lpd_setup_build_menu() );
	}

	set_transient( 'lpd_setup_summary', $summary, HOUR_IN_SECONDS );
}
add_action( 'after_switch_theme', 'lpd_setup_on_activate', 20 );

function lpd_setup_activation_notice() {
	$summary = get_transient( 'lpd_setup_summary' );
	if ( ! $summary || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	delete_transient( 'lpd_setup_summary' );

	$parts = array();
	if ( ! empty( $summary['created'] ) ) {
		/* translators: %d: number of pages */
		$parts[] = sprintf( _n( 'created %d page', 'created %d pages', count( $summary['created'] ), 'lilypawdigital' ), count( $summary['created'] ) );
	}
	if ( ! empty( $summary['front_page'] ) ) {
		$parts[] = __( 'set Home as the front page', 'lilypawdigital' );
	}
	if ( ! empty( $summary['menu'] ) ) {
		$parts[] = __( 'built the main menu', 'lilypawdigital' );
	}

	$message = $parts
		/* translators: %s: list of what the theme did, e.g. "created 6 pages, built the main menu" */
		? sprintf( __( 'Lily Paw Digital theme: %s. Existing pages were left as they were.', 'lilypawdigital' ), implode( ', ', $parts ) )
		: __( 'Lily Paw Digital theme: all pages already exist, so nothing was changed.', 'lilypawdigital' );

	printf(
		'<div class="notice notice-success is-dismissible"><p>%s <a href="%s">%s</a></p></div>',
		esc_html( $message ),
		esc_url( admin_url( 'themes.php?page=lpd-page-setup' ) ),
		esc_html__( 'Review pages', 'lilypawdigital' )
	);
}
add_action( 'admin_notices', 'lpd_setup_activation_notice' );

/* -------------------------------------------------------------------------
 * Appearance -> Page Setup
 * ---------------------------------------------------------------------- */

function lpd_setup_admin_menu() {
	add_theme_page(
		__( 'Page Setup', 'lilypawdigital' ),
		__( 'Page Setup', 'lilypawdigital' ),
		'edit_theme_options',
		'lpd-page-setup',
		'lpd_setup_render_admin_page'
	);
}
add_action( 'admin_menu', 'lpd_setup_admin_menu' );

function lpd_setup_handle_action() {
	if ( ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'publish_pages' ) ) {
		wp_die( esc_html__( 'You are not allowed to set up pages.', 'lilypawdigital' ), 403 );
	}
	check_admin_referer( 'lpd_page_setup' );

	$do    = isset( $_POST['lpd_do'] ) ? sanitize_key( wp_unslash( $_POST['lpd_do'] ) ) : '';
	$slug  = isset( $_POST['lpd_slug'] ) ? sanitize_title( wp_unslash( $_POST['lpd_slug'] ) ) : '';
	$pages = lpd_setup_pages();
	$done  = '';

	if ( in_array( $do, array( 'create', 'replace' ), true ) && isset( $pages[ $slug ] ) ) {
		$result = 'create' === $do ? lpd_setup_create_page( $slug ) : lpd_setup_replace_page( $slug );
		$done   = is_wp_error( $result ) ? 'error' : $do;
	} elseif ( 'create_missing' === $do ) {
		lpd_setup_create_missing();
		$done = 'create_missing';
	} elseif ( 'front_page' === $do ) {
		$done = lpd_setup_set_front_page() ? 'front_page' : 'error';
	} elseif ( 'menu' === $do ) {
		$done = is_wp_error( lpd_setup_build_menu() ) ? 'error' : 'menu';
	}

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'     => 'lpd-page-setup',
				'lpd_done' => $done,
				'lpd_slug' => $slug,
			),
			admin_url( 'themes.php' )
		)
	);
	exit;
}
add_action( 'admin_post_lpd_page_setup', 'lpd_setup_handle_action' );

/**
 * One self-contained form per button keeps each action explicit.
 */
function lpd_setup_button( $do, $label, $slug = '', $primary = false, $confirm = '' ) {
	?>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin:0 6px 6px 0;">
		<?php wp_nonce_field( 'lpd_page_setup' ); ?>
		<input type="hidden" name="action" value="lpd_page_setup">
		<input type="hidden" name="lpd_do" value="<?php echo esc_attr( $do ); ?>">
		<input type="hidden" name="lpd_slug" value="<?php echo esc_attr( $slug ); ?>">
		<button type="submit" class="button<?php echo $primary ? ' button-primary' : ''; ?>"<?php echo $confirm ? ' onclick="return confirm(' . esc_attr( wp_json_encode( $confirm ) ) . ');"' : ''; ?>>
			<?php echo esc_html( $label ); ?>
		</button>
	</form>
	<?php
}

function lpd_setup_render_admin_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$pages    = lpd_setup_pages();
	$front_id = 'page' === get_option( 'show_on_front' ) ? (int) get_option( 'page_on_front' ) : 0;
	$missing  = 0;

	$messages = array(
		'create'         => __( 'Page created.', 'lilypawdigital' ),
		'replace'        => __( 'Page replaced with the theme version. The previous content is saved under Revisions on that page.', 'lilypawdigital' ),
		'create_missing' => __( 'Missing pages created.', 'lilypawdigital' ),
		'front_page'     => __( 'Home is now the front page.', 'lilypawdigital' ),
		'menu'           => __( 'Main menu built and assigned.', 'lilypawdigital' ),
		'error'          => __( 'That didn\'t work. Check you have permission to publish pages, then try again.', 'lilypawdigital' ),
	);
	$done = isset( $_GET['lpd_done'] ) ? sanitize_key( wp_unslash( $_GET['lpd_done'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Page Setup', 'lilypawdigital' ); ?></h1>
		<p style="max-width:640px;">
			<?php esc_html_e( 'Creates the site\'s pages with the theme\'s ready-made content. Creating only adds pages that don\'t exist yet. Replacing overwrites a page with the theme version; WordPress keeps the old content as a revision, so it can be restored from the page editor.', 'lilypawdigital' ); ?>
		</p>

		<?php if ( isset( $messages[ $done ] ) ) : ?>
			<div class="notice <?php echo 'error' === $done ? 'notice-error' : 'notice-success'; ?> is-dismissible"><p><?php echo esc_html( $messages[ $done ] ); ?></p></div>
		<?php endif; ?>

		<table class="widefat striped" style="max-width:960px;margin-top:16px;">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Page', 'lilypawdigital' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Status', 'lilypawdigital' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Actions', 'lilypawdigital' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $pages as $slug => $page_def ) : ?>
				<?php
				$page = lpd_setup_find_page( $slug );
				if ( ! $page ) {
					$missing++;
					$status = __( 'Not created yet', 'lilypawdigital' );
				} elseif ( trim( $page->post_content ) === trim( lpd_setup_page_content( $slug ) ) ) {
					$status = __( 'Matches the theme version', 'lilypawdigital' );
				} else {
					$status = __( 'Edited (differs from the theme version)', 'lilypawdigital' );
				}
				if ( $page && 'publish' !== $page->post_status ) {
					/* translators: %s: post status, e.g. draft */
					$status .= ' · ' . sprintf( __( 'status: %s', 'lilypawdigital' ), $page->post_status );
				}
				if ( $page && (int) $page->ID === $front_id ) {
					$status .= ' · ' . __( 'front page', 'lilypawdigital' );
				}
				?>
				<tr>
					<td>
						<strong><?php echo esc_html( $page_def['title'] ); ?></strong><br>
						<code>/<?php echo esc_html( $slug ); ?>/</code>
						<?php if ( $page ) : ?>
							· <a href="<?php echo esc_url( get_permalink( $page ) ); ?>"><?php esc_html_e( 'View', 'lilypawdigital' ); ?></a>
							· <a href="<?php echo esc_url( get_edit_post_link( $page->ID ) ); ?>"><?php esc_html_e( 'Edit', 'lilypawdigital' ); ?></a>
						<?php endif; ?>
					</td>
					<td><?php echo esc_html( $status ); ?></td>
					<td>
						<?php
						if ( ! $page ) {
							lpd_setup_button( 'create', __( 'Create page', 'lilypawdigital' ), $slug, true );
						} else {
							$confirm = sprintf(
								/* translators: %s: page title */
								__( 'Replace the content of "%s" with the theme version? The current content is kept as a revision.', 'lilypawdigital' ),
								$page_def['title']
							);
							if ( ! empty( $page_def['warning'] ) ) {
								$confirm .= ' ' . $page_def['warning'];
							}
							lpd_setup_button( 'replace', __( 'Replace with theme version', 'lilypawdigital' ), $slug, false, $confirm );
							if ( ! empty( $page_def['warning'] ) ) {
								echo '<p class="description" style="margin:0;">' . esc_html( $page_def['warning'] ) . '</p>';
							}
						}
						?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<h2 style="margin-top:28px;"><?php esc_html_e( 'Site settings', 'lilypawdigital' ); ?></h2>
		<p>
			<?php
			if ( $missing ) {
				/* translators: %d: number of missing pages */
				lpd_setup_button( 'create_missing', sprintf( _n( 'Create %d missing page', 'Create %d missing pages', $missing, 'lilypawdigital' ), $missing ), '', true );
			}
			$home = lpd_setup_find_page( 'home' );
			if ( $home && (int) $home->ID !== $front_id ) {
				lpd_setup_button( 'front_page', __( 'Use Home as the front page', 'lilypawdigital' ) );
			}
			lpd_setup_button(
				'menu',
				lpd_setup_has_primary_menu() ? __( 'Add missing pages to the main menu', 'lilypawdigital' ) : __( 'Build the main menu', 'lilypawdigital' )
			);
			?>
		</p>
		<p class="description" style="max-width:640px;">
			<?php esc_html_e( 'The menu button adds any of these pages missing from the header menu (or creates "Main Menu" if the header has none). Pages already in it aren\'t added twice; reorder it under Appearance → Menus.', 'lilypawdigital' ); ?>
		</p>
	</div>
	<?php
}
