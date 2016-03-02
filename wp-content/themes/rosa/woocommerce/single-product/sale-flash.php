<?php
/*  for PRO users! - *
 * Single Product Sale Flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<?php if ( $product->is_on_sale() ) : ?>

	<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="product__badge  on-sale">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>

<?php endif; ?>