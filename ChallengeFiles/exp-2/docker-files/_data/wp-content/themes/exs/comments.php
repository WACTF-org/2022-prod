<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

//new since v1.9.5
$mt        = exs_option( 'blog_single_comments_mt' );
$mb        = exs_option( 'blog_single_comments_mb' );
$bg        = exs_option( 'blog_single_comments_background' );
$section   = exs_option( 'blog_single_comments_section' ) ? 'section' : '';
if ( $bg && ! $section ) {
	$bg .= ' extra-padding';
}
$pt        = exs_option( 'blog_single_comments_pt' );
$pb        = exs_option( 'blog_single_comments_pb' );

?>

<div id="comments" class="comments-area <?php echo esc_attr( $mt . ' ' . $mb . ' ' . $bg . ' ' . $section . ' ' . $pt . ' ' . $pb ); ?>">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php
			$exs_comments_number = get_comments_number();
			if ( '1' === $exs_comments_number ) {
				/* translators: %s: post title */
				printf( esc_html_x( 'One Reply to &ldquo;%s&rdquo;', 'comments title', 'exs' ), esc_html( get_the_title() ) );
			} else {
				printf(
					esc_html(
						/* translators: 1: number of comments, 2: post title */
						_nx(
							'%1$s Reply to &ldquo;%2$s&rdquo;',
							'%1$s Replies to &ldquo;%2$s&rdquo;',
							$exs_comments_number,
							'comments title',
							'exs'
						)
					),
					esc_html(
						number_format_i18n(
							$exs_comments_number
						)
					),
					esc_html( get_the_title() )
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'avatar_size' => 100,
					'style'       => 'ol',
					'short_ping'  => true,
					'reply_text'  => exs_icon( 'reply', true ) . ' ' . esc_html__( 'Reply', 'exs' ),
					'login_text'  => exs_icon( 'reply', true ) . ' ' . esc_html__( 'Login to Reply', 'exs' ),
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			exs_get_the_posts_pagination_atts()
		);

	endif; // Check for have_comments().

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'exs' ); ?></p>
		<?php
	endif; //comments_open

	//placehoders for fields
	$exs_commenter = wp_get_current_commenter();
	comment_form(
		array(

			'comment_field' => '<p class="comment-form-comment"><label class="screen-reader-text" for="comment">' . esc_html_x( 'Comment', 'noun', 'exs' ) . '</label> <textarea id="comment" name="comment"  placeholder="' . esc_attr__( 'Comment', 'exs' ) . '" cols="45" rows="8" maxlength="65525" required="required"></textarea></p>',
			'fields'        => array(

				'author' => '<p class="comment-form-author"><label class="screen-reader-text" for="author">' . esc_html__( 'Name', 'exs' ) . ' <span class="required">*</span></label> ' .
							'<input id="author" name="author" type="text" placeholder="' . esc_attr__( 'Name', 'exs' ) . '" value="' . esc_attr( $exs_commenter['comment_author'] ) . '" size="30" maxlength="245" required="required" /></p>',
				'email'  => '<p class="comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Email', 'exs' ) . ' <span class="required">*</span></label> ' .
							'<input id="email" name="email" type="email" placeholder="' . esc_attr__( 'Email', 'exs' ) . '" value="' . esc_attr( $exs_commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes" required="required" /></p>',
				'url'    => '',
			),

		)
	);

	?>
</div><!-- #comments -->
