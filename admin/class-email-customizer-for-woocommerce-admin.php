<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Email_Customizer_For_Woocommerce
 * @subpackage Email_Customizer_For_Woocommerce/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Email_Customizer_For_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The Email of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $email_trigger    The Email of this plugin.
	 */
	private $email_trigger;

	/**
	 * The panel id of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $panel_id    The panel id of the customizer
	 */
	private $panel_id;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin settings tabs.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var mixed     $plugin_settings_tabs    The settings Tabs.
	 */
	public $plugin_settings_tabs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name   = $plugin_name;
		$this->version       = $version;
		$this->email_trigger = 'email-customizer-for-woocommerce';
		$this->panel_id      = 'wc_email_customizer_panel';

		add_action( 'init', array( $this, 'wb_email_customizer_maybe_run_email_customizer' ) );
	}

	/**
	 * Verify user has WooCommerce management permissions.
	 *
	 * @since  1.0.0
	 * @return bool Whether user has permissions.
	 */
	private function verify_user_permissions(): bool {
		if ( ! current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			wp_die(
				esc_html__( 'You do not have sufficient permissions to access this feature.', 'email-customizer-for-woocommerce' ),
				esc_html__( 'Access Denied', 'email-customizer-for-woocommerce' ),
				array( 'response' => 403 )
			);
			return false;
		}
		return true;
	}


	/**
	 * Conditionally run email customizer based on query parameters.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function wb_email_customizer_maybe_run_email_customizer(): void {
		if ( isset( $_GET[ $this->email_trigger ] ) && isset( $_GET['_wpnonce'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'preview-mail' ) ) {
				add_action( 'wp_print_styles', array( $this, 'wb_email_customizer_remove_theme_styles' ), 100 );
			} else {
				wp_die( esc_html__( 'Invalid nonce.', 'email-customizer-for-woocommerce' ), 403 );
			}
		}

		// Enqueue Customizer script.
		add_action(
			'customize_controls_enqueue_scripts',
			function () {
				if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
					$extension = is_rtl() ? '.rtl.css' : '.css';
				} else {
					$extension = is_rtl() ? '.rtl.css' : '.min.css';
				}
				wp_enqueue_style( 'wb-email-customizer-styles', EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_URL . 'admin/css/customizer-styles' . $extension, array(), EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION );
			}
		);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook The current admin page hook.
	 */
	public function enqueue_styles( $hook ): void {

		// Only load on plugin pages.
		$plugin_pages = array(
			'wbcomplugins',
			'wb-email-customizer-settings',
			'woocommerce_page_wc-settings',
		);

		if (
			! in_array( get_current_screen()->id, $plugin_pages, true ) &&
			false === strpos( $hook, 'email-customizer' )
		) {
			return;
		}

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$extension = is_rtl() ? '.rtl.css' : '.css';
		} else {
			$extension = is_rtl() ? '.rtl.css' : '.min.css';
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/email-customizer-for-woocommerce-admin' . $extension, array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook The current admin page hook.
	 */
	public function enqueue_scripts( $hook ): void {

		// Only load on plugin pages.
		$plugin_pages = array(
			'wbcomplugins',
			'wb-email-customizer-settings',
			'woocommerce_page_wc-settings',
		);

		if (
			! in_array( get_current_screen()->id, $plugin_pages, true ) &&
			false === strpos( $hook, 'email-customizer' )
		) {
			return;
		}

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$extension = '.js';
			$path      = '';
		} else {
			$extension = '.min.js';
			$path      = '/min';
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js' . $path . '/email-customizer-for-woocommerce-admin' . $extension, array( 'jquery' ), $this->version, false );
	}

	/**
	 * Enqueues scripts on the control panel side
	 *
	 * @since 1.0.0
	 */
	public function wb_email_customizer_enqueue_customizer_control_script(): void {

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$extension = '.js';
			$path      = '';
		} else {
			$extension = '.min.js';
			$path      = '/min';
		}
		$localized_vars = array(
			'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			'ajaxSendEmailNonce' => wp_create_nonce( '_wc_email_customizer_send_email_nonce' ),
			'templateResetNonce' => wp_create_nonce( 'wc_email_customizer_email_load_templates' ),
			'error'              => __( 'Error occurred. Please try again.', 'email-customizer-for-woocommerce' ),
			'success'            => __( 'Email Sent!', 'email-customizer-for-woocommerce' ),
			'saveFirst'          => __( 'Please save your changes before sending the test email', 'email-customizer-for-woocommerce' ),
			'template_changed'   => __( 'Template changed to:', 'email-customizer-for-woocommerce' ),
			'settings_updated'   => __( 'Settings updated:', 'email-customizer-for-woocommerce' ),
			'loading'            => __( 'Loading...', 'email-customizer-for-woocommerce' ),
			'error_occurred'     => __( 'An error occurred. Please try again.', 'email-customizer-for-woocommerce' ),
			'saving_text'        => __( 'Saving...', 'email-customizer-for-woocommerce' ),
			'saved_text'         => __( 'Saved', 'email-customizer-for-woocommerce' ),
			'error_text'         => __( 'An error occurred. Please try again.', 'email-customizer-for-woocommerce' ),
			'previewUrl'         => $this->get_preview_url(),
			'email_trigger'      => $this->email_trigger,
			'panel_id'           => $this->panel_id,
		);
		wp_enqueue_script( 'woocommerce-email-customizer-live-preview', EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_URL . '/admin/js' . $path . '/customizer-wbpreview' . $extension, array( 'jquery' ), EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION, true );
		wp_localize_script( 'woocommerce-email-customizer-live-preview', 'woocommerce_email_customizer_controls_local', $localized_vars );

		wp_enqueue_script( 'woocommerce-email-customizer-live-control', EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_URL . '/admin/js' . $path . '/customizer-control' . $extension, array( 'jquery' ), EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION, true );
		wp_localize_script( 'woocommerce-email-customizer-live-control', 'woocommerce_email_customizer_controls_local', $localized_vars );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js' . $path . '/email-customizer-for-woocommerce-admin' . $extension, array( 'jquery', 'customize-preview' ), $this->version, false );

		$localized_vars_reset = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'wc_email_customizer_email_load_templates' ),
			'action'  => 'wb_email_customizer_load_template_presets',
		);
		wp_localize_script( $this->plugin_name, 'wc_email_customizer_email_ajx', $localized_vars_reset );
	}

	/**
	 * Removes enqueued styles in the customiser.
	 */
	public function wb_email_customizer_remove_theme_styles(): void {
		global $wp_styles;
		$wp_styles->queue = array();
	}

	/**
	 * Hide all notices from the setting page.
	 *
	 * @return void
	 */
	public function wb_email_hide_all_admin_notices_from_setting_page(): void {
		$wbcom_pages_array  = array( 'wbcomplugins', 'wbcom-plugins-page', 'wbcom-support-page', 'wb-email-customizer-settings' );
		$wbcom_setting_page = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : '';

		if ( in_array( $wbcom_setting_page, $wbcom_pages_array, true ) ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}

	/**
	 * Actions performed to create a submenu page content.
	 *
	 * @since    1.0.0
	 * @access public
	 */
	public function wb_email_customizer_admin_options_page(): void {
		if ( ! $this->verify_user_permissions() ) {
			return;
		}
		$tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'wb-email-customizer-welcome';
		if ( ! current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'email-customizer-for-woocommerce' ) );
		}
		?>
		<div class="wrap">
			<div class="wbcom-bb-plugins-offer-wrapper">
				<div id="wb_admin_logo">
				</div>
			</div>
			<div class="wbcom-wrap">
				<div class="blpro-header">
					<div class="wbcom_admin_header-wrapper">
						<div id="wb_admin_plugin_name">
							<?php esc_html_e( 'Email Customizer For WooCommerce', 'email-customizer-for-woocommerce' ); ?>
							<?php /* translators: %s: */ ?>
							<span><?php printf(esc_html__( 'Version %s', 'email-customizer-for-woocommerce' ), EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION );//phpcs:ignore ?></span>
						</div>
						<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
					</div>
				</div>
				<div class="wbcom-admin-settings-page">
					<?php
					settings_errors();
					$this->wb_email_customizer_plugin_settings_tabs();
					settings_fields( $tab );
					do_settings_sections( $tab );
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Actions performed on loading plugin settings
	 *
	 * @since    1.0.9
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function wb_email_customizer_init_plugin_settings(): void {
		$this->plugin_settings_tabs['wb-email-customizer-welcome'] = esc_html__( 'Welcome', 'email-customizer-for-woocommerce' );
		register_setting(
			'wb_email_customizer_admin_welcome_options',
			'wb_email_customizer_admin_welcome_options',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		add_settings_section( 'wb-email-customizer-welcome', ' ', array( $this, 'wb_email_customizer_admin_welcome_content' ), 'wb-email-customizer-welcome' );

		$this->plugin_settings_tabs['wb-email-customizer-faq'] = esc_html__( 'FAQ', 'email-customizer-for-woocommerce' );
		register_setting(
			'wb_email_customizer_faq_options',
			'wb_email_customizer_faq_options',
			array( 'sanitize_callback' => 'sanitize_text_field' )
		);
		add_settings_section( 'wb-email-customizer-faq', ' ', array( $this, 'wb_email_customizer_faq_options_content' ), 'wb-email-customizer-faq' );

		add_action( 'admin_notices', array( $this, 'show_customizer_notices' ) );
	}

	/**
	 * Show admin notices on the email customizer settings page.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function show_customizer_notices(): void {
		$screen = get_current_screen();
		if ( ! $screen || false === strpos( $screen->id, 'email-customizer' ) ) {
			return;
		}

		// Show warning if WooCommerce emails are disabled.
		if ( get_option( 'woocommerce_email_enabled' ) !== 'yes' ) {
			printf(
				'<div class="notice notice-warning"><p>%s <a href="%s">%s</a></p></div>',
				esc_html__( 'WooCommerce emails are currently disabled.', 'email-customizer-for-woocommerce' ),
				esc_url( admin_url( 'admin.php?page=wc-settings&tab=email' ) ),
				esc_html__( 'Enable them here', 'email-customizer-for-woocommerce' )
			);
		}
	}

	/**
	 * Actions performed to create tabs on the sub menu page.
	 */
	public function wb_email_customizer_plugin_settings_tabs(): void {
		$current_tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'wb-email-customizer-welcome';
		// xprofile setup tab.
		echo '<div class="wbcom-tabs-section"><div class="nav-tab-wrapper"><div class="wb-responsive-menu"><span>' . esc_html( 'Menu' ) . '</span><input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn"><label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label></div><ul>';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab === $tab_key ? 'nav-tab-active' : '';
			echo '<li><a class="nav-tab ' . esc_attr( $active ) . '" id="' . esc_attr( $tab_key ) . '-tab" href="?page=wb-email-customizer-settings&tab=' . esc_attr( $tab_key ) . '">' . esc_attr( $tab_caption ) . '</a></li>';
		}
		echo '</div></ul></div>';
	}

	/**
	 * Wb_email_customizer_admin_welcome_content
	 *
	 * @return void
	 */
	public function wb_email_customizer_admin_welcome_content(): void {
		include plugin_dir_path( __DIR__ ) . 'admin/partials/wb-email-customizer-welcome-page.php';
	}

	/**
	 * Wb_email_customizer_faq_options_content
	 *
	 * @return void
	 */
	public function wb_email_customizer_faq_options_content(): void {
		include plugin_dir_path( __DIR__ ) . 'admin/partials/wb-email-customizer-faq.php';
	}


	/**
	 * Actions performed on loading admin_menu.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function wb_email_customizer_views_add_admin_settings(): void {
		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) && class_exists( 'WooCommerce' ) ) {
			add_menu_page( esc_html__( 'WB Plugins', 'email-customizer-for-woocommerce' ), esc_html__( 'WB Plugins', 'email-customizer-for-woocommerce' ), 'manage_options', 'wbcomplugins', array( $this, 'wb_email_customizer_admin_options_page' ), 'dashicons-lightbulb', 59 );
			add_submenu_page( 'wbcomplugins', esc_html__( 'Welcome', 'email-customizer-for-woocommerce' ), esc_html__( 'Welcome', 'email-customizer-for-woocommerce' ), 'manage_options', 'wbcomplugins' );

		}
		add_submenu_page( 'wbcomplugins', esc_html__( 'WC Email Customizer', 'email-customizer-for-woocommerce' ), esc_html__( 'WC Email Customizer', 'email-customizer-for-woocommerce' ), 'manage_options', 'wb-email-customizer-settings', array( $this, 'wb_email_customizer_admin_options_page' ) );
	}


	/**
	 * Get preview URL.
	 *
	 * @since  1.0.0
	 * @return string The preview URL.
	 */
	private function get_preview_url() {
		// Generate nonce for security.
		$nonce = wp_create_nonce( 'preview-mail' );
		// Generate the email preview URL (not the customizer URL).
		$preview_url = home_url( '/' );
		$preview_url = add_query_arg( $this->email_trigger, 'true', $preview_url );
		$preview_url = add_query_arg( '_wpnonce', $nonce, $preview_url );

		return $preview_url;
	}

	/**
	 * Add a submenu item to the WooCommerce menu.
	 *
	 * @since   1.0
	 */
	public function wb_email_customizer_admin_setting_submenu_pages(): void {
		$url = admin_url( 'customize.php' );
		// Add the preview URL to the customizer.
		$url = add_query_arg( 'url', rawurlencode( $this->get_preview_url() ), $url );
		// Add our identifier to know we're in email customizer mode.
		$url = add_query_arg( $this->email_trigger, 'true', $url );

		add_submenu_page(
			'woocommerce',
			__( 'WooCommerce Email Customizer', 'email-customizer-for-woocommerce' ),
			__( 'Email Customizer', 'email-customizer-for-woocommerce' ),
			'manage_woocommerce', // phpcs:ignore WordPress.WP.Capabilities.Unknown
			$this->plugin_name,
			function () use ( $url ) {
				wp_safe_redirect( $url );
				exit;
			}
		);
	}


	/**
	 * Add custom variables to the available query vars.
	 *
	 * @param  mixed $vars Email Vare.
	 */
	public function wb_email_customizer_add_query_vars( $vars ): array {

		$vars[] = $this->email_trigger;

		return $vars;
	}

	/**
	 * If the right query var is present load the email template.
	 *
	 * @param mixed $wp_query Custom Query.
	 * @throws Exception If template loading fails.
	 * @return mixed The query object.
	 */
	public function wb_email_customizer_load_email_template( $wp_query ) {
		// Only process if this is the main query and we're not in admin.
		if ( ! is_main_query() || is_admin() ) {
			return $wp_query;
		}

		try {
			if ( isset( $_GET[ $this->email_trigger ] ) && 'true' === sanitize_text_field( wp_unslash( $_GET[ $this->email_trigger ] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				// Use a more specific check to prevent multiple executions.
				if ( defined( 'WC_EMAIL_PREVIEW_LOADED' ) ) {
					return $wp_query;
				}
				define( 'WC_EMAIL_PREVIEW_LOADED', true );

				// Verify nonce for security.
				$wpnonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
				if ( ! wp_verify_nonce( $wpnonce, 'preview-mail' ) ) {
					wp_die( esc_html__( 'Security check failed.', 'email-customizer-for-woocommerce' ) );
				}

				$mailer = WC()->mailer();
				if ( ! $mailer ) {
					throw new Exception( 'WooCommerce mailer not available' );
				}

				ob_start();

				$template = $this->get_validated_param( 'woocommerce_email_template', 'default', 'template' );

				$template_file = $this->get_template_file_path( $template );

				if ( ! file_exists( $template_file ) ) {
					throw new Exception( 'Template file not found: ' . $template_file );
				}

				include $template_file;

				$message = ob_get_clean();

				if ( empty( $message ) ) {
					throw new Exception( 'Empty email template generated' );
				}

				$email_heading = $this->get_validated_param(
					'woocommerce_email_heading_text',
					__( 'Thanks for your order!', 'email-customizer-for-woocommerce' ),
					'text'
				);

				$email    = new WC_Email();
				$messages = $email->style_inline( $mailer->wrap_message( $email_heading, $message ) );

				// Set proper content type header.
				header( 'Content-Type: text/html; charset=utf-8' );

				echo $messages; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Nonce-verified admin preview; full HTML email document from WooCommerce internals cannot be run through wp_kses_post without stripping required <html>/<head>/<style> tags.
				exit;
			}
		} catch ( Exception $e ) {
			// Show user-friendly error in preview mode.
			if ( current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
				wp_die(
					sprintf(
						/* translators: %s: Error message. */
						esc_html__( 'Email template preview failed: %s', 'email-customizer-for-woocommerce' ),
						esc_html( $e->getMessage() )
					)
				);
			}

			// Return gracefully for non-admin users.
			return $wp_query;
		}

		return $wp_query;
	}


	/**
	 * Added Customizer Sections.
	 *
	 * @since   1.0
	 * @param string $wp_customize Get a Customizer Section.
	 */
	public function wb_email_customizer_add_sections( $wp_customize ): void {
		if ( ! current_user_can( 'customize' ) ) {
			return;
		}

		if ( ! is_user_logged_in() || ! current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			return;
		}
			$wp_customize->add_panel(
				$this->panel_id,
				array(
					'title'       => __( 'WooCommerce Email Customizer', 'email-customizer-for-woocommerce' ),
					'description' => __( 'Customize the appearance and content of your WooCommerce emails.', 'email-customizer-for-woocommerce' ),
					'capability'  => 'manage_woocommerce',
					'priority'    => 10,
				)
			);
			$wp_customize->add_section(
				'wc_email_templates',
				array(
					'title'       => __( 'Email Templates', 'email-customizer-for-woocommerce' ),
					'description' => __( 'Selecting a template will immediately override all current email styling settings. Your customizations will be replaced with the template\'s default values.', 'email-customizer-for-woocommerce' ),
					'capability'  => 'manage_woocommerce',
					'priority'    => 10,
					'panel'       => $this->panel_id,
				)
			);

			// Add all other sections with proper descriptions.
		$sections = array(
			'wc_email_text'                  => array(
				'title'       => __( 'Email Content', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Customize the text content of your emails.', 'email-customizer-for-woocommerce' ),
				'priority'    => 20,
			),
			'wc_email_header'                => array(
				'title'       => __( 'Email Header', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Customize the header section of your emails including logo and styling.', 'email-customizer-for-woocommerce' ),
				'priority'    => 30,
			),
			'wc_email_appearance_customizer' => array(
				'title'       => __( 'Container & Layout', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Adjust the overall layout, spacing, and container styling.', 'email-customizer-for-woocommerce' ),
				'priority'    => 40,
			),
			'wc_email_body'                  => array(
				'title'       => __( 'Email Body', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Customize the main content area styling and typography.', 'email-customizer-for-woocommerce' ),
				'priority'    => 50,
			),
			'wc_email_footer'                => array(
				'title'       => __( 'Email Footer', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Customize the footer section including text and styling.', 'email-customizer-for-woocommerce' ),
				'priority'    => 60,
			),
		);

		foreach ( $sections as $section_id => $section_args ) {
			$section_args['capability'] = 'manage_woocommerce';
			$section_args['panel']      = $this->panel_id;
			$wp_customize->add_section( $section_id, $section_args );
		}
	}

	/**
	 * Added Customizer Controls.
	 *
	 * @since   1.0
	 * @param string $wp_customize Get a Customizer Section.
	 */
	public function wb_email_customizer_add_controls( $wp_customize ): void {
		if ( ! current_user_can( 'customize' ) ) {
			return;
		}

		$wp_customize->add_control(
			'woocommerce_email_template_control',
			array(
				'type'        => 'radio',
				'label'       => __( 'Email Template', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Choose an email template design.', 'email-customizer-for-woocommerce' ),
				'section'     => 'wc_email_templates',
				'settings'    => 'woocommerce_email_template',
				'choices'     => array(
					'default'        => __( 'Default Template', 'email-customizer-for-woocommerce' ),
					'template-one'   => __( 'Modern Template', 'email-customizer-for-woocommerce' ),
					'template-two'   => __( 'Minimal Template', 'email-customizer-for-woocommerce' ),
					'template-three' => __( 'Bold Template', 'email-customizer-for-woocommerce' ),
				),
			)
		);

		/**
		 * Mail texts
		 */

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wc_email_text_heading_control',
				array(
					'type'        => 'text',
					'label'       => __( 'Email Heading', 'email-customizer-for-woocommerce' ),
					'description' => __( 'Enter the main heading text for emails (max 200 characters).', 'email-customizer-for-woocommerce' ),
					'section'     => 'wc_email_text',
					'settings'    => 'woocommerce_email_heading_text',
					'input_attrs' => array(
						'maxlength'   => 200,
						'placeholder' => __( 'Enter heading text...', 'email-customizer-for-woocommerce' ),
					),
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wc_email_text_subheading_control',
				array(
					'label'    => __( 'Subheading Text', 'email-customizer-for-woocommerce' ),
					'priority' => 30,
					'section'  => 'wc_email_text',
					'settings' => 'woocommerce_email_subheading_text',
					'type'     => 'text',
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wc_email_text_body_control',
				array(
					'label'    => __( 'Body Text', 'email-customizer-for-woocommerce' ),
					'priority' => 30,
					'section'  => 'wc_email_text',
					'settings' => 'woocommerce_email_body_text',
					'type'     => 'textarea',
				)
			)
		);

		/**
		 * Container padding.
		 */

		$wp_customize->add_control(
			'wc_email_header_padding_container_top_control',
			array(
				'type'        => 'range',
				'priority'    => 50,
				'section'     => 'wc_email_appearance_customizer',
				'label'       => __( 'Padding Top', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Padding Top', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_padding_container_top',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			'wc_email_header_padding_container_bottom_control',
			array(
				'type'        => 'range',
				'priority'    => 50,
				'section'     => 'wc_email_appearance_customizer',
				'label'       => __( 'Padding Bottom', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Padding Bottom', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_padding_container_bottom',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			'wc_email_header_padding_container_left_right_control',
			array(
				'type'        => 'range',
				'priority'    => 50,
				'section'     => 'wc_email_appearance_customizer',
				'label'       => __( 'Padding Left/Right', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Padding Left/Right', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_padding_container_left_right',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		/**
		 * Emails appearance customizer.
		 */

		$wp_customize->add_control(
			'wc_email_header_border_container_top_control',
			array(
				'type'        => 'range',
				'priority'    => 50,
				'section'     => 'wc_email_appearance_customizer',
				'label'       => __( 'Border Top', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Border Top', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_border_container_top',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			'wc_email_header_border_container_bottom_control',
			array(
				'type'        => 'range',
				'priority'    => 50,
				'section'     => 'wc_email_appearance_customizer',
				'label'       => __( 'Border Bottom', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Border Bottom', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_border_container_bottom',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			'wc_email_header_border_container_left_control',
			array(
				'type'        => 'range',
				'priority'    => 50,
				'section'     => 'wc_email_appearance_customizer',
				'label'       => __( 'Border Left', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Border Left', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_border_container_left',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			'wc_email_header_border_container_right_control',
			array(
				'type'        => 'range',
				'priority'    => 50,
				'section'     => 'wc_email_appearance_customizer',
				'label'       => __( 'Border Right', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Border Right', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_border_container_right',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_footer_border_control',
				array(
					'label'    => __( 'Border Color', 'email-customizer-for-woocommerce' ),
					'priority' => 20,
					'section'  => 'wc_email_appearance_customizer',
					'settings' => 'woocommerce_email_border_color',
				)
			)
		);

		/**
		 * Email Header.
		 */

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wc_email_header_image_placement_control',
				array(
					'label'    => __( 'Header Image Placement', 'email-customizer-for-woocommerce' ),
					'priority' => 2,
					'section'  => 'wc_email_header',
					'settings' => 'woocommerce_email_header_image_placement',
					'type'     => 'select',
					'choices'  => array(
						''        => __( 'Select body container placement', 'email-customizer-for-woocommerce' ),
						'inside'  => __( 'Inside the body container', 'email-customizer-for-woocommerce' ),
						'outside' => __( 'Outside the body container', 'email-customizer-for-woocommerce' ),
					),
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'wc_email_header_image_control',
				array(
					'label'    => __( 'Upload Header Image', 'email-customizer-for-woocommerce' ),
					'priority' => 10,
					'section'  => 'wc_email_header',
					'settings' => 'woocommerce_email_header_image',
					'context'  => 'email-customizer-for-woocommerce',
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_header_color_control',
				array(
					'label'       => __( 'Header Background Color', 'email-customizer-for-woocommerce' ),
					'description' => __( 'Choose the background color for email headers.', 'email-customizer-for-woocommerce' ),
					'section'     => 'wc_email_header',
					'settings'    => 'woocommerce_email_header_background_color',
				)
			)
		);

		$wp_customize->add_control(
			'wc_email_header_font_size_control',
			array(
				'type'        => 'range',
				'label'       => __( 'Header Font Size', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Set the font size for email headers (10-50px).', 'email-customizer-for-woocommerce' ),
				'section'     => 'wc_email_header',
				'settings'    => 'woocommerce_email_header_font_size',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wc_email_header_image_alignment_control',
				array(
					'label'    => __( 'Header Image Alignment', 'email-customizer-for-woocommerce' ),
					'priority' => 10,
					'section'  => 'wc_email_header',
					'settings' => 'woocommerce_email_header_image_alignment',
					'type'     => 'select',
					'choices'  => array(
						'left'   => __( 'Left', 'email-customizer-for-woocommerce' ),
						'center' => __( 'Center', 'email-customizer-for-woocommerce' ),
						'right'  => __( 'Right', 'email-customizer-for-woocommerce' ),
					),
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_header_text_color_control',
				array(
					'label'    => __( 'Header Text Color', 'email-customizer-for-woocommerce' ),
					'priority' => 40,
					'section'  => 'wc_email_header',
					'settings' => 'woocommerce_email_header_text_color',
				)
			)
		);

		/**
		 * Email Body.
		 */
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_bg_color_control',
				array(
					'label'    => __( 'Background Color', 'email-customizer-for-woocommerce' ),
					'priority' => 10,
					'section'  => 'wc_email_appearance_customizer',
					'settings' => 'woocommerce_email_background_color',
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_body_bg_color_control',
				array(
					'label'    => __( 'Content Background Color', 'email-customizer-for-woocommerce' ),
					'priority' => 30,
					'section'  => 'wc_email_body',
					'settings' => 'woocommerce_email_body_background_color',
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_link_color_control',
				array(
					'label'    => __( 'Link Color', 'email-customizer-for-woocommerce' ),
					'priority' => 50,
					'section'  => 'wc_email_body',
					'settings' => 'woocommerce_email_link_color',
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_body_text_color_control',
				array(
					'label'    => __( 'Text Color', 'email-customizer-for-woocommerce' ),
					'priority' => 70,
					'section'  => 'wc_email_body',
					'settings' => 'woocommerce_email_body_text_color',
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_body_border_color_control',
				array(
					'label'    => __( 'Border Color', 'email-customizer-for-woocommerce' ),
					'priority' => 70,
					'section'  => 'wc_email_body',
					'settings' => 'woocommerce_email_body_border_color',
				)
			)
		);

		$wp_customize->add_control(
			'wc_email_body_font_size_control',
			array(
				'type'        => 'range',
				'priority'    => 90,
				'section'     => 'wc_email_body',
				'label'       => __( 'Font Size', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Font Size', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_body_font_size',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			'wc_email_body_title_font_size_control',
			array(
				'type'        => 'range',
				'priority'    => 90,
				'section'     => 'wc_email_body',
				'label'       => __( 'Title Font Size', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Title Font Size', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_body_title_font_size',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wc_email_width_control',
				array(
					'label'    => __( 'Email Container Width', 'email-customizer-for-woocommerce' ),
					'priority' => 130,
					'section'  => 'wc_email_appearance_customizer',
					'settings' => 'woocommerce_email_width',
					'type'     => 'select',
					'choices'  => array(
						'500' => __( 'Narrow', 'email-customizer-for-woocommerce' ),
						'600' => __( 'Default', 'email-customizer-for-woocommerce' ),
						'700' => __( 'Wide', 'email-customizer-for-woocommerce' ),
					),
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wc_email_font_family_control',
				array(
					'label'    => __( 'Font Family', 'email-customizer-for-woocommerce' ),
					'priority' => 150,
					'section'  => 'wc_email_body',
					'settings' => 'woocommerce_email_font_family',
					'type'     => 'select',
					'choices'  => array(
						'sans-serif' => __( 'Sans Serif', 'email-customizer-for-woocommerce' ),
						'serif'      => __( 'Serif', 'email-customizer-for-woocommerce' ),
					),
				)
			)
		);

		$wp_customize->add_control(
			'wc_email_rounded_corners_control',
			array(
				'type'        => 'range',
				'priority'    => 170,
				'section'     => 'wc_email_appearance_customizer',
				'label'       => __( 'Rounded Corners', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Enable rounded corners', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_rounded_corners',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			'wc_email_box_shadow_spread_control',
			array(
				'type'        => 'range',
				'priority'    => 190,
				'section'     => 'wc_email_appearance_customizer',
				'label'       => __( 'Shadow Spread', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Amount of shadow behind the email container', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_box_shadow_spread',
				'input_attrs' => array(
					'min'  => -5,
					'max'  => 10,
					'step' => 1,
				),
			)
		);

		/**
		 * Email footer.
		 */
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wc_email_footer_text_control',
				array(
					'label'    => __( 'Footer Text', 'email-customizer-for-woocommerce' ),
					'priority' => 30,
					'section'  => 'wc_email_footer',
					'settings' => 'woocommerce_email_footer_text',
					'type'     => 'text',
				)
			)
		);

		$wp_customize->add_control(
			'wc_email_footer_font_size_control',
			array(
				'type'        => 'range',
				'priority'    => 40,
				'section'     => 'wc_email_footer',
				'label'       => __( 'Font Size', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Font Size', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_footer_font_size',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_footer_background_color_control',
				array(
					'label'    => __( 'Footer Background Color', 'email-customizer-for-woocommerce' ),
					'priority' => 50,
					'section'  => 'wc_email_footer',
					'settings' => 'woocommerce_email_footer_background_color',
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_footer_text_color_control',
				array(
					'label'    => __( 'Footer Text Color', 'email-customizer-for-woocommerce' ),
					'priority' => 50,
					'section'  => 'wc_email_footer',
					'settings' => 'woocommerce_email_footer_text_color',
				)
			)
		);

		$wp_customize->add_control(
			'wc_email_footer_top_padding_control',
			array(
				'type'        => 'range',
				'priority'    => 60,
				'section'     => 'wc_email_footer',
				'label'       => __( 'Footer Top Padding', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Footer Top Padding', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_footer_top_padding',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			'wc_email_footer_bottom_padding_control',
			array(
				'type'        => 'range',
				'priority'    => 70,
				'section'     => 'wc_email_footer',
				'label'       => __( 'Footer Bottom Padding', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Footer Bottom Padding', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_footer_bottom_padding',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			'wc_email_footer_left_right_padding_control',
			array(
				'type'        => 'range',
				'priority'    => 80,
				'section'     => 'wc_email_footer',
				'label'       => __( 'Footer Left/Right Padding', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Footer Left/Right Padding', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_footer_left_right_padding',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		/**
		 * Email footer Customizer.
		 */
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_footer_address_background_color_control',
				array(
					'label'    => __( 'Footer Address Background Color', 'email-customizer-for-woocommerce' ),
					'priority' => 10,
					'section'  => 'wc_email_footer',
					'settings' => 'woocommerce_email_footer_address_background_color',
				)
			)
		);

		$wp_customize->add_control(
			'wc_email_footer_address_border_control',
			array(
				'type'        => 'range',
				'priority'    => 20,
				'section'     => 'wc_email_footer',
				'label'       => __( 'Footer Address Border', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Footer Address Border', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_footer_address_border',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_footer_address_border_color_control',
				array(
					'label'    => __( 'Footer Address Border Color', 'email-customizer-for-woocommerce' ),
					'priority' => 21,
					'section'  => 'wc_email_footer',
					'settings' => 'woocommerce_email_footer_address_border_color',
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'wc_email_footer_address_border_style_control',
				array(
					'label'    => __( 'Footer Address Border Style', 'email-customizer-for-woocommerce' ),
					'priority' => 22,
					'section'  => 'wc_email_footer',
					'settings' => 'woocommerce_email_footer_address_border_style',
					'type'     => 'select',
					'choices'  => array(
						'solid'  => __( 'Solid', 'email-customizer-for-woocommerce' ),
						'dotted' => __( 'Dotted', 'email-customizer-for-woocommerce' ),
					),
				)
			)
		);
	}

	/**
	 * Added Customizer Settings.
	 *
	 * @param string $wp_customize Get a Customizer Section.
	 */
	public function wb_email_customizer_add_customizer_settings( $wp_customize ): void {
		if ( ! current_user_can( 'customize' ) ) {
			return;
		}

		// Get the selected email template.
		$selected_template = ( isset( $_GET['woocommerce_email_template'] ) ) ? sanitize_text_field( wp_unslash( $_GET['woocommerce_email_template'] ) ) : get_option( 'woocommerce_email_template', 'default' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		// Get site title for dynamic text.
		$site_title      = get_bloginfo( 'name' );
		$formatted_title = ucwords( strtolower( str_replace( '-', ' ', $site_title ) ) );

		// Get template options with defaults and overrides.
		$template_options = $this->get_woocommerce_email_template_options( $selected_template );
		// Define template-specific text content.
		$template_texts = $this->get_template_specific_texts( $selected_template, $site_title, $formatted_title );
		// Merge template options with dynamic texts.
		$final_options = array_merge( $template_options, $template_texts );

		// Enhanced settings configuration with validation and sanitization.
		$settings_config = array(
			'woocommerce_email_heading_text'              => array(
				'default'           => $final_options['woocommerce_email_heading_text'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'woocommerce_email_subheading_text'           => array(
				'default'           => $final_options['woocommerce_email_subheading_text'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'woocommerce_email_body_text'                 => array(
				'default'           => $final_options['woocommerce_email_body_text'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_textarea_field',
			),
			'woocommerce_email_template'                  => array(
				'default'           => $final_options['woocommerce_email_template'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			),
			'woocommerce_email_header_image_placement'    => array(
				'default'           => $final_options['woocommerce_email_header_image_placement'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			),
			'woocommerce_email_header_image_alignment'    => array(
				'default'           => $final_options['woocommerce_email_header_image_alignment'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			),
			'woocommerce_email_padding_container_top'     => array(
				'default'           => $final_options['woocommerce_email_padding_container_top'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_padding_container_bottom'  => array(
				'default'           => $final_options['woocommerce_email_padding_container_bottom'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_padding_container_left_right' => array(
				'default'           => $final_options['woocommerce_email_padding_container_left_right'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_border_container_top'      => array(
				'default'           => $final_options['woocommerce_email_border_container_top'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_border_container_bottom'   => array(
				'default'           => $final_options['woocommerce_email_border_container_bottom'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_border_container_left'     => array(
				'default'           => $final_options['woocommerce_email_border_container_left'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_border_container_right'    => array(
				'default'           => $final_options['woocommerce_email_border_container_right'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_background_color'          => array(
				'default'           => $final_options['woocommerce_email_background_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_body_background_color'     => array(
				'default'           => $final_options['woocommerce_email_body_background_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_header_background_color'   => array(
				'default'           => $final_options['woocommerce_email_header_background_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_header_text_color'         => array(
				'default'           => $final_options['woocommerce_email_header_text_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_header_font_size'          => array(
				'default'           => $final_options['woocommerce_email_header_font_size'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_body_text_color'           => array(
				'default'           => $final_options['woocommerce_email_body_text_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_body_border_color'         => array(
				'default'           => $final_options['woocommerce_email_body_border_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_body_font_size'            => array(
				'default'           => $final_options['woocommerce_email_body_font_size'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_body_title_font_size'      => array(
				'default'           => $final_options['woocommerce_email_body_title_font_size'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_rounded_corners'           => array(
				'default'           => $final_options['woocommerce_email_rounded_corners'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_box_shadow_spread'         => array(
				'default'           => $final_options['woocommerce_email_box_shadow_spread'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'intval',
			),
			'woocommerce_email_font_family'               => array(
				'default'           => $final_options['woocommerce_email_font_family'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			),
			'woocommerce_email_link_color'                => array(
				'default'           => $final_options['woocommerce_email_link_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_header_image'              => array(
				'default'           => $final_options['woocommerce_email_header_image'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'esc_url_raw',
			),
			'woocommerce_email_width'                     => array(
				'default'           => $final_options['woocommerce_email_width'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			),
			'woocommerce_email_footer_text'               => array(
				'default'           => $final_options['woocommerce_email_footer_text'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'woocommerce_email_footer_font_size'          => array(
				'default'           => $final_options['woocommerce_email_footer_font_size'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_footer_text_color'         => array(
				'default'           => $final_options['woocommerce_email_footer_text_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_footer_top_padding'        => array(
				'default'           => $final_options['woocommerce_email_footer_top_padding'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_footer_bottom_padding'     => array(
				'default'           => $final_options['woocommerce_email_footer_bottom_padding'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_footer_left_right_padding' => array(
				'default'           => $final_options['woocommerce_email_footer_left_right_padding'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_footer_background_color'   => array(
				'default'           => $final_options['woocommerce_email_footer_background_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_border_color'              => array(
				'default'           => $final_options['woocommerce_email_border_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_footer_address_border_color' => array(
				'default'           => $final_options['woocommerce_email_footer_address_border_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_footer_address_border'     => array(
				'default'           => $final_options['woocommerce_email_footer_address_border'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			),
			'woocommerce_email_footer_address_background_color' => array(
				'default'           => $final_options['woocommerce_email_footer_address_background_color'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			),
			'woocommerce_email_footer_address_border_style' => array(
				'default'           => $final_options['woocommerce_email_footer_address_border_style'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_key',
			),
		);

		$settings_config = apply_filters( 'wb_email_customizer_settings_config', $settings_config, $wp_customize );

		// Loop through and add all settings with enhanced configuration.
		foreach ( $settings_config as $setting_id => $config ) {
			$setting_args = array(
				'type'      => 'option',
				'default'   => $config['default'],
				'transport' => $config['transport'],
			);

			// Add sanitization callback if provided.
			if ( isset( $config['sanitize_callback'] ) ) {
				$setting_args['sanitize_callback'] = $config['sanitize_callback'];
			}

			$wp_customize->add_setting( $setting_id, $setting_args );
		}
	}

	/**
	 * Get the file path for the email template based on the selected template.
	 *
	 * @param string $template The selected email template.
	 * @return string The file path to the email template.
	 */
	public function get_template_file_path( $template ): string {
		$template_file = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/admin/partials/';

		switch ( $template ) {
			case 'template-one':
				$template_file .= 'email-customizer-for-woocommerce-admin-display-template-one.php';
				break;
			case 'template-two':
				$template_file .= 'email-customizer-for-woocommerce-admin-display-template-two.php';
				break;
			case 'template-three':
				$template_file .= 'email-customizer-for-woocommerce-admin-display-template-three.php';
				break;
			default:
				$template_file .= 'email-customizer-for-woocommerce-admin-display.php';
				break;
		}

			return $template_file;
	}

	/**
	 * Overrides WooCommerce email template path with a custom one.
	 *
	 * @param string $template      The path to the template found.
	 * @param string $template_name The name of the template file.
	 * @param string $template_path The path to the template directory.
	 * @return string Modified template path if custom one exists.
	 */
	public function wb_email_customizer_custom_universal_email_template_override( $template, $template_name, $template_path ): string {
		$order_email_templates = array(
			'emails/admin-new-order.php',
			'emails/customer-processing-order.php',
			'emails/customer-completed-order.php',
			'emails/customer-on-hold-order.php',
			'emails/customer-refunded-order.php',
			'emails/customer-invoice.php',
			'emails/customer-note.php',
			'emails/customer-failed-order.php',
		);
		$order_email_templates = apply_filters( 'wb_email_customizer_templates_for_preview', $order_email_templates );

		$selected_template = ( isset( $_GET['woocommerce_email_template'] ) ) ? sanitize_text_field( wp_unslash( $_GET['woocommerce_email_template'] ) ) : get_option( 'woocommerce_email_template', 'default' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( 'template-one' === $selected_template && in_array( $template_name, $order_email_templates, true ) ) {
			$template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/admin/partials/email-customizer-for-woocommerce-admin-display-template-one.php';

		} elseif ( 'template-two' === $selected_template && in_array( $template_name, $order_email_templates, true ) ) {
			$template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/admin/partials/email-customizer-for-woocommerce-admin-display-template-two.php';

		} elseif ( 'template-three' === $selected_template && in_array( $template_name, $order_email_templates, true ) ) {
			$template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/admin/partials/email-customizer-for-woocommerce-admin-display-template-three.php';

		} elseif ( 'default' === $selected_template && in_array( $template_name, $order_email_templates, true ) ) {
			$template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/admin/partials/email-customizer-for-woocommerce-admin-display.php';
		}

		if ( 'emails/email-header.php' === $template_name ) {
			$template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/templates/emails/email-header.php';
		}

		return $template;
	}

	/**
	 * Get and validate a parameter from $_GET or options.
	 *
	 * @param string $key             The parameter key.
	 * @param mixed  $default         The default value.
	 * @param string $validation_type The type of validation to apply.
	 * @return string The validated parameter value.
	 */
	private function get_validated_param( $key, $default = '', $validation_type = 'text' ): string {
		$default_array = $this->get_woocommerce_email_template_options();
		if ( empty( $default ) ) {
			$default = isset( $default_array[ $key ] ) ? $default_array[ $key ] : $default;
		}
		if ( ! isset( $_GET[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return get_option( $key, $default );
		}

		$value = sanitize_text_field( wp_unslash( $_GET[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		switch ( $validation_type ) {
			case 'color':
				$sanitized_color = sanitize_hex_color( $value );
				return $sanitized_color ? $sanitized_color : $default;
			case 'numeric':
				return absint( $value );
			case 'url':
				return esc_url_raw( $value );
			case 'template':
				$allowed = array( 'default', 'template-one', 'template-two', 'template-three' );
				return in_array( $value, $allowed, true ) ? $value : $default;
			case 'alignment':
				$allowed = array( 'left', 'center', 'right' );
				return in_array( $value, $allowed, true ) ? $value : $default;
			case 'border_style':
				$allowed = array( 'solid', 'dashed', 'dotted', 'double' );
				return in_array( $value, $allowed, true ) ? $value : $default;
			case 'font_family':
				$allowed = array( 'sans-serif', 'serif' );
				return in_array( $value, $allowed, true ) ? $value : $default;
			default:
				return sanitize_text_field( $value );
		}
	}

	/**
	 * Modify the styles in the WooCommerce emails with the styles in the customizer.
	 *
	 * @param mixed $styles CSS a blob of CSS.
	 */
	public function wb_email_customizer_add_styles( $styles ): string {
		// Verify nonce first.
		if ( is_admin() && isset( $_GET['customize_changeset_uuid'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'preview-mail' ) ) {
				return $styles;
			}
		}

		// If it's a preview email with nonce parameter.
		if ( isset( $_GET['nonce'] ) ) {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), '_wc_email_customizer_send_email_nonce' ) ) {
				return $styles;
			}
		}

		// Get all parameters with proper sanitization.
		$selected_template = $this->get_validated_param( 'woocommerce_email_template', 'default', 'template' );

		// Color parameters.
		$woocommerce_email_background_color                = $this->get_validated_param( 'woocommerce_email_background_color', '#f5f5f5', 'color' );
		$woocommerce_email_body_background_color           = $this->get_validated_param( 'woocommerce_email_body_background_color', '#fdfdfd', 'color' );
		$woocommerce_email_header_background_color         = $this->get_validated_param( 'woocommerce_email_header_background_color', '#557da1', 'color' );
		$woocommerce_email_header_text_color               = $this->get_validated_param( 'woocommerce_email_header_text_color', '#ffffff', 'color' );
		$body_color                                        = $this->get_validated_param( 'woocommerce_email_body_text_color', '#505050', 'color' );
		$woocommerce_email_body_border_color               = $this->get_validated_param( 'woocommerce_email_body_border_color', '#505050', 'color' );
		$woocommerce_email_link_color                      = $this->get_validated_param( 'woocommerce_email_link_color', '#1e73be', 'color' );
		$woocommerce_email_footer_text_color               = $this->get_validated_param( 'woocommerce_email_footer_text_color', '#202020', 'color' );
		$woocommerce_email_border_color                    = $this->get_validated_param( 'woocommerce_email_border_color', '#202020', 'color' );
		$woocommerce_email_footer_address_background_color = $this->get_validated_param( 'woocommerce_email_footer_address_background_color', '#202020', 'color' );
		$woocommerce_email_footer_address_border_color     = $this->get_validated_param( 'woocommerce_email_footer_address_border_color', '#202020', 'color' );
		$woocommerce_email_footer_background_color         = $this->get_validated_param( 'woocommerce_email_footer_background_color', '#202020', 'color' );

		// Numeric parameters.
		$woocommerce_email_header_font_size     = $this->get_validated_param( 'woocommerce_email_header_font_size', 30, 'numeric' );
		$woocommerce_email_rounded_corners      = $this->get_validated_param( 'woocommerce_email_rounded_corners', 6, 'numeric' );
		$woocommerce_email_box_shadow_spread    = $this->get_validated_param( 'woocommerce_email_box_shadow_spread', 1, 'numeric' );
		$woocommerce_email_body_font_size       = $this->get_validated_param( 'woocommerce_email_body_font_size', 12, 'numeric' );
		$woocommerce_email_body_title_font_size = $this->get_validated_param( 'woocommerce_email_body_title_font_size', 18, 'numeric' );
		$woocommerce_email_width                = $this->get_validated_param( 'woocommerce_email_width', 600, 'numeric' );
		$woocommerce_email_footer_font_size     = $this->get_validated_param( 'woocommerce_email_footer_font_size', 12, 'numeric' );

		// Border width parameters.
		$woocommerce_email_border_container_top    = $this->get_validated_param( 'woocommerce_email_border_container_top', ( in_array( $selected_template, array( 'template-one', 'template-two', 'template-three' ), true ) ? 0 : 1 ), 'numeric' );
		$woocommerce_email_border_container_bottom = $this->get_validated_param( 'woocommerce_email_border_container_bottom', ( in_array( $selected_template, array( 'template-one', 'template-two', 'template-three' ), true ) ? 0 : 1 ), 'numeric' );
		$woocommerce_email_border_container_left   = $this->get_validated_param( 'woocommerce_email_border_container_left', ( in_array( $selected_template, array( 'template-one', 'template-two', 'template-three' ), true ) ? 0 : 1 ), 'numeric' );
		$woocommerce_email_border_container_right  = $this->get_validated_param( 'woocommerce_email_border_container_right', ( in_array( $selected_template, array( 'template-one', 'template-two', 'template-three' ), true ) ? 0 : 1 ), 'numeric' );

		// Padding parameters.
		$woocommerce_email_padding_container_top        = $this->get_validated_param( 'woocommerce_email_padding_container_top', 10, 'numeric' );
		$woocommerce_email_padding_container_bottom     = $this->get_validated_param( 'woocommerce_email_padding_container_bottom', 10, 'numeric' );
		$woocommerce_email_padding_container_left_right = $this->get_validated_param( 'woocommerce_email_padding_container_left_right', 10, 'numeric' );

		// Footer parameters.
		$woocommerce_email_footer_address_border     = $this->get_validated_param( 'woocommerce_email_footer_address_border', 2, 'numeric' );
		$woocommerce_email_footer_top_padding        = $this->get_validated_param( 'woocommerce_email_footer_top_padding', 10, 'numeric' );
		$woocommerce_email_footer_bottom_padding     = $this->get_validated_param( 'woocommerce_email_footer_bottom_padding', 10, 'numeric' );
		$woocommerce_email_footer_left_right_padding = $this->get_validated_param( 'woocommerce_email_footer_left_right_padding', 10, 'numeric' );

		// Text/key parameters.
		$woocommerce_email_footer_address_border_style = $this->get_validated_param( 'woocommerce_email_footer_address_border_style', 'solid', 'border_style' );
		$woocommerce_email_header_image_alignment      = $this->get_validated_param( 'woocommerce_email_header_image_alignment', 'center', 'alignment' );
		$font_family                                   = $this->get_validated_param( 'woocommerce_email_font_family', 'sans-serif', 'font_family' );

		// Build CSS with sanitized values.
		$bg_color          = sprintf( 'body, body>#outer_wrapper, body > div, body > #wrapper > table > tbody > tr > td { background-color: %s !important; }%s', esc_attr( $woocommerce_email_background_color ), PHP_EOL );
		$body_bg_color     = sprintf( '#template_container { background-color: %s !important; } #template_body, #template_body td, #body_content { background: transparent none !important; }%s', esc_attr( $woocommerce_email_body_background_color ), PHP_EOL );
		$header_bg_color   = sprintf( '#template_header { background-color: %s !important; } #header_wrapper, #header_wrapper h1 { background: transparent none !important; }%s', esc_attr( $woocommerce_email_header_background_color ), PHP_EOL );
		$header_text_color = sprintf( '#template_header h1 { color: %s !important; text-shadow: 0 1px 0 %s !important; }%s', esc_attr( $woocommerce_email_header_text_color ), esc_attr( $woocommerce_email_header_text_color ), PHP_EOL );

		if ( 'template-one' === $selected_template || 'template-two' === $selected_template ) {
			$header_font_size = sprintf( '#template_header h1 { font-size: %dpx !important; text-align: center !important; }%s', $woocommerce_email_header_font_size, PHP_EOL );
		} else {
			$header_font_size = sprintf( '#template_header h1 { font-size: %dpx !important; }%s', $woocommerce_email_header_font_size, PHP_EOL );
		}

		$header_image              = sprintf( '#template_header_image { margin-left: auto !important; margin-right: auto !important; }%s', PHP_EOL );
		$template_container_border = sprintf( 'table, table th, table td { border: none !important; border-style: none !important; border-width: 0 !important; }%s', PHP_EOL );
		$template_rounded_corners  = sprintf( '#template_container { border-radius: %dpx !important; } #template_header { border-radius: %dpx %dpx 0 0 !important; } #template_footer { border-radius: 0 0 %dpx %dpx !important; }%s', $woocommerce_email_rounded_corners, $woocommerce_email_rounded_corners, $woocommerce_email_rounded_corners, $woocommerce_email_rounded_corners, $woocommerce_email_rounded_corners, PHP_EOL );

		$template_shadow = sprintf( '#template_container { box-shadow: 0 0 6px %dpx rgba(0,0,0,0.2) !important; }%s', $woocommerce_email_box_shadow_spread, PHP_EOL );

		$body_items_table = sprintf( '#body_content_inner table { border-collapse: collapse !important; width: 100%% !important; }%s', PHP_EOL );
		$body_text_color  = sprintf( '#template_body div, #template_body div p, #template_body h2, #template_body h3, #template_body table td, #template_body table th, #template_body table tr, #template_body table, #template_body table h3, #template_body table .body-content-title a { color: %s !important; }%s', esc_attr( $body_color ), PHP_EOL );

		// Handle border styles based on template.
		if ( 'template-three' === $selected_template ) {
			$body_border_color = sprintf( '#body_content_inner table td, #body_content_inner table th { border-color: %s !important; border-width: 2px !important; border-style: solid !important; text-align: left !important; }%s', esc_attr( $woocommerce_email_body_border_color ), PHP_EOL );
			$addresses         = sprintf( '#body_content_inner table .addresses, #body_content_inner table.addresses td { border: 0 !important; line-height: 1.5 !important; padding: 0 !important; } %s', PHP_EOL );
		} elseif ( 'template-one' === $selected_template ) {
			$body_border_color = sprintf( '#body_content_inner table td, #body_content_inner table th { border-color: %s !important; border-width: 1px !important; border-style: solid !important; text-align: left !important; border-left: 0 !important; border-right: 0 !important; }%s', esc_attr( $woocommerce_email_body_border_color ), PHP_EOL );
			$addresses         = sprintf(
				'.addresses td { line-height: 1.5 !important; padding-right: 12px !important; }
				.addresses td + td { padding-left: 12px !important; }
				#body_content_inner table.addresses th, #body_content_inner table.addresses td {
					border-color: %s !important;
					border-width: %spx !important;
					border-style: %s !important;
					text-align: center !important;
				}%s',
				esc_attr( $woocommerce_email_footer_address_border_color ),
				esc_attr( $woocommerce_email_footer_address_border ),
				esc_attr( $woocommerce_email_footer_address_border_style ),
				PHP_EOL
			);
		} elseif ( 'template-two' === $selected_template ) {
			$body_border_color = sprintf( '#body_content_inner table td, #body_content_inner table th { border-color: %s !important; border-width: 1px !important; border-style: solid !important; text-align: left !important; }%s', esc_attr( $woocommerce_email_body_border_color ), PHP_EOL );
			$addresses         = sprintf(
				'.addresses td { line-height: 1.5 !important; padding-right: 12px !important; }
				.addresses td + td { padding-left: 12px !important; }
				#body_content_inner table.addresses th, #body_content_inner table.addresses td { text-align: center !important; }
				#body_content_inner table.addresses td {
					border-color: %s !important;
					border-width: %spx !important;
					border-style: %s !important;
					text-align: center !important;
				}%s',
				esc_attr( $woocommerce_email_footer_address_border_color ),
				esc_attr( $woocommerce_email_footer_address_border ),
				esc_attr( $woocommerce_email_footer_address_border_style ),
				PHP_EOL
			);
		} else {
			$body_border_color = sprintf( '#body_content_inner table td, #body_content_inner table th { border-color: %s !important; border-width: 1px !important; border-style: solid !important; text-align: left !important; }%s', esc_attr( $woocommerce_email_body_border_color ), PHP_EOL );
			$addresses         = sprintf( '.addresses td { border: none !important; line-height: 1.5 !important; padding-right: 12px !important; } .addresses td + td { padding-left: 12px !important; padding-right: 0 !important; }%s', PHP_EOL );
		}

		$body_font_size       = sprintf( '#template_body div, #template_body div p, #template_body table td, #template_body table th, #template_body h2, #template_body h3 { font-size: %dpx !important; }%s', $woocommerce_email_body_font_size, PHP_EOL );
		$body_title_font_size = sprintf( '#template_body h2, #template_body h3 { font-size: %dpx !important; }%s', $woocommerce_email_body_title_font_size, PHP_EOL );
		$body_link_color      = sprintf( '#template_body div a, #template_body table td a { color: %s !important; }%s', esc_attr( $woocommerce_email_link_color ), PHP_EOL );
		$width                = sprintf( '#template_container, #template_header, #template_body, #template_footer { width: %dpx !important; }%s', $woocommerce_email_width, PHP_EOL );
		$footer_font_size     = sprintf( '#template_footer p { font-size: %dpx !important; }%s', $woocommerce_email_footer_font_size, PHP_EOL );
		$footer_text_color    = sprintf( '#template_footer p, #template_footer p a { color: %s !important; line-height: 1.5 !important; }%s', esc_attr( $woocommerce_email_footer_text_color ), PHP_EOL );

		// Handle font family.
		if ( 'sans-serif' === $font_family ) {
			$font_family_css = 'Helvetica, Arial, sans-serif';
		} else {
			$font_family_css = 'Georgia, serif';
		}
		$font_family = sprintf( '#template_container, #template_header h1, #template_body table div, #template_footer p, #template_footer th, #template_footer td, #template_body table table, #body_content_inner table, #template_footer table { font-family: %s !important; }%s', esc_attr( $font_family_css ), PHP_EOL );

		// Border and padding styles.
		$apprance_border_color = sprintf( '#template_container { border-color: %s !important; border-style: solid !important; border-top-width: %dpx !important; border-bottom-width: %dpx !important; border-left-width: %dpx !important; border-right-width: %dpx !important; }%s', esc_attr( $woocommerce_email_border_color ), $woocommerce_email_border_container_top, $woocommerce_email_border_container_bottom, $woocommerce_email_border_container_left, $woocommerce_email_border_container_right, PHP_EOL );
		$padding_conatiner     = sprintf( '#header_wrapper { padding-top: %dpx !important; padding-bottom: %dpx !important; padding-left: %dpx !important; padding-right: %dpx !important; }%s', $woocommerce_email_padding_container_top, $woocommerce_email_padding_container_bottom, $woocommerce_email_padding_container_left_right, $woocommerce_email_padding_container_left_right, PHP_EOL );
		$footer_address        = sprintf( 'table.addresses { background: %s !important; }%s', esc_attr( $woocommerce_email_footer_address_background_color ), PHP_EOL );

		// Body title styles.
		if ( 'template-three' === $selected_template ) {
			$body_title_style = sprintf( '.has-border { text-decoration: none !important; margin-bottom: 20px !important; border: 0 !important; border-bottom: %dpx %s %s !important; }%s', $woocommerce_email_footer_address_border, esc_attr( $woocommerce_email_footer_address_border_style ), esc_attr( $woocommerce_email_footer_address_border_color ), PHP_EOL );
		} elseif ( 'template-one' === $selected_template || 'template-two' === $selected_template ) {
			$body_title_style = sprintf( '.body-content-title { text-align: center !important; margin: 40px 0 30px !important; }%s', PHP_EOL );
		} else {
			$body_title_style = '';
		}

		$footer_bottom   = sprintf( '#template_footer { background: %s !important; padding-top: %dpx !important; padding-bottom: %dpx !important; padding-left: %dpx !important; padding-right: %dpx !important; }%s', esc_attr( $woocommerce_email_footer_background_color ), $woocommerce_email_footer_top_padding, $woocommerce_email_footer_bottom_padding, $woocommerce_email_footer_left_right_padding, $woocommerce_email_footer_left_right_padding, PHP_EOL );
		$image_alignment = sprintf( '#template_header_image, #template_header_image>p, #template_header_image img { text-align: %s !important; }%s', esc_attr( $woocommerce_email_header_image_alignment ), PHP_EOL );

		// Append all styles.
		$styles .= PHP_EOL;
		$styles .= $template_container_border;
		$styles .= $bg_color;
		$styles .= $body_bg_color;
		$styles .= $header_bg_color;
		$styles .= $header_image;
		$styles .= $header_font_size;
		$styles .= $header_text_color;
		$styles .= $body_items_table;
		$styles .= $body_text_color;
		$styles .= $body_border_color;
		$styles .= $body_font_size;
		$styles .= $body_title_font_size;
		$styles .= $body_link_color;
		$styles .= $width;
		$styles .= $footer_text_color;
		$styles .= $footer_font_size;
		$styles .= $font_family;
		$styles .= $template_rounded_corners;
		$styles .= $template_shadow;
		$styles .= $addresses;
		$styles .= $apprance_border_color;
		$styles .= $padding_conatiner;
		$styles .= $footer_address;
		$styles .= $footer_bottom;
		$styles .= $image_alignment;
		$styles .= $body_title_style;

		return $styles;
	}

	/**
	 * Replace the footer text with the footer text from the customizer
	 *
	 * @param string $text Email Footer Text.
	 */
	public function wb_email_customizer_email_footer_text( $text ): string {
		return ( isset( $_GET['woocommerce_email_footer_text'] ) ) ? sanitize_text_field( wp_unslash( $_GET['woocommerce_email_footer_text'] ) ) : get_option( 'woocommerce_email_footer_text', __( 'Email Customizer For WooCommerce - Powered by WooCommerce and WordPress', 'email-customizer-for-woocommerce' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * AJAX handler for loading template presets.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function wb_email_customizer_load_template_presets(): void {
		// Flexible nonce verification.
		$nonce_verified = false;

		// Try different nonce fields that might be sent.
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
			if ( wp_verify_nonce( $nonce, 'wc_email_customizer_email_load_templates' ) ) {
				$nonce_verified = true;
			}
		}

		if ( ! $nonce_verified && isset( $_POST['_wpnonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) );
			if ( wp_verify_nonce( $nonce, 'wc_email_customizer_email_load_templates' ) ) {
				$nonce_verified = true;
			}
		}

		if ( ! $nonce_verified && isset( $_POST['_ajax_nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
			if ( wp_verify_nonce( $nonce, 'wc_email_customizer_email_load_templates' ) ) {
				$nonce_verified = true;
			}
		}

		if ( ! $nonce_verified ) {
			wp_send_json_error( 'Security check failed. Please refresh the page and try again.' );
			return;
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			wp_send_json_error( 'Insufficient permissions' );
			return;
		}

		$template = isset( $_POST['template'] ) ? sanitize_text_field( wp_unslash( $_POST['template'] ) ) : 'default';

		try {
			// Get template-specific defaults.
			$template_defaults = $this->get_template_specific_overrides( $template );

			// Update all template options.
			$success = $this->wb_email_customizer_update_all_defaults( $template_defaults );

			if ( $success ) {
				wp_send_json_success( __( 'Template preset loaded successfully!', 'email-customizer-for-woocommerce' ) );
			} else {
				wp_send_json_error( __( 'Failed to load template preset.', 'email-customizer-for-woocommerce' ) );
			}
		} catch ( Exception $e ) {
			wp_send_json_error( __( 'Error loading template preset.', 'email-customizer-for-woocommerce' ) );
		}
	}

	/**
	 * AJAX handler for sending a test email.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function wb_email_customizer_send_email(): void {
		// Verify nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), '_wc_email_customizer_send_email_nonce' ) ) {
			wp_send_json_error( __( 'Security check failed. Please refresh the page and try again.', 'email-customizer-for-woocommerce' ) );
			return;
		}

		// Check user capabilities.
		if ( ! current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			wp_send_json_error( __( 'Insufficient permissions.', 'email-customizer-for-woocommerce' ) );
			return;
		}

		$recipient = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		if ( empty( $recipient ) || ! is_email( $recipient ) ) {
			$recipient = get_option( 'admin_email' );
		}

		try {
			$mailer = WC()->mailer();
			if ( ! $mailer ) {
				wp_send_json_error( __( 'WooCommerce mailer not available.', 'email-customizer-for-woocommerce' ) );
				return;
			}

			// Build the query string from POST data for the template to use.
			$query_args = array();
			if ( isset( $_POST['settings'] ) && is_array( $_POST['settings'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotUnslashed
				$unslashed_settings = wp_unslash( $_POST['settings'] );
				// phpcs:enable
				foreach ( $unslashed_settings as $key => $value ) {
					$query_args[ sanitize_key( $key ) ] = sanitize_text_field( $value );
				}
			}

			$query_args['nonce'] = wp_create_nonce( '_wc_email_customizer_send_email_nonce' );

			// Temporarily set GET params for template rendering.
			$original_get = $_GET;
			try {
				$_GET = array_merge( $_GET, $query_args );

				$template = $this->get_validated_param( 'woocommerce_email_template', 'default', 'template' );

				ob_start();
				$template_file = $this->get_template_file_path( $template );
				if ( file_exists( $template_file ) ) {
					include $template_file;
				}
				$message = ob_get_clean();
			} finally {
				// Always restore original GET, even on exceptions.
				$_GET = $original_get;
			}

			if ( empty( $message ) ) {
				wp_send_json_error( __( 'Failed to generate email content.', 'email-customizer-for-woocommerce' ) );
				return;
			}

			$email_heading = $this->get_validated_param(
				'woocommerce_email_heading_text',
				__( 'Thanks for your order!', 'email-customizer-for-woocommerce' ),
				'text'
			);

			$email      = new WC_Email();
			$email_body = $email->style_inline( $mailer->wrap_message( $email_heading, $message ) );

			$subject = sprintf(
				/* translators: %s: Site name. */
				__( '[%s] Test Email from Email Customizer', 'email-customizer-for-woocommerce' ),
				get_bloginfo( 'name' )
			);

			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			$sent    = wp_mail( $recipient, $subject, $email_body, $headers );

			if ( $sent ) {
				wp_send_json_success(
					sprintf(
						/* translators: %s: Recipient email address. */
						__( 'Test email sent to %s.', 'email-customizer-for-woocommerce' ),
						$recipient
					)
				);
			} else {
				wp_send_json_error( __( 'Failed to send test email. Please check your email configuration.', 'email-customizer-for-woocommerce' ) );
			}
		} catch ( Exception $e ) {
			wp_send_json_error( __( 'Error sending test email.', 'email-customizer-for-woocommerce' ) );
		}
	}

	/**
	 * Update all default email customizer options at once.
	 *
	 * @since  1.0.0
	 * @param  array $custom_defaults Custom default values to merge.
	 * @return bool Whether all options were updated successfully.
	 */
	public function wb_email_customizer_update_all_defaults( $custom_defaults = array() ): bool {
		// Define all default values for email customizer options.
		$default_options = $this->get_woocommerce_email_template_options();

		// Merge custom defaults with predefined defaults.
		$options_to_update = wp_parse_args( $custom_defaults, $default_options );

		$success = true;

		// Fetch current values once to avoid a get_option() call per iteration.
		$current_values = array_map( 'get_option', array_keys( $options_to_update ) );
		$current_values = array_combine( array_keys( $options_to_update ), $current_values );

		// Update each option.
		foreach ( $options_to_update as $option_name => $option_value ) {
			$result = update_option( $option_name, $option_value );
			if ( false === $result && $current_values[ $option_name ] !== $option_value ) {
				$success = false;
			}
		}

		// Trigger action hook for other plugins/themes to react to defaults update.
		do_action( 'wb_email_customizer_defaults_updated', $options_to_update, $success );

		return $success;
	}

	/**
	 * Get template-specific text content based on selected template.
	 *
	 * @since  1.0.0
	 * @param  string $selected_template The selected template.
	 * @param  string $site_title        The site title.
	 * @param  string $formatted_title   The formatted site title.
	 * @return array Template-specific text content.
	 */
	public function get_template_specific_texts( $selected_template, $site_title, $formatted_title ): array {
		$template_texts = array();

		switch ( $selected_template ) {
			case 'template-three':
				$template_texts = array(
					'woocommerce_email_heading_text'    => __( 'Thanks for your order!', 'email-customizer-for-woocommerce' ),
					/* translators: %s: Formatted site title. */
					'woocommerce_email_subheading_text' => sprintf( __( 'Hello from %s', 'email-customizer-for-woocommerce' ), $formatted_title ),
					/* translators: %s: Site title. */
					'woocommerce_email_body_text'       => sprintf( __( 'Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ), $site_title ),
				);
				break;

			case 'template-two':
				$template_texts = array(
					'woocommerce_email_heading_text'    => __( 'Thanks for your order!', 'email-customizer-for-woocommerce' ),
					'woocommerce_email_subheading_text' => __( 'Order complete', 'email-customizer-for-woocommerce' ),
					/* translators: %s: Site title. */
					'woocommerce_email_body_text'       => sprintf( __( 'Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ), $site_title ),
				);
				break;

			case 'template-one':
				$template_texts = array(
					'woocommerce_email_heading_text'    => __( 'Thanks for your order!', 'email-customizer-for-woocommerce' ),
					/* translators: %s: Site title. */
					'woocommerce_email_subheading_text' => sprintf( __( 'Hello from %s', 'email-customizer-for-woocommerce' ), $site_title ),
					/* translators: %s: Site title. */
					'woocommerce_email_body_text'       => sprintf( __( 'Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ), $site_title ),
				);
				break;

			case 'default':
			default:
				$template_texts = array(
					'woocommerce_email_heading_text'    => __( 'HTML Email Template!', 'email-customizer-for-woocommerce' ),
					'woocommerce_email_subheading_text' => __( 'HTML Email Sub Heading', 'email-customizer-for-woocommerce' ),
					/* translators: %s: Site title. */
					'woocommerce_email_body_text'       => sprintf( __( 'Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ), $site_title ),
				);
				break;
		}

		return $template_texts;
	}

	/**
	 * Get Email Template Options with Template-Specific Overrides.
	 *
	 * @since  1.0.0
	 * @param  string $selected_template The selected template.
	 * @return array Template options.
	 */
	public function get_woocommerce_email_template_options( $selected_template = 'default' ): array {

		// Define default options.
		$default_options = array(
			// Email Template.
			'woocommerce_email_template'                  => 'default',

			// Mail Texts.
			'woocommerce_email_heading_text'              => __( 'Thank you for your order', 'email-customizer-for-woocommerce' ),
			'woocommerce_email_subheading_text'           => __( 'Your order details', 'email-customizer-for-woocommerce' ),
			'woocommerce_email_body_text'                 => __( 'We have received your order and will process it shortly.', 'email-customizer-for-woocommerce' ),

			// Container Padding.
			'woocommerce_email_padding_container_top'     => 15,
			'woocommerce_email_padding_container_bottom'  => 15,
			'woocommerce_email_padding_container_left_right' => 20,

			// Container Borders.
			'woocommerce_email_border_container_top'      => 0,
			'woocommerce_email_border_container_bottom'   => 0,
			'woocommerce_email_border_container_left'     => 0,
			'woocommerce_email_border_container_right'    => 0,
			'woocommerce_email_border_color'              => '#cccccc',

			// Email Header.
			'woocommerce_email_header_image_placement'    => 'inside',
			'woocommerce_email_header_image'              => '',
			'woocommerce_email_header_background_color'   => '#ffffff',
			'woocommerce_email_header_font_size'          => 18,
			'woocommerce_email_header_image_alignment'    => 'center',
			'woocommerce_email_header_text_color'         => '#333333',

			// Email Body/Appearance.
			'woocommerce_email_background_color'          => '#f7f7f7',
			'woocommerce_email_body_background_color'     => '#ffffff',
			'woocommerce_email_link_color'                => '#0073aa',
			'woocommerce_email_body_text_color'           => '#333333',
			'woocommerce_email_body_border_color'         => '#dddddd',
			'woocommerce_email_body_font_size'            => 14,
			'woocommerce_email_body_title_font_size'      => 20,
			'woocommerce_email_width'                     => '600',
			'woocommerce_email_font_family'               => 'sans-serif',
			'woocommerce_email_rounded_corners'           => 0,
			'woocommerce_email_box_shadow_spread'         => 0,

			// Email Footer.
			'woocommerce_email_footer_text'               => __( 'Thank you for shopping with us!', 'email-customizer-for-woocommerce' ),
			'woocommerce_email_footer_font_size'          => 12,
			'woocommerce_email_footer_background_color'   => '#f7f7f7',
			'woocommerce_email_footer_text_color'         => '#666666',
			'woocommerce_email_footer_top_padding'        => 15,
			'woocommerce_email_footer_bottom_padding'     => 15,
			'woocommerce_email_footer_left_right_padding' => 20,

			// Footer Address.
			'woocommerce_email_footer_address_background_color' => '#ffffff',
			'woocommerce_email_footer_address_border'     => 1,
			'woocommerce_email_footer_address_border_color' => '#dddddd',
			'woocommerce_email_footer_address_border_style' => 'solid',
		);

		// Get template-specific overrides.
		$template_overrides = $this->get_template_specific_overrides( $selected_template );

		// Merge defaults with template overrides.
		$final_options = array_merge( $default_options, $template_overrides );

		return $final_options;
	}

	/**
	 * Get template-specific override values.
	 *
	 * @since  1.0.0
	 * @param  string $selected_template The selected template.
	 * @return array Template-specific override values.
	 */
	public function get_template_specific_overrides( $selected_template ): array {

		$template_overrides = array();

		switch ( $selected_template ) {
			case 'template-one':
				$template_overrides = array(
					'woocommerce_email_template'          => 'template-one',
					'woocommerce_email_header_text_color' => '#32373c',
					'woocommerce_email_body_background_color' => '#ffffff',
					'woocommerce_email_header_background_color' => '#ffffff',
					'woocommerce_email_footer_address_background_color' => '#ffffff',
					'woocommerce_email_footer_address_border' => '1',
					'woocommerce_email_rounded_corners'   => '0',
					'woocommerce_email_border_container_top' => '0',
					'woocommerce_email_border_container_bottom' => '0',
					'woocommerce_email_border_container_left' => '0',
					'woocommerce_email_border_container_right' => '0',
					'woocommerce_email_body_border_color' => '#f6f6f6',
					'woocommerce_email_footer_text_color' => '#ffffff',
					'woocommerce_email_footer_background_color' => '#202020',
				);
				break;

			case 'template-two':
				$template_overrides = array(
					'woocommerce_email_template'          => 'template-two',
					'woocommerce_email_header_text_color' => '#32373c',
					'woocommerce_email_body_background_color' => '#ffffff',
					'woocommerce_email_header_background_color' => '#ffffff',
					'woocommerce_email_footer_address_background_color' => '#ffffff',
					'woocommerce_email_footer_address_border' => '1',
					'woocommerce_email_rounded_corners'   => '0',
					'woocommerce_email_border_container_top' => '0',
					'woocommerce_email_border_container_bottom' => '0',
					'woocommerce_email_border_container_left' => '0',
					'woocommerce_email_border_container_right' => '0',
					'woocommerce_email_body_border_color' => '#dddddd',
					'woocommerce_email_footer_text_color' => '#202020',
					'woocommerce_email_footer_background_color' => '#ffffff',
				);
				break;

			case 'template-three':
				$template_overrides = array(
					'woocommerce_email_template'          => 'template-three',
					'woocommerce_email_header_text_color' => '#32373c',
					'woocommerce_email_body_background_color' => '#ffd2d3',
					'woocommerce_email_header_background_color' => '#ffd2d3',
					'woocommerce_email_footer_address_background_color' => '#ffd2d3',
					'woocommerce_email_footer_address_border' => '2',
					'woocommerce_email_rounded_corners'   => '0',
					'woocommerce_email_border_container_top' => '0',
					'woocommerce_email_border_container_bottom' => '0',
					'woocommerce_email_border_container_left' => '0',
					'woocommerce_email_border_container_right' => '0',
					'woocommerce_email_body_border_color' => '#505050',
					'woocommerce_email_footer_text_color' => '#ffffff',
					'woocommerce_email_footer_background_color' => '#202020',
				);
				break;

			case 'default':
				$template_overrides = array(
					'woocommerce_email_template'          => 'default',
					'woocommerce_email_header_text_color' => '#ffffff',
					'woocommerce_email_body_background_color' => '#fdfdfd',
					'woocommerce_email_header_background_color' => '#557da1',
					'woocommerce_email_footer_address_background_color' => '#ffffff',
					'woocommerce_email_footer_address_border' => '1',
					'woocommerce_email_rounded_corners'   => '6',
					'woocommerce_email_border_container_top' => '1',
					'woocommerce_email_border_container_bottom' => '1',
					'woocommerce_email_border_container_left' => '1',
					'woocommerce_email_border_container_right' => '1',
					'woocommerce_email_body_border_color' => '#505050',
					'woocommerce_email_footer_text_color' => '#ffffff',
					'woocommerce_email_footer_background_color' => '#202020',
				);
				break;

			default:
				// No overrides for default template.
				$template_overrides = array();
				break;
		}

		return $template_overrides;
	}

	/**
	 * Handle custom save via AJAX.
	 *
	 * @since  1.0.0
	 * @param  WP_Customize_Manager $wp_customize The customizer manager instance.
	 * @return void
	 */
	public function wb_email_customizer_handle_custom_save( $wp_customize ) {
		$posted_values = $wp_customize->unsanitized_post_values();

		// Check user capabilities.
		if ( ! current_user_can( 'customize' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'email-customizer-for-woocommerce' ) );
		}

		if ( isset( $posted_values['woocommerce_email_template'] ) && ! empty( $posted_values['woocommerce_email_template'] ) ) {
			$selected_template = isset( $posted_values['woocommerce_email_template'] ) ? sanitize_text_field( wp_unslash( $posted_values['woocommerce_email_template'] ) ) : '';

			if ( ! empty( $selected_template ) ) {
				$custom_defaults = $this->get_template_specific_overrides( $selected_template );
				$this->wb_email_customizer_update_all_defaults( $custom_defaults );
			}
		}

		if ( isset( $posted_values['woocommerce_email_body_background_color'] ) && ! empty( $posted_values['woocommerce_email_body_background_color'] ) ) {
			$woocommerce_email_body_background_color = isset( $posted_values['woocommerce_email_body_background_color'] ) ? sanitize_text_field( wp_unslash( $posted_values['woocommerce_email_body_background_color'] ) ) : '#fffff';
			update_option( 'woocommerce_email_body_background_color', $woocommerce_email_body_background_color );
		}

		if ( isset( $posted_values['woocommerce_email_background_color'] ) && ! empty( $posted_values['woocommerce_email_background_color'] ) ) {
			$woocommerce_email_background_color = isset( $posted_values['woocommerce_email_background_color'] ) ? sanitize_text_field( wp_unslash( $posted_values['woocommerce_email_background_color'] ) ) : '#fffff';
			update_option( 'woocommerce_email_background_color', $woocommerce_email_background_color );
		}

		// Optional: Add custom logic here (like sending test emails, clearing cache, etc.).
		do_action( 'wb_email_customizer_after_save', $posted_values );
	}
}