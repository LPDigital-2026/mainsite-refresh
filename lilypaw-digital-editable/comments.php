<?php
/**
 * Comments template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area card" style="padding:30px;margin-top:30px;">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comment_count = get_comments_number();
			if ( '1' === $comment_count ) {
				esc_html_e( '1 Comment', 'lilypaw-digital' );
			} else {
				echo esc_html( $comment_count ) . ' ' . esc_html__( 'Comments', 'lilypaw-digital' );
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol>

		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'lilypaw-digital' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div>
