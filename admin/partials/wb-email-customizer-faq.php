<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    email-customizer-for-woocommerce
 * @subpackage email-customizer-for-woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content">      
<div class="wb-email-customizer-admin-setting">
	<div class="wb-email-customizer-tab-header">
		<h3><?php esc_html_e( 'FAQ(s) ', 'email-customizer-for-woocommerce' ); ?></h3>
		<input type="hidden" class="wb-ads-tab-active" value="support"/>
	</div>

	<div class="wb-email-customizer-admin-settings-block">
		<div id="wb-email-customizer-tbl" class="wb-ads-table">
			<div class="wb-email-customizer-admin-row border">
				<div class="wb-email-customizer-admin-col-12">
					<button class="wb-email-customizer-accordion">
						<?php esc_html_e( 'Does the default woocommerce elements are customizable?', 'email-customizer-for-woocommerce' ); ?>
					</button>
					<div class="wb-email-customizer-panel">
						<p> 
							<?php esc_html_e( 'Yes, all the default WooCommerce elements are customizable.', 'email-customizer-for-woocommerce' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="wb-email-customizer-admin-row border">
				<div class="wb-email-customizer-admin-col-12">
					<button class="wb-email-customizer-accordion">
						<?php esc_html_e( 'What Types of Emails can be customized using the plugin', 'email-customizer-for-woocommerce' ); ?>
					</button>
					<div class="wb-email-customizer-panel">
						<p> 
							<?php esc_html_e( 'You can customize all the default WooCommerce emails using this plugin.', 'email-customizer-for-woocommerce' ); ?>     
						</p>
					</div>
				</div>
			</div>
			<div class="wb-email-customizer-admin-row border">
				<div class="wb-email-customizer-admin-col-12">
					<button class="wb-email-customizer-accordion">
						<?php esc_html_e( 'What if I need more features?', 'email-customizer-for-woocommerce' ); ?>
					</button>
					<div class="wb-email-customizer-panel">
						<p> 
							<?php esc_html_e( 'You can hire our team to assist you.', 'email-customizer-for-woocommerce' ); ?>    
						</p>
					</div>
				</div>
			</div>
			<div class="wb-email-customizer-admin-row border">
				<div class="wb-email-customizer-admin-col-12">
					<button class="wb-email-customizer-accordion">
						<?php esc_html_e( 'What if I have a question?', 'email-customizer-for-woocommerce' ); ?>
					</button>
					<div class="wb-email-customizer-panel">

					<?php
					$contatct_page = '<a href="https://wbcomdesigns.com/contact/">contact page</a>';
					?>
						<p> 
							<?php esc_html_e( 'No problem. Please get in touch with us via our', 'email-customizer-for-woocommerce' ); ?>   
							<a href="https://wbcomdesigns.com/contact/" target="_blank"><?php echo esc_html( 'contact page.' ); ?></a> 
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

