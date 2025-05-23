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

?>
<?php
if ( ! empty( get_option( 'woocommerce_email_subheading_text' ) ) ) {
	?>
	<h1 class="sub_heading"><?php echo esc_html( get_option( 'woocommerce_email_subheading_text' ) ); ?></h1>
<?php } else { ?>
	<h1 class="sub_heading"><?php echo esc_html_e( 'HTML Email sub heading','email-customizer-for-woocommerce' ); ?></h1>
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

<a href="#"><?php esc_html_e( 'Order', 'email-customizer-for-woocommerce' ); ?> #2020</a>

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
<table class="addresses">
	<tr>
		<td valign="top" width="50%">
			<h3><?php esc_html_e( 'Billing address', 'email-customizer-for-woocommerce' ); ?></h3>

			<p>
				John Doe<br />
				1234 Fake Street<br />
				WooVille, SA
			</p>
		</td>

		<td valign="top" width="50%">
			<h3><?php esc_html_e( 'Shipping address', 'email-customizer-for-woocommerce' ); ?></h3>

			<p>
				John Doe<br />
				1234 Fake Street<br />
				WooVille, SA
			</p>
		</td>
	</tr>
</table>
