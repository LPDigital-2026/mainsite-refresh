<?php
/**
 * Search form template
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="search" name="s" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'lilypawdigital' ); ?>" value="<?php echo get_search_query(); ?>">
	<button type="submit" class="btn btn-primary" style="border:none;cursor:pointer;">
		<?php echo esc_html_x( 'Search', 'submit button', 'lilypawdigital' ); ?>
	</button>
</form>
