<?php
/**
 * Sidebar template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<div id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div>
