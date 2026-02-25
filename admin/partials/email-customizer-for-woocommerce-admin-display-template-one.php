<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Automattic\WooCommerce\Utilities\FeaturesUtil;
?>
<?php
// phpcs:disable
if ( ! empty( get_option( 'woocommerce_email_subheading_text' ) ) || ( isset( $_GET['woocommerce_email_subheading_text'] ) ) ) {
	$woocommerce_email_subheading_text = ( isset( $_GET['woocommerce_email_subheading_text'] ) ) ? sanitize_text_field( wp_unslash( $_GET['woocommerce_email_subheading_text'] ) ) : get_option( 'woocommerce_email_subheading_text' );
}

if ( ! empty( get_option( 'woocommerce_email_body_text' ) ) || ( isset( $_GET['woocommerce_email_body_text'] ) ) ) {
	$woocommerce_email_body_text = ( isset( $_GET['woocommerce_email_body_text'] ) ) ? sanitize_text_field( wp_unslash( $_GET['woocommerce_email_body_text'] ) ) : get_option( 'woocommerce_email_body_text' );
}
// phpcs:enable
$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );
if ( ! empty( $email_heading ) && ! empty( $email ) ) {
	/*
	* @hooked WC_Emails::email_header() Output the email header
	*/
	do_action( 'woocommerce_email_header', $email_heading, $email );
	if ( ! empty( $woocommerce_email_subheading_text ) ) {
		?>
		<h1 class="sub_heading"><?php echo esc_html( $woocommerce_email_subheading_text ); ?></h1>
	<?php } ?>

	<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>
	<p>
	<?php
	if ( ! empty( $order->get_billing_first_name() ) ) {
		/* translators: %s: Customer first name */
		printf( esc_html__( 'Hi %s,', 'email-customizer-for-woocommerce' ), esc_html( $order->get_billing_first_name() ) );
	} else {
		printf( esc_html__( 'Hi,', 'email-customizer-for-woocommerce' ) );
	}
	?>
	</p>
	<?php
	if ( ! empty( $woocommerce_email_body_text ) ) {
		?>
		<p><?php echo esc_html( $woocommerce_email_body_text ); ?></p>

		<?php
	} elseif ( $email_improvements_enabled ) {

		?>
		<p>
		<?php
		printf(
			/* translators: %1$s: Blog name, %2$s: Order status */
			esc_html__( 'Hi there. Your recent order on %1$s has been %2$s.', 'email-customizer-for-woocommerce' ),
			esc_html( get_bloginfo( 'name' ) ),
			esc_html( $order->get_status() )
		);
		?>
			</p>
		<p><?php esc_html_e( 'Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ); ?></p>
	<?php } else { ?>
		<p><?php esc_html_e( 'We have finished processing your order.', 'email-customizer-for-woocommerce' ); ?></p>
		<?php

	}
	?>
	<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

	<?php
	/*
	* @hooked WC_Emails::order_details() Shows the order details table.
	* @hooked WC_Structured_Data::generate_order_data() Generates structured data.
	* @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
	* @since 2.5.0
	*/
	// phpcs:ignore Squiz.PHP.CommentedOutCode.Found -- Kept for reference.
	// do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );.
	?>
	<h3 class="body-content-title"><?php esc_html_e( 'Order Details', 'email-customizer-for-woocommerce' ); ?></h3>

	<div class="order-details-wrapper" style="display: flex; justify-content: space-between">
		<div class="order-details" style="padding-top: 10px; padding-bottom: 10px; font-style: italic; font-size: inherit; font-weight: inherit;"><?php echo esc_html__( 'Order : #', 'email-customizer-for-woocommerce' ) . ' ' . esc_html( $order->get_order_number() ); ?></div>
		<div class="order-details" style="padding-top: 10px; padding-bottom: 10px; font-style: italic; font-size: inherit; font-weight: inherit;"><?php echo esc_html__( 'Order Date:', 'email-customizer-for-woocommerce' ) . ' ' . esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></div>
	</div>

	<table>
			<thead>
				<tr>
					<th style="width: 50%;"><?php esc_html_e( 'Product', 'email-customizer-for-woocommerce' ); ?></th>
					<th style="text-align: center;"><?php esc_html_e( 'Quantity', 'email-customizer-for-woocommerce' ); ?></th>
					<th style="text-align: right;"><?php esc_html_e( 'Price', 'email-customizer-for-woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $order->get_items() as $item ) : ?>
					<tr>
						<td><?php echo esc_html( $item->get_name() ); ?></td>
						<td style="text-align: center;"><?php echo esc_html( $item->get_quantity() ); ?></td>
						<td style="text-align: right;"><?php echo wp_kses_post( wc_price( $item->get_total() ) ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2"><?php esc_html_e( 'Subtotal:', 'email-customizer-for-woocommerce' ); ?></th>
					<td style="text-align: right;"><?php echo wp_kses_post( wc_price( $order->get_subtotal() ) ); ?></td>
				</tr>
				<tr>
					<th colspan="2"><?php esc_html_e( 'Shipping:', 'email-customizer-for-woocommerce' ); ?></th>
					<td style="text-align: right;"><?php echo $order->get_shipping_total() > 0 ? wp_kses_post( wc_price( $order->get_shipping_total() ) ) : esc_html__( 'Free Shipping', 'email-customizer-for-woocommerce' ); ?></td>
				</tr>
				<tr>
					<th colspan="2"><?php esc_html_e( 'Tax:', 'email-customizer-for-woocommerce' ); ?></th>
					<td style="text-align: right;"><?php echo wp_kses_post( wc_price( $order->get_total_tax() ) ); ?></td>
				</tr>
				<tr>
					<th colspan="2"><?php esc_html_e( 'Payment Method:', 'email-customizer-for-woocommerce' ); ?></th>
					<td style="text-align: right;"><?php echo esc_html( $order->get_payment_method_title() ); ?></td>
				</tr>
				<tr>
					<th colspan="2"><?php esc_html_e( 'Total:', 'email-customizer-for-woocommerce' ); ?></th>
					<td style="text-align: right;"><?php echo wp_kses_post( wc_price( $order->get_total() ) ); ?></td>
				</tr>
			</tfoot>
	</table>

	<?php if ( ! empty( $order->get_customer_note() ) ) : ?>
		<table style="margin: 20px 0;">
			<tr>
				<th>
					<?php esc_html_e( 'The following note has been added to your order:', 'email-customizer-for-woocommerce' ); ?>
				</th>
			</tr>
			<tr>
				<td>
					<?php
					echo wp_kses_post( wptexturize( $order->get_customer_note() ) ) . "\n\n";
					?>
				</td>
			</tr>
		</table>
	<?php endif; ?>

	<h3 class="body-content-title"><?php esc_html_e( 'Billing address', 'email-customizer-for-woocommerce' ); ?></h3>
	<table class="addresses">
		<tr>
			<td valign="top" width="100%">
				<p><?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?></p>
			</td>
		</tr>
	</table>

	<h3 class="body-content-title"><?php esc_html_e( 'Shipping address', 'email-customizer-for-woocommerce' ); ?></h3>
	<table class="addresses">
		<tr>
			<td valign="top" width="100%">
				<p><?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?></p>
			</td>
		</tr>
	</table>

	<?php
	/*
	* @hooked WC_Emails::order_meta() Shows order meta data.
	*/
	// phpcs:ignore Squiz.PHP.CommentedOutCode.Found -- Kept for reference.
	// do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );.

	/*
	* @hooked WC_Emails::customer_details() Shows customer details.
	* @hooked WC_Emails::email_address() Shows email address.
	*/
	// phpcs:ignore Squiz.PHP.CommentedOutCode.Found -- Kept for reference.
	// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );.

	/**
	 * Show user-defined additional content - this is set in each email's settings.
	 */
	if ( ! empty( $additional_content ) ) {
		echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="email-additional-content">' : '';
		echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
		echo $email_improvements_enabled ? '</td></tr></table>' : '';
	}

	/*
	* @hooked WC_Emails::email_footer() Output the email footer
	*/
	do_action( 'woocommerce_email_footer', $email );
} else {
	if ( ! empty( $woocommerce_email_subheading_text ) ) {
		?>
		<h1 class="sub_heading"><?php echo esc_html( $woocommerce_email_subheading_text ); ?></h1>
	<?php } else { ?>
		<h1 class="sub_heading"><?php echo esc_html_e( 'HTML Email sub heading', 'email-customizer-for-woocommerce' ); ?></h1>
		<?php
	}

	if ( ! empty( $woocommerce_email_body_text ) ) {
		?>
		<p><?php echo esc_html( $woocommerce_email_body_text ); ?></p>

	<?php } else { ?>
		<p><?php esc_html_e( 'Your order has been received and is now being processed. Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ); ?></p>

		<?php
	}
	?>

	<h3 class="body-content-title"><?php esc_html_e( 'Order Details', 'email-customizer-for-woocommerce' ); ?></h3>

	<div class="order-details-wrapper" style="display: flex; justify-content: space-between">
		<div class="order-details" style="padding-top: 10px; padding-bottom: 10px; font-style: italic; font-size: inherit; font-weight: inherit;">
			<?php
			printf(
					/* translators: %s: Order number */
				esc_html__( 'Order #%s', 'email-customizer-for-woocommerce' ),
				esc_html( '2020' )
			);
			?>
		</div>
		<div class="order-details" style="padding-top: 10px; padding-bottom: 10px; font-style: italic; font-size: inherit; font-weight: inherit;"><?php esc_html_e( 'Order Date: May 16, 2025', 'email-customizer-for-woocommerce' ); ?></div>
	</div>
	<table>
		<thead>
			<tr>
				<th style="width: 50%;"><?php esc_html_e( 'Product', 'email-customizer-for-woocommerce' ); ?></th>
				<th style="text-align: center;"><?php esc_html_e( 'Quantity', 'email-customizer-for-woocommerce' ); ?></th>
				<th style="text-align: right;"><?php esc_html_e( 'Price', 'email-customizer-for-woocommerce' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td><?php esc_html_e( 'Ninja Silhouette', 'email-customizer-for-woocommerce' ); ?><br /></td>
				<td style="text-align: center;"><?php esc_html_e( '1', 'email-customizer-for-woocommerce' ); ?></td>
				<td style="text-align: right;">
					<span><?php esc_html_e( '$20.00', 'email-customizer-for-woocommerce' ); ?></span> <small><?php esc_html_e( '(ex. tax)', 'email-customizer-for-woocommerce' ); ?></small>
				</td>
			</tr>
		</tbody>

		<tfoot>
			<tr>
				<th colspan="2"><?php esc_html_e( 'Subtotal:', 'email-customizer-for-woocommerce' ); ?></th>
				<td style="text-align: right;">
					<span><?php esc_html_e( '$20.00', 'email-customizer-for-woocommerce' ); ?></span> <small><?php esc_html_e( '(ex. tax)', 'email-customizer-for-woocommerce' ); ?></small>
				</td>
			</tr>

			<tr>
				<th colspan="2"><?php esc_html_e( 'Shipping:', 'email-customizer-for-woocommerce' ); ?></th>
				<td style="text-align: right;"><?php esc_html_e( 'Free Shipping', 'email-customizer-for-woocommerce' ); ?></td>
			</tr>

			<tr>
				<th colspan="2"><?php esc_html_e( 'Tax:', 'email-customizer-for-woocommerce' ); ?></th>
				<td style="text-align: right;">
					<span><?php esc_html_e( '$2.00', 'email-customizer-for-woocommerce' ); ?></span>
				</td>
			</tr>

			<tr>
				<th colspan="2"><?php esc_html_e( 'Payment Method:', 'email-customizer-for-woocommerce' ); ?></th>
				<td style="text-align: right;"><?php esc_html_e( 'Direct Bank Transfer', 'email-customizer-for-woocommerce' ); ?></td>
			</tr>

			<tr>
				<th colspan="2"><?php esc_html_e( 'Total:', 'email-customizer-for-woocommerce' ); ?></th>
				<td style="text-align: right;">
					<span><?php esc_html_e( '$22.00', 'email-customizer-for-woocommerce' ); ?></span>
				</td>
			</tr>
		</tfoot>
	</table>
	<br />
	<h3 class="body-content-title"><?php esc_html_e( 'Billing address', 'email-customizer-for-woocommerce' ); ?></h3>
	<table class="addresses">
		<?php
			$demo_address = array(
				'name'   => __( 'John Doe', 'email-customizer-for-woocommerce' ),
				'street' => __( '1234 Fake Street', 'email-customizer-for-woocommerce' ),
				'city'   => __( 'WooVille, SA', 'email-customizer-for-woocommerce' ),
			);
			?>
		<tr>
			<td valign="top" width="100%">
				<p>
					<?php
					foreach ( $demo_address as $line ) {
						echo esc_html( $line ) . '<br />';
					}
					?>
				</p>
			</td>
		</tr>
	</table>

	<h3 class="body-content-title"><?php esc_html_e( 'Shipping address', 'email-customizer-for-woocommerce' ); ?></h3>
	<table class="addresses">
		<tr>
			<td valign="top" width="100%">
				<p>
					<?php
					foreach ( $demo_address as $line ) {
						echo esc_html( $line ) . '<br />';
					}
					?>
				</p>
			</td>
		</tr>
	</table>
	<?php
}
