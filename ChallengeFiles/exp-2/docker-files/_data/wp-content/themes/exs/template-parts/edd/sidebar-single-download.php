<?php
/**
 * The template for displaying single download sidebar
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Get the author options.
$author_options = exs_edd_download_author_options();

// Get the download options.
$download_options = exs_edd_download_details_options();


do_action( 'exs_edd_sidebar_download_start' ); ?>

<?php if ( ! is_active_sidebar( 'sidebar-download' ) ) : ?>

	<div class="widget widget_edd_product_details">
		<?php
		/**
		 * The price and purchase button are loaded onto this hook.
		 * This hook is also added to EDD's Download Details widget.
		 */
		do_action( 'exs_edd_download_info', $post->ID ); ?>
	</div>

	<?php do_action( 'exs_edd_sidebar_download_product_details_after' ); ?>

	<?php
	/**
	 * Show the Author Details
	 */
	if ( exs_edd_show_download_author() ) : ?>

		<div class="widget downloadAuthor">

			<?php
			/**
			 * Author avatar
			 */
			$user       = new WP_User( $post->post_author );
			$vendor_url = exs_is_edd_fes_active() ? ( new exs_EDD_Frontend_Submissions )->author_url( get_the_author_meta( 'ID', $post->post_author ) ) : '';

			if ( true === $author_options['avatar'] ) : ?>

				<div class="downloadAuthor-avatar">
					<?php if ( $vendor_url ) : ?>
						<a href="<?php echo esc_url( $vendor_url ); ?>"><?php echo get_avatar( $user->ID, $author_options['avatar_size'], '', get_the_author_meta( 'display_name' ) ); ?></a>
					<?php else : ?>
						<?php echo get_avatar( $user->ID, $author_options['avatar_size'], '', get_the_author_meta( 'display_name' ) ); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php
			/**
			 * Author's store name.
			 */
			if ( true === $author_options['store_name'] ) :
				$store_name = get_the_author_meta( 'name_of_store', $post->post_author );
				?>

				<?php if ( exs_is_edd_fes_active() && ! empty( $store_name ) ) : ?>
				<h2 class="widget-title"><?php echo wp_kses( $store_name, array( 'span' => array( 'class' ) ) ); ?></h2>
			<?php endif; ?>

			<?php endif; ?>

			<ul>

				<?php do_action( 'exs_edd_sidebar_download_author_list_start', $author_options ); ?>

				<?php
				/**
				 * Author name.
				 */
				if ( true === $author_options['name'] ) : ?>

					<li class="downloadAuthor-author">
						<span class="downloadAuthor-name"><?php esc_html_e( 'Author:', 'exs' ); ?></span>
						<span class="downloadAuthor-value">
						<?php if ( exs_is_edd_fes_active() ) : ?>
							<a class="vendor-url" href="<?php echo esc_url( $vendor_url ); ?>">
								<?php echo wp_kses( $user->display_name, array( 'span' => array( 'class' ) ) ); ?>
							</a>
						<?php else : ?>
							<?php echo wp_kses( $user->display_name, array( 'span' => array( 'class' ) ) ); ?>
						<?php endif; ?>
					</span>
					</li>
				<?php endif; ?>

				<?php
				/**
				 * Author signup date.
				 */
				if ( true === $author_options['signup_date'] ) : ?>

					<li class="downloadAuthor-authorSignupDate">
						<span class="downloadAuthor-name"><?php esc_html_e( 'Author since:', 'exs' ); ?></span>
						<span
							class="downloadAuthor-value"><?php echo wp_kses( date_i18n( get_option( 'date_format' ), strtotime( $user->user_registered ) ), array( 'span' => array( 'class' ) ) ); ?></span>
					</li>
				<?php endif; ?>

				<?php
				/**
				 * Author website.
				 */
				$website = get_the_author_meta( 'user_url', $post->post_author );

				if ( ! empty( $website ) && true === $author_options['website'] ) : ?>

					<li class="downloadAuthor-website">
						<span class="downloadAuthor-name"><?php esc_html_e( 'Website:', 'exs' ); ?></span>
						<span class="downloadAuthor-value"><a href="<?php echo esc_url( $website ); ?>" target="_blank"
						                                      rel="noopener"><?php echo esc_url( $website ); ?></a></span>
					</li>
				<?php endif; ?>

				<?php do_action( 'exs_edd_sidebar_download_author_list_end', $author_options ); ?>

			</ul>

		</div>

	<?php endif; ?>

	<?php do_action( 'exs_edd_sidebar_download_author_after' ); ?>

	<?php
	/**
	 * Show the Download Details
	 */
	if ( exs_edd_show_download_details() ) : ?>

		<div class="widget downloadDetails">

			<?php
			/**
			 * Widget title.
			 */
			if ( ! empty( $download_options['title'] ) ) : ?>
				<h2 class="widget-title"><?php echo wp_kses( $download_options['title'], array( 'span' => array( 'class' => true ) ) ); ?></h2>
			<?php endif; ?>

			<ul>

				<?php do_action( 'exs_edd_sidebar_download_details_list_start', $download_options ); ?>

				<?php
				/**
				 * Date published.
				 */
				if ( true === $download_options['date_published'] ) : ?>
					<li class="downloadDetails-datePublished">
						<span class="downloadDetails-name"><?php esc_html_e( 'Published:', 'exs' ); ?></span>
						<span class="downloadDetails-value"><?php echo wp_kses( exs_edd_download_date_published(), array( 'span' => array( 'class' => true ), 'time' => array( 'class' => true, 'datetime' => true ) ) ); ?></span>
					</li>
				<?php endif; ?>

				<?php
				/**
				 * Sale count.
				 */
				if ( true === $download_options['sale_count'] ) :
					$sales = edd_get_download_sales_stats( $post->ID );
					?>
					<li class="downloadDetails-sales">
						<span class="downloadDetails-name"><?php esc_html_e( 'Sales:', 'exs' ); ?></span>
						<span class="downloadDetails-value"><?php echo esc_html( $sales ); ?></span>
					</li>
				<?php endif; ?>

				<?php
				/**
				 * Version.
				 */
				if ( true === $download_options['version'] ) :

					$version = exs_edd_download_version( $post->ID );

					if ( $version ) : ?>
						<li class="downloadDetails-version">
							<span class="downloadDetails-name"><?php esc_html_e( 'Version:', 'exs' ); ?></span>
							<span class="downloadDetails-value"><?php echo esc_html( $version ); ?></span>
						</li>
					<?php endif; ?>
				<?php endif; ?>

				<?php
				/**
				 * Download categories.
				 */
				if ( true === $download_options['categories'] ) :

					$categories = exs_edd_download_categories( $post->ID );

					if ( $categories ) : ?>
						<li class="downloadDetails-categories">
							<span class="downloadDetails-name"><?php esc_html_e( 'Categories:', 'exs' ); ?></span>
							<span class="downloadDetails-value"><?php echo wp_kses( $categories, array( 'a' => array( 'class' => true, 'href' => true, 'rel' => true ) ) ); ?></span>
						</li>
					<?php endif; ?>
				<?php endif; ?>

				<?php
				/**
				 * Download tags.
				 */
				if ( true === $download_options['tags'] ) :

					$tags = exs_edd_download_tags( $post->ID );

					if ( $tags ) : ?>
						<li class="downloadDetails-tags">
							<span class="downloadDetails-name"><?php esc_html_e( 'Tags:', 'exs' ); ?></span>
							<span class="downloadDetails-value"><?php echo wp_kses( $tags, array( 'a' => array( 'class' => true, 'href' => true, 'rel' => true ) ) ); ?></span>
						</li>
					<?php endif; ?>
				<?php endif; ?>

				<?php do_action( 'exs_edd_sidebar_download_details_list_end', $download_options ); ?>

			</ul>
		</div>
	<?php endif; ?>

<?php

else:
	dynamic_sidebar( 'sidebar-download' );
endif; // end sidebar widget area
?>

<?php do_action( 'exs_edd_sidebar_download_end' ); ?>

