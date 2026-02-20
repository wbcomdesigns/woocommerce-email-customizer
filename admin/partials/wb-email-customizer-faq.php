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
								<?php esc_html_e( 'You can contact our team for custom development assistance.', 'email-customizer-for-woocommerce' ); ?>    
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
							<p> 
								<?php esc_html_e( 'No problem. Please get in touch with us via our', 'email-customizer-for-woocommerce' ); ?>   
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/contact/' ); ?>" target="_blank"><?php esc_html_e( 'Contact page', 'email-customizer-for-woocommerce' ); ?></a> 
							</p>
						</div>
					</div>
				</div>
				<div class="wbcom-faq-section-row">
					<div class="wbcom-faq-admin-row">
						<button class="wbcom-faq-accordion">
							<?php esc_html_e( 'What types of emails can be customized with this plugin?', 'email-customizer-for-woocommerce' ); ?>
						</button>
						<div class="wbcom-faq-panel">
							<p> 
								<?php esc_html_e( 'You can customize all default WooCommerce emails order related.', 'email-customizer-for-woocommerce' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="wbcom-faq-section-row">
					<div class="wbcom-faq-admin-row">
						<button class="wbcom-faq-accordion">
							<?php esc_html_e( 'What if I need additional features?', 'email-customizer-for-woocommerce' ); ?>
						</button>
						<div class="wbcom-faq-panel">
							<p> 
								<?php esc_html_e( 'If you require extra features or custom functionality, our team is available for hire to assist with tailored development.', 'email-customizer-for-woocommerce' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="wbcom-faq-section-row">
					<div class="wbcom-faq-admin-row">
						<button class="wbcom-faq-accordion">
							<?php esc_html_e( 'What if I have a question or need support?', 'email-customizer-for-woocommerce' ); ?>
						</button>
						<div class="wbcom-faq-panel">
							<p> 
								<?php esc_html_e( 'No worries! Feel free to reach out to us anytime via our Contact page. We’re happy to help.', 'email-customizer-for-woocommerce' ); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
