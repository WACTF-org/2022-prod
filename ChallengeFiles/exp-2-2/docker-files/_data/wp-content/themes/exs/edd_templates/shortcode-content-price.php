<?php
/**
 * The template file for displaying EDD price
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php $item_props = edd_add_schema_microdata(); ?>
<div<?php echo ! empty( $item_props ) ? ' itemprop="offers" itemscope itemtype="http://schema.org/Offer"' : '';?>>
	<div itemprop="price" class="edd_price">
	<?php
		exs_edd_price( get_the_ID() );
	?>
	</div>
</div>
