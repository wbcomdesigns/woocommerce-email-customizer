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
	<div class="wbcom-faq-adming-setting">
		<div class="wbcom-admin-title-section">
			<h3><?php esc_html_e( 'Have some questions?', 'email-customizer-for-woocommerce' ); ?></h3>
		</div>
		<div class="wbcom-faq-admin-settings-block">
			<div id="wbcom-faq-settings-section" class="wbcom-faq-table">
				<div class="wbcom-faq-section-row">
					<div class="wbcom-faq-admin-row">
						<button class="wbcom-faq-accordion">
							<?php esc_html_e( 'Does the default woocommerce elements are customizable?', 'email-customizer-for-woocommerce' ); ?>
						</button>
						<div class="wbcom-faq-panel">
							<p> 
								<?php esc_html_e( 'Yes, all the default WooCommerce elements are customizable.', 'email-customizer-for-woocommerce' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="wbcom-faq-section-row">
					<div class="wbcom-faq-admin-row">
						<button class="wbcom-faq-accordion">
							<?php esc_html_e( 'What Types of Emails can be customized using the plugin', 'email-customizer-for-woocommerce' ); ?>
						</button>
						<div class="wbcom-faq-panel">
							<p> 
								<?php esc_html_e( 'You can customize all the default WooCommerce emails using this plugin.', 'email-customizer-for-woocommerce' ); ?>     
							</p>
						</div>
					</div>
				</div>
				<div class="wbcom-faq-section-row">
					<div class="wbcom-faq-admin-row">
						<button class="wbcom-faq-accordion">
							<?php esc_html_e( 'What if I need more features?', 'email-customizer-for-woocommerce' ); ?>
						</button>
						<div class="wbcom-faq-panel">
							<p> 
								<?php esc_html_e( 'You can hire our team to assist you.', 'email-customizer-for-woocommerce' ); ?>    
							</p>
						</div>
					</div>
				</div>
				<div class="wbcom-faq-section-row">
					<div class="wbcom-faq-admin-row">
						<button class="wbcom-faq-accordion">
							<?php esc_html_e( 'What if I have a question?', 'email-customizer-for-woocommerce' ); ?>
						</button>
						<div class="wbcom-faq-panel">

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
