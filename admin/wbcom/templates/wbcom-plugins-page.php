<div class="wrap">
	<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
	<?php
	$wbcom_setting_obj = new Wbcom_Admin_Settings();
	$free_plugins      = $wbcom_setting_obj->wbcom_all_free_plugins();
	$paid_plugins      = $wbcom_setting_obj->wbcom_all_paid_plugins();
	?>
	<h4 class="wbcom-plugin-heading"><?php esc_html_e( 'Free Addons', 'email-customizer-for-woocommerce' ); ?></h4>
	<div class="reign-demos-wrapper reign-importer-section">
		<div class="reign-demos-inner-wrapper">
			<?php
			foreach ( $free_plugins as $key => $plugin_details ) {
					$plugin_btn_text = esc_html__( 'View', 'email-customizer-for-woocommerce' );
					$toggle_class    = 'fa fa-eye';
				?>
				<div class="wbcom-req-plugin-card">
					<div class="wbcom_single_left">
						<div class="wbcom_single_icon_wrapper">
							<i class="<?php echo esc_attr( $plugin_details['icon'] ); ?>" aria-hidden="true"></i>
						</div>
					</div>
					<div class="wbcom_single_right">
						<h3><a href="<?php echo esc_url( $plugin_details['wp_url'] ); ?>"><?php echo esc_html( $plugin_details['name'] ); ?></a></h3>
						<p class="plugin-description"><?php echo esc_html( $plugin_details['description'] ); ?></p>
						<input type="hidden" class="plugin-slug" name="plugin-slug" value="<?php echo esc_attr( $plugin_details['slug'] ); ?>">
						<div class="activation_button_wrap">
							<a href="<?php echo esc_url( $plugin_details['wp_url'] ); ?>" class="wbcom-plugin-action-button wb_btn wb_btn_default" >
								<i class="<?php echo esc_attr( $toggle_class ); ?>"></i>
								<?php echo esc_html( $plugin_btn_text ); ?>
							</a>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<h4 class="wbcom-plugin-heading"><?php esc_html_e( 'Paid Addons', 'email-customizer-for-woocommerce' ); ?></h4>
	<div class="reign-demos-wrapper reign-importer-section">
		<div class="reign-demos-inner-wrapper">
			<?php
			foreach ( $paid_plugins as $key => $plugin_details ) {
				?>
				<div class="wbcom-req-plugin-card">
					<div class="wbcom_single_left">
						<div class="wbcom_single_icon_wrapper">
							<i class="<?php echo esc_attr( $plugin_details['icon'] ); ?>" aria-hidden="true"></i>
						</div>
					</div>
					<div class="wbcom_single_right">
						<h3><?php echo esc_html( $plugin_details['name'] ); ?></h3>
						<p class="plugin-description"><?php echo esc_html( $plugin_details['description'] ); ?></p>
						<div class="activation_button_wrap">
							<a href="<?php echo esc_url( $plugin_details['download_url'] ); ?>" class="wb_btn wb_btn_default" target="_blank" >
								<i class="fa fa-eye"></i>
								<?php esc_html_e( 'View', 'email-customizer-for-woocommerce' ); ?>
							</a>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
