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
 use Automattic\WooCommerce\Utilities\FeaturesUtil;
?>
<?php
$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

if(!empty($email_heading) && !empty($email)){
	/*
	* @hooked WC_Emails::email_header() Output the email header
	*/
	do_action( 'woocommerce_email_header', $email_heading, $email ); 
	if ( ! empty( get_option( 'woocommerce_email_subheading_text' ) ) ) {
		?>
		<h1 class="sub_heading"><?php echo esc_html( get_option( 'woocommerce_email_subheading_text' ), ); ?></h1>
	<?php }?>

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
	if ( ! empty( get_option( 'woocommerce_email_body_text' ) ) ) {
		?>
		<p><?php echo esc_html( get_option( 'woocommerce_email_body_text' ) ); ?></p>

	<?php } else { 
		if ( $email_improvements_enabled ) : ?>
		<p><?php esc_html_e( 'We’ve successfully processeddddd your order, and it’s on its way to you.', 'email-customizer-for-woocommerce' ); ?></p>
		<p><?php esc_html_e( 'Here’s a reminderrrr of what you’ve ordered:', 'email-customizer-for-woocommerce' ); ?></p>
	<?php else : ?>
		<p><?php esc_html_e( 'We have finished processing your order.', 'email-customizer-for-woocommerce' ); ?></p>
	<?php endif; 
	}?>
	<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

	<?php

	/*
	* @hooked WC_Emails::order_details() Shows the order details table.
	* @hooked WC_Structured_Data::generate_order_data() Generates structured data.
	* @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
	* @since 2.5.0
	*/
	do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

	/*
	* @hooked WC_Emails::order_meta() Shows order meta data.
	*/
	do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

	/*
	* @hooked WC_Emails::customer_details() Shows customer details
	* @hooked WC_Emails::email_address() Shows email address
	*/
	do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

	/**
	 * Show user-defined additional content - this is set in each email's settings.
	 */
	if ( $additional_content ) {
		echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="email-additional-content">' : '';
		echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
		echo $email_improvements_enabled ? '</td></tr></table>' : '';
	}

	/*
	* @hooked WC_Emails::email_footer() Output the email footer
	*/
	do_action( 'woocommerce_email_footer', $email );
}else{
	if ( ! empty( get_option( 'woocommerce_email_subheading_text' ) ) ) {
		?>
		<h1 class="sub_heading"><?php echo esc_html( get_option( 'woocommerce_email_subheading_text' ), ); ?></h1>
	<?php } else { ?>
		<h1 class="sub_heading"><?php echo esc_html_e( 'HTML Email Sub Heading', 'email-customizer-for-woocommerce' ); ?></h1>
		<?php
	}

	if ( ! empty( get_option( 'woocommerce_email_body_text' ) ) ) {
		?>
		<p><?php echo esc_html( get_option( 'woocommerce_email_body_text' ) ); ?></p>

	<?php } else { ?>
		<p><?php esc_html_e( 'Your order has been received and is now being processed. Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ); ?></p>

		<?php
	}
	?>

	<a href="#"><?php esc_html_e( 'Order templete two', 'email-customizer-for-woocommerce' ); ?> #2020</a>

	<table>
		<thead>
			<tr>
				<th><?php esc_html_e( 'Product', 'email-customizer-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Quantity', 'email-customizer-for-woocommerce' ); ?></th>
				<th><?php esc_html_e( 'Price', 'email-customizer-for-woocommerce' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td>Ninja Silhouette<br /></td>
				<td>1</td>
				<td>
					<span>$20.00</span> <small><?php esc_html_e( '(ex. tax)', 'email-customizer-for-woocommerce' ); ?></small>
				</td>
			</tr>
		</tbody>

		<tfoot>
			<tr>
				<th colspan="2"><?php esc_html_e( 'Subtotal:', 'email-customizer-for-woocommerce' ); ?></th>
				<td>
					<span>$20.00</span> <small><?php esc_html_e( '(ex. tax)', 'email-customizer-for-woocommerce' ); ?></small>
				</td>
			</tr>

			<tr>
				<th colspan="2"><?php esc_html_e( 'Shipping:', 'email-customizer-for-woocommerce' ); ?></th>
				<td><?php esc_html_e( 'Free Shipping', 'email-customizer-for-woocommerce' ); ?></td>
			</tr>

			<tr>
				<th colspan="2"><?php esc_html_e( 'Tax:', 'email-customizer-for-woocommerce' ); ?></th>
				<td>
					<span>$2.00</span>
				</td>
			</tr>

			<tr>
				<th colspan="2"><?php esc_html_e( 'Payment Method:', 'email-customizer-for-woocommerce' ); ?></th>
				<td><?php esc_html_e( 'Direct Bank Transfer', 'email-customizer-for-woocommerce' ); ?></td>
			</tr>

			<tr>
				<th colspan="2"><?php esc_html_e( 'Total:', 'email-customizer-for-woocommerce' ); ?></th>
				<td>
					<span>$22.00</span>
				</td>
			</tr>
		</tfoot>
	</table>
	<br />

	<h3><?php esc_html_e( 'Billing address', 'email-customizer-for-woocommerce' ); ?></h3>
	<table class="addresses">
		<tr>
			<td valign="top" width="100%">
				<p>
					John Doe<br />
					1234 Fake Street<br />
					WooVille, SA
				</p>
			</td>
		</tr>
	</table>

	<h3><?php esc_html_e( 'Shipping address', 'email-customizer-for-woocommerce' ); ?></h3>
	<table class="addresses">
		<tr>
			<td valign="top" width="100%">
				<p>
					John Doe<br />
					1234 Fake Street<br />
					WooVille, SA
				</p>
			</td>
		</tr>
	</table>
<?php
}
