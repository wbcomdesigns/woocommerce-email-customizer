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
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * plugin_settings_tabs
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

		if ( isset( $_GET[ $this->email_trigger ] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_action( 'wp_print_styles', array( $this, 'wb_email_customizer_remove_theme_styles' ), 100 );
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Email_Customizer_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Email_Customizer_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/email-customizer-for-woocommerce-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Email_Customizer_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Email_Customizer_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/email-customizer-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Hide all notices from the setting page.
	 *
	 * @return void
	 */
	public function wb_email_hide_all_admin_notices_from_setting_page() {
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
	public function wb_email_customizer_admin_options_page() {
		global $allowedposttags;
		$tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'wb-email-customizer-welcome';
		?>
		<div class="wrap">
			<div class="wbcom-bb-plugins-offer-wrapper">
				<div id="wb_admin_logo">
					<a href="https://wbcomdesigns.com/downloads/buddypress-community-bundle/" target="_blank">
						<img src="<?php echo esc_url( EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_URL ) . 'admin/wbcom/assets/imgs/wbcom-offer-notice.png'; ?>">
					</a>
				</div>
			</div>
			<div class="wbcom-wrap">
				<div class="blpro-header">
					<div class="wbcom_admin_header-wrapper">
						<div id="wb_admin_plugin_name">
							<?php esc_html_e( 'Email Customizer For Woocommerce', 'email-customizer-for-woocommerce' ); ?>
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
	public function wb_email_customizer_init_plugin_settings() {
		$this->plugin_settings_tabs['wb-email-customizer-welcome'] = esc_html__( 'Welcome', 'email-customizer-for-woocommerce' );
		register_setting( 'wb_email_customizer_admin_welcome_options', 'wb_email_customizer_admin_welcome_options' );
		add_settings_section( 'wb-email-customizer-welcome', ' ', array( $this, 'wb_email_customizer_admin_welcome_content' ), 'wb-email-customizer-welcome' );

		$this->plugin_settings_tabs['wb-email-customizer-faq'] = esc_html__( 'FAQ', 'email-customizer-for-woocommerce' );
		register_setting( 'wb_email_customizer_faq_options', 'wb_email_customizer_faq_options' );
		add_settings_section( 'wb-email-customizer-faq', ' ', array( $this, 'wb_email_customizer_faq_options_content' ), 'wb-email-customizer-faq' );
	}

	/**
	 * Actions performed to create tabs on the sub menu page.
	 */
	public function wb_email_customizer_plugin_settings_tabs() {
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
	public function wb_email_customizer_admin_welcome_content() {
		include plugin_dir_path( __DIR__ ) . 'admin/partials/wb-email-customizer-welcome-page.php';
	}

	/**
	 * Wb_email_customizer_faq_options_content
	 *
	 * @return void
	 */
	public function wb_email_customizer_faq_options_content() {
		include plugin_dir_path( __DIR__ ) . 'admin/partials/wb-email-customizer-faq.php';
	}


	/**
	 * Actions performed on loading admin_menu.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function wb_email_customizer_views_add_admin_settings() {
		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) && class_exists( 'WooCommerce' ) ) {
			add_menu_page( esc_html__( 'WB Plugins', 'email-customizer-for-woocommerce' ), esc_html__( 'WB Plugins', 'email-customizer-for-woocommerce' ), 'manage_options', 'wbcomplugins', array( $this, 'wb_email_customizer_admin_options_page' ), 'dashicons-lightbulb', 59 );
			add_submenu_page( 'wbcomplugins', esc_html__( 'Welcome', 'email-customizer-for-woocommerce' ), esc_html__( 'Welcome', 'email-customizer-for-woocommerce' ), 'manage_options', 'wbcomplugins' );

		}
		add_submenu_page( 'wbcomplugins', esc_html__( 'WC Email Customizer', 'email-customizer-for-woocommerce' ), esc_html__( 'WC Email Customizer', 'email-customizer-for-woocommerce' ), 'manage_options', 'wb-email-customizer-settings', array( $this, 'wb_email_customizer_admin_options_page' ) );
	}




	/**
	 * Add a submenu item to the WooCommerce menu.
	 *
	 * @since   1.0
	 */
	public function wb_email_customizer_admin_setting_submenu_pages() {
		global $menu, $submenu;

		$url = admin_url( 'customize.php' );

		$url = add_query_arg( 'email-customizer-for-woocommerce', 'true', $url );

		$url = add_query_arg( 'url', wp_nonce_url( site_url() . '/?email-customizer-for-woocommerce=true', 'preview-mail' ), $url );

		$url = add_query_arg(
			'return',
			urlencode(
				add_query_arg(
					array(
						'page' => 'wc-settings',
						'tab'  => 'email',
					),
					admin_url( 'admin.php' )
				)
			),
			$url
		);
		add_submenu_page(
			'woocommerce',
			__( 'Woocommerce Email Customizer', 'email-customizer-for-woocommerce' ),
			__( 'Email Customizer', 'email-customizer-for-woocommerce' ),
			'manage_woocommerce',
			$this->plugin_name,
			function () use ( $url ) {
				wp_redirect( $url );
				exit;
			}
		);
	}

	/**
	 * Added admin page.
	 *
	 * @since   1.0
	 */
	public function wb_email_customizer_admin_page() {
		$template = get_option( 'woocommerce_email_template' );

		if ( $template == 'template-one' ) {
			require_once 'partials/email-customizer-for-woocommerce-admin-display-template-one.php';
		} elseif ( $template == 'template-two' ) {
			require_once 'partials/email-customizer-for-woocommerce-admin-display-template-two.php';
		} elseif ( $template == 'template-three' ) {
			require_once 'partials/email-customizer-for-woocommerce-admin-display-template-three.php';
		} else {
			require_once 'partials/email-customizer-for-woocommerce-admin-display.php';
		}
	}
	/**
	 * Added Customizer Sections.
	 *
	 * @since   1.0
	 * @param string $wp_customize Get a Customizer Section.
	 */
	public function wb_email_customizer_add_sections( $wp_customize ) {
		$wb_email_customizer_check_url = isset( $_GET['email-customizer-for-woocommerce'] ) ? sanitize_text_field( wp_unslash( $_GET['email-customizer-for-woocommerce'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( is_user_logged_in() && true == $wb_email_customizer_check_url ) {
			$wp_customize->add_panel(
				'wc_email_header',
				array(
					'title'      => __( 'Email Customizer', 'email-customizer-for-woocommerce' ),
					'capability' => 'edit_theme_options',
					'priority'   => 10,
				)
			);

			$wp_customize->add_section(
				'wc_email_templates',
				array(
					'title'      => __( 'Email Templates', 'email-customizer-for-woocommerce' ),
					'capability' => 'edit_theme_options',
					'priority'   => 20,
					'panel'      => 'wc_email_header',
				)
			);

			$wp_customize->add_section(
				'wc_email_text',
				array(
					'title'      => __( 'Email Texts', 'email-customizer-for-woocommerce' ),
					'capability' => 'edit_theme_options',
					'priority'   => 30,
					'panel'      => 'wc_email_header',
				)
			);

			$wp_customize->add_section(
				'wc_email_header',
				array(
					'title'      => __( 'Email Header', 'email-customizer-for-woocommerce' ),
					'capability' => 'edit_theme_options',
					'priority'   => 20,
					'panel'      => 'wc_email_header',
				)
			);

			$wp_customize->add_section(
				'wc_email_appearance_customizer',
				array(
					'title'      => __( 'Container', 'email-customizer-for-woocommerce' ),
					'capability' => 'edit_theme_options',
					'priority'   => 20,
					'panel'      => 'wc_email_header',
				)
			);

			$wp_customize->add_section(
				'wc_email_body',
				array(
					'title'      => __( 'Email Body', 'email-customizer-for-woocommerce' ),
					'capability' => 'edit_theme_options',
					'priority'   => 30,
					'panel'      => 'wc_email_header',
				)
			);

			$wp_customize->add_section(
				'wc_email_footer',
				array(
					'title'      => __( 'Email Footer', 'email-customizer-for-woocommerce' ),
					'capability' => 'edit_theme_options',
					'priority'   => 50,
					'panel'      => 'wc_email_header',
				)
			);

			$wp_customize->add_section(
				'wc_email_send',
				array(
					'title'      => __( 'Send Test Email', 'email-customizer-for-woocommerce' ),
					'capability' => 'edit_theme_options',
					'priority'   => 60,
					'panel'      => 'wc_email_header',
				)
			);

		}
	}
	/**
	 * Added Customizer Controls.
	 *
	 * @since   1.0
	 * @param string $wp_customize Get a Customizer Section.
	 */
	public function wb_email_customizer_add_controls( $wp_customize ) {

		/**
		 * email template
		 */
		$image_base_url = plugin_dir_url( __FILE__ ) . 'img/';
		$wp_customize->add_control(
			new theme_slug_Image_Radio_Control(
				$wp_customize,
				'theme_slug_default_layout',
				array(
					'type'     => 'radio',
					'label'    => esc_html__( 'Select default layout', 'email-customizer-for-woocommerce' ),
					'section'  => 'wc_email_templates',
					'settings' => 'woocommerce_email_template',
					'type'     => 'text',
					'choices'  => array(
						'default'        => $image_base_url . 'woo_full_template.jpg',
						'template-one'   => $image_base_url . 'woo_full_template.jpg',
						'template-two'   => $image_base_url . 'woo_skinny_template.jpg',
						'template-three' => $image_base_url . 'woo_flat_template.jpg',

					),
				)
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
					'label'    => __( 'Heading Text', 'email-customizer-for-woocommerce' ),
					'priority' => 30,
					'section'  => 'wc_email_text',
					'settings' => 'woocommerce_email_heading_text',
					'type'     => 'text',
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
		 * container padding
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
					'min'  => 15,
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
					'min'  => 15,
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
					'min'  => 15,
					'max'  => 50,
					'step' => 1,
				),
			)
		);

		/**
		 * emails appearance customizer
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
					'min'  => 15,
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
					'min'  => 15,
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
					'min'  => 15,
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
					'min'  => 15,
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
					'priority' => 1,
					'section'  => 'wc_email_header',
					'settings' => 'woocommerce_email_header_image_placement',
					'type'     => 'select',
					'choices'  => array(
						''        => __( 'Select the body container', 'email-customizer-for-woocommerce' ),
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
					'label'    => __( 'Upload a Header', 'email-customizer-for-woocommerce' ),
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
					'label'    => __( 'Header Background Color', 'email-customizer-for-woocommerce' ),
					'priority' => 30,
					'section'  => 'wc_email_header',
					'settings' => 'woocommerce_email_header_background_color',
				)
			)
		);

		$wp_customize->add_control(
			'wc_email_header_font_size_control',
			array(
				'type'        => 'range',
				'priority'    => 50,
				'section'     => 'wc_email_header',
				'label'       => __( 'Font Size', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Font Size', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_header_font_size',
				'input_attrs' => array(
					'min'  => 15,
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
					'label'    => __( 'Email Width', 'email-customizer-for-woocommerce' ),
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
				'description' => __( 'Rounded corners', 'email-customizer-for-woocommerce' ),
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
				'description' => __( 'Amount of shadow behind email', 'email-customizer-for-woocommerce' ),
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
			new WP_Customize_Color_Control(
				$wp_customize,
				'wc_email_footer_text_color_control',
				array(
					'label'    => __( 'Footer Text Color', 'email-customizer-for-woocommerce' ),
					'priority' => 20,
					'section'  => 'wc_email_footer',
					'settings' => 'woocommerce_email_footer_text_color',
				)
			)
		);

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
			'wc_email_footer_top_padding_control',
			array(
				'type'        => 'range',
				'priority'    => 60,
				'section'     => 'wc_email_footer',
				'label'       => __( 'Footer Top Padding', 'email-customizer-for-woocommerce' ),
				'description' => __( 'Footer Top Padding', 'email-customizer-for-woocommerce' ),
				'settings'    => 'woocommerce_email_footer_top_padding',
				'input_attrs' => array(
					'min'  => 10,
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
					'min'  => 10,
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
					'min'  => 10,
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
					'min'  => 10,
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
					'priority' => 30,
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
					'priority' => 130,
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
	 * Show only our email settings in the preview
	 *
	 * @since 1.0.0
	 */
	public function control_filter( $active, $control ) {
		if ( in_array( $control->section, array( 'wc_email_template', 'wc_email_text', 'wc_email_header', 'wc_email_body', 'wc_email_footer', 'wc_email_send' ) ) ) {

			return true;
		}

		return false;
	}
	/**
	 * Added Customizer Settings.
	 *
	 * @param string $wp_customize Get a Customizer Section.
	 */
	public function wb_email_customizer_add_customizer_settings( $wp_customize ) {

		// Get the selected email template.
		$selected_template = get_option( 'woocommerce_email_template' );

		// Get site title.
		$site_title = get_bloginfo( 'name' );
		$formatted_title = ucwords(strtolower(str_replace('-', ' ', $site_title)));

		// Define default values based on selected template
		if ( 'template-three' === $selected_template ) {
			$default_heading_text            = __( 'Thanks for your order!', 'email-customizer-for-woocommerce' );
			$default_subheading_text         = sprintf( __( 'Hello from %s', 'email-customizer-for-woocommerce' ), $formatted_title );
			$default_body_text               = sprintf( __( 'Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ), $site_title );
			$default_header_text_color       = '#32373c';
			$default_body_bg                 = '#ffee8c';
			$default_header_bg               = '#ffee8c';
			$default_address_background      = '#ffee8c';
			$default_address_border          = '2';
			$default_rounded_corners         = '0';
			$default_border_container_top    = '0';
			$default_border_container_bottom = '0';
			$default_border_container_left   = '0';
			$default_border_container_right  = '0';
			$default_body_border_color       = '#505050';
			$default_footer_text_color       = '#ffffff';
			$default_footer_background_color = '#202020';
		} elseif ( 'template-two' === $selected_template ) {
			$default_heading_text            = __( 'Thanks for your order!', 'email-customizer-for-woocommerce' );
			$default_subheading_text         = __( 'Order complete', 'email-customizer-for-woocommerce' );
			$default_body_text               = sprintf( __( 'Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ), $site_title );
			$default_header_text_color       = '#32373c';
			$default_body_bg                 = '#ffffff';
			$default_header_bg               = '#ffffff';
			$default_address_background      = '#ffffff';
			$default_address_border          = '1';
			$default_rounded_corners         = '0';
			$default_border_container_top    = '0';
			$default_border_container_bottom = '0';
			$default_border_container_left   = '0';
			$default_border_container_right  = '0';
			$default_body_border_color       = '#dddddd';
			$default_footer_text_color       = '#202020';
			$default_footer_background_color = '#ffffff';
		} elseif ( 'template-one' === $selected_template ) {
			$default_subheading_text         = sprintf( __( 'Hello from %s', 'email-customizer-for-woocommerce' ), $site_title );
			$default_heading_text            = __( 'Thanks for your order!', 'email-customizer-for-woocommerce' );
			$default_body_text               = sprintf( __( 'Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ), $site_title );
			$default_header_text_color       = '#32373c';
			$default_body_bg                 = '#ffffff';
			$default_header_bg               = '#ffffff';
			$default_address_background      = '#ffffff';
			$default_address_border          = '1';
			$default_rounded_corners         = '0';
			$default_border_container_top    = '0';
			$default_border_container_bottom = '0';
			$default_border_container_left   = '0';
			$default_border_container_right  = '0';
			$default_body_border_color       = '#dddddd';
			$default_footer_text_color       = '#202020';
			$default_footer_background_color = '#ffffff';
		} else {
			$default_subheading_text         = sprintf( __( 'Hello from %s', 'email-customizer-for-woocommerce' ), $site_title );
			$default_heading_text            = __( 'Thanks for your order!', 'email-customizer-for-woocommerce' );
			$default_body_text               = sprintf( __( 'Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:', 'email-customizer-for-woocommerce' ), $site_title );
			$default_header_text_color       = '#ffffff';
			$default_body_bg                 = '#fdfdfd';
			$default_header_bg               = '#557da1';
			$default_address_background      = '#202020';
			$default_address_border          = '12';
			$default_rounded_corners         = '6';
			$default_border_container_top    = '1';
			$default_border_container_bottom = '1';
			$default_border_container_left   = '1';
			$default_border_container_right  = '1';
			$default_body_border_color       = '#505050';
			$default_footer_text_color       = '#ffffff';
			$default_footer_background_color = '#202020';
		}

		$wp_customize->add_setting(
			'woocommerce_email_heading_text',
			array(
				'type'      => 'option',
				'default'   => $default_heading_text,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_subheading_text',
			array(
				'type'      => 'option',
				'default'   => $default_subheading_text,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_body_text',
			array(
				'type'      => 'option',
				'default'   => $default_body_text,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_template',
			array(
				'type'      => 'option',
				'default'   => '',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_header_image_placement',
			array(
				'type'      => 'option',
				'default'   => '',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_header_image_alignment',
			array(
				'type'      => 'option',
				'default'   => 'center',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_padding_container_top',
			array(
				'type'      => 'option',
				'default'   => '30',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_padding_container_bottom',
			array(
				'type'      => 'option',
				'default'   => '30',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_padding_container_left_right',
			array(
				'type'      => 'option',
				'default'   => '30',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_border_container_top',
			array(
				'type'      => 'option',
				'default'   => $default_border_container_top,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_border_container_bottom',
			array(
				'type'      => 'option',
				'default'   => $default_border_container_bottom,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_border_container_left',
			array(
				'type'      => 'option',
				'default'   => $default_border_container_left,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_border_container_right',
			array(
				'type'      => 'option',
				'default'   => $default_border_container_right,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_background_color',
			array(
				'type'      => 'option',
				'default'   => '#f5f5f5',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_body_background_color',
			array(
				'type'      => 'option',
				'default'   => $default_body_bg,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_header_background_color',
			array(
				'type'      => 'option',
				'default'   => $default_header_bg,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_header_text_color',
			array(
				'type'      => 'option',
				'default'   => $default_header_text_color,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_header_font_size',
			array(
				'type'      => 'option',
				'default'   => '30',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_body_text_color',
			array(
				'type'      => 'option',
				'default'   => '#505050',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_body_border_color',
			array(
				'type'      => 'option',
				'default'   => $default_body_border_color,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_body_font_size',
			array(
				'type'      => 'option',
				'default'   => '12',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_body_title_font_size',
			array(
				'type'      => 'option',
				'default'   => '18',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_rounded_corners',
			array(
				'type'      => 'option',
				'default'   => $default_rounded_corners,
				'transport' => 'postMessage',
			)
		);

		// Add a select box for the box shadow.
		$wp_customize->add_setting(
			'woocommerce_email_box_shadow_spread',
			array(
				'type'      => 'option',
				'default'   => '1',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_font_family',
			array(
				'type'      => 'option',
				'default'   => 'sans-serif',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_link_color',
			array(
				'type'      => 'option',
				'default'   => '#214cce',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_header_image',
			array(
				'type'      => 'option',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_width',
			array(
				'type'      => 'option',
				'default'   => '600',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_text',
			array(
				'type'      => 'option',
				'default'   => __( 'WooCommece Email Customizer - Powered by WooCommerce and WordPress', 'email-customizer-for-woocommerce' ),
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_font_size',
			array(
				'type'      => 'option',
				'default'   => '12',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_text_color',
			array(
				'type'      => 'option',
				'default'   => $default_footer_text_color,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_top_padding',
			array(
				'type'      => 'option',
				'default'   => '10',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_bottom_padding',
			array(
				'type'      => 'option',
				'default'   => '10',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_left_right_padding',
			array(
				'type'      => 'option',
				'default'   => '10',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_background_color',
			array(
				'type'      => 'option',
				'default'   => $default_footer_background_color,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_border_color',
			array(
				'type'      => 'option',
				'default'   => '#202020',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_send',
			array(
				'type'    => 'option',
				'default' => '',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_address_border_color',
			array(
				'type'      => 'option',
				'default'   => '#202020',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_address_border',
			array(
				'type'      => 'option',
				'default'   => $default_address_border,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_address_background_color',
			array(
				'type'      => 'option',
				'default'   => $default_address_background,
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_footer_address_border_style',
			array(
				'type'      => 'option',
				'default'   => 'solid',
				'transport' => 'postMessage',
			)
		);
	}
	/**
	 * Add custom variables to the available query vars.
	 *
	 * @param  mixed $vars Email Vare.
	 */
	public function wb_email_customizer_add_query_vars( $vars ) {
		$vars[] = $this->email_trigger;

		return $vars;
	}
	/**
	 * If the right query var is present load the email template.
	 *
	 * @param mixed $wp_query Custom Query.
	 */
	public function wb_email_customizer_load_email_template( $wp_query ) {

		if ( get_query_var( $this->email_trigger ) ) {
			static $alredy_excute = false;
			if ( $alredy_excute ) {
				return;
			}

			$mailer = WC()->mailer();

			ob_start();

			$template      = get_option( 'woocommerce_email_template' );
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

			if ( file_exists( $template_file ) ) {
				include $template_file;
				$alredy_excute = true;
			}

			$message = ob_get_clean();

			if ( ! empty( get_option( 'woocommerce_email_heading_text' ) ) ) {
				$email_heading = get_option( 'woocommerce_email_heading_text' );
			} else {
				$email_heading = __( 'Thanks for your order!', 'email-customizer-for-woocommerce' );
			}

			$email = new WC_Email();
			
			$messages = $email->style_inline( $mailer->wrap_message($email_heading,$message));
			echo $messages; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			exit;
		}

		return $wp_query;
	}

	/**
	 * Overrides WooCommerce email template path with a custom one.
	 *
	 * @param string $template      The path to the template found.
	 * @param string $template_name The name of the template file.
	 * @param string $template_path The path to the template directory.
	 * @return string Modified template path if custom one exists.
	 */

	public function wb_email_customizer_custom_universal_email_template_override( $template, $template_name, $template_path ) {
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

		$selected_template = get_option( 'woocommerce_email_template' );

		if ( $selected_template === 'template-one' && in_array( $template_name, $order_email_templates ) ) {
			$template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/admin/partials/email-customizer-for-woocommerce-admin-display-template-one.php';
			// $template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/templates/emails/customer-completed-order.php';

		} elseif ( $selected_template === 'template-two' && in_array( $template_name, $order_email_templates ) ) {

			$template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/admin/partials/email-customizer-for-woocommerce-admin-display-template-two.php';
			// $template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/templates/emails/customer-completed-order.php';

		} elseif ( $selected_template === 'template-three' && in_array( $template_name, $order_email_templates ) ) {

			$template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/admin/partials/email-customizer-for-woocommerce-admin-display-template-three.php';
			// $template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/templates/emails/customer-completed-order.php';

		} elseif ( $selected_template === 'default' && in_array( $template_name, $order_email_templates ) ) {
			$template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/admin/partials/email-customizer-for-woocommerce-admin-display.php';
			// $template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/templates/emails/customer-completed-order.php';

		}
		if ($template_name === 'emails/email-header.php') {
        	$template = EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/templates/emails/email-header.php';
		}

		return $template;
	}

	/**
	 * Hook in email header with access to the email object
	 *
	 * @param string $email_heading email heading.
	 * @param object $email the email object.
	 * @access public
	 * @return void
	 */
	public function add_email_header( $email_heading, $email = '' ) {
		wc_get_template(
			'emails/email-header.php',
			array(
				'email_heading' => $email_heading,
				'email'         => $email,
			)
		);
	}


	/**
	 * Enqueues the customizer JS script.
	 */
	public function wb_email_customizer_enqueue_customizer_script() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'woocommerce-email-customizer-live-preview', EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_URL . '/admin/js/customizer' . $suffix . '.js', array( 'jquery', 'customize-preview' ), EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION, true );

		return true;
	}

	/**
	 * Modify the styles in the WooCommerce emails with the styles in the customizer.
	 *
	 * @param mixed $styles CSS a blob of CSS.
	 */
	public function wb_email_customizer_add_styles( $styles ) {

		$selected_template = get_option( 'woocommerce_email_template' );

		$bg_color = 'body, body > div, body > #wrapper > table > tbody > tr > td { background-color:' . get_option( 'woocommerce_email_background_color', '#f5f5f5' ) . '; }' . PHP_EOL;

		$body_bg_color = '#template_container { background-color:' . get_option( 'woocommerce_email_body_background_color', '#fdfdfd' ) . '; } #template_body, #template_body td, #body_content { background: transparent none; }' . PHP_EOL;

		$header_bg_color = '#template_header { background-color:' . get_option( 'woocommerce_email_header_background_color', '#557da1' ) . '; } #header_wrapper, #header_wrapper h1 { background: transparent none; }' . PHP_EOL;

		$header_text_color = '#template_header h1 { color:' . get_option( 'woocommerce_email_header_text_color', '#ffffff' ) . '; text-shadow:0 1px 0 ' . get_option( 'woocommerce_email_header_text_color', '#ffffff' ) . '; }' . PHP_EOL;

		$header_font_size = '#template_header h1 { font-size:' . get_option( 'woocommerce_email_header_font_size', '30' ) . 'px' . '; }' . PHP_EOL;

		$header_image = '#template_header_image { margin-left: auto; margin-right: auto; }' . PHP_EOL;

		$template_container_border = 'table, table th, table td { border:none; border-style:none; border-width:0; }' . PHP_EOL;

		$template_rounded_corners = '#template_container { border-radius:' . get_option( 'woocommerce_email_rounded_corners', '6' ) . 'px !important; } #template_header { border-radius:' . get_option( 'woocommerce_email_rounded_corners', '6' ) . 'px ' . get_option( 'woocommerce_email_rounded_corners', '6' ) . 'px 0 0 !important; } #template_footer { border-radius:0 0 ' . get_option( 'woocommerce_email_rounded_corners', '6' ) . 'px ' . get_option( 'woocommerce_email_rounded_corners', '6' ) . 'px !important; }' . PHP_EOL;

		if ( 'template-two' === $selected_template || 'template-three' === $selected_template ) { 
			$template_shadow = '#template_container { box-shadow: none !important; }' . PHP_EOL;
		} else {
			$template_shadow = '#template_container { box-shadow:0 0 6px ' . get_option( 'woocommerce_email_box_shadow_spread', '1' ) . 'px rgba(0,0,0,0.2) !important; }' . PHP_EOL;
		}

		$body_items_table = '#body_content_inner table { border-collapse: collapse; width:100%; }' . PHP_EOL;

		$body_text_color = '#template_body div, #template_body div p, #template_body h2, #template_body h3, #template_body table td, #template_body table th, #template_body table tr, #template_body table, #template_body table h3, #template_body table .body-content-title a { color:' . get_option( 'woocommerce_email_body_text_color', '#505050' ) . '; }' . PHP_EOL;

		if ( 'template-three' === $selected_template ) {
			$body_border_color = '#body_content_inner table td, #body_content_inner table th { border-color:' . get_option( 'woocommerce_email_body_border_color', '#505050' ) . '; border-width: 2px; border-style:solid; text-align:left; }' . PHP_EOL;
			$addresses         = '.addresses td { border:none !important; line-height:1.5; padding-right: 12px !important; } .addresses td + td { padding-left: 12px !important; }' . PHP_EOL;
		} else if ( 'template-two' === $selected_template ) {
			$body_border_color = '#body_content_inner table td, #body_content_inner table th { border-color:' . get_option( 'woocommerce_email_body_border_color', '#505050' ) . '; border-width: 1px; border-style: solid; text-align:center; }' . PHP_EOL;
			$addresses         = '.addresses td { line-height:1.5; padding-right: 12px !important; } .addresses td + td { padding-left: 12px !important; }' . PHP_EOL;
		} else {
			$body_border_color = '#body_content_inner table td, #body_content_inner table th { border-color:' . get_option( 'woocommerce_email_body_border_color', '#505050' ) . '; border-width: 1px; border-style:solid; text-align:left; }' . PHP_EOL;
			$addresses         = '.addresses td { border:none !important; line-height:1.5; padding-left: 0 !important; padding-right: 12px !important; } .addresses td + td { padding-left: 12px !important; padding-right: 0 !important; }' . PHP_EOL;
		}

		$body_font_size = '#template_body div, #template_body div p, #template_body table td, #template_body table th, #template_body h2, #template_body h3 { font-size:' . get_option( 'woocommerce_email_body_font_size', '12' ) . 'px' . '; }' . PHP_EOL;

		$body_title_font_size = '#template_body h2, #template_body h3 { font-size:' . get_option( 'woocommerce_email_body_title_font_size', '18' ) . 'px' . '; }' . PHP_EOL;

		$body_link_color = '#template_body div a, #template_body table td a { color:' . get_option( 'woocommerce_email_link_color', '#214cce' ) . '; }' . PHP_EOL;

		$width = '#template_container, #template_header, #template_body, #template_footer { width:' . get_option( 'woocommerce_email_width', '600' ) . 'px' . '; }' . PHP_EOL;

		$footer_font_size = '#template_footer p { font-size:' . get_option( 'woocommerce_email_footer_font_size', '12' ) . 'px' . '; }' . PHP_EOL;

		$footer_text_color = '#template_footer p, #template_footer p a { color:' . get_option( 'woocommerce_email_footer_text_color', '#202020' ) . '; line-height:1.5; }' . PHP_EOL;

		$font_family = get_option( 'woocommerce_email_font_family', 'sans-serif' );

		if ( 'sans-serif' === $font_family ) {
			$font_family = 'Helvetica, Arial, sans-serif';
		} else {
			$font_family = 'Georgia, serif';
		}

		$font_family = '#template_container, #template_header h1, #template_body table div, #template_footer p, #template_footer th, #template_footer td, #template_body table table, #body_content_inner table, #template_footer table { font-family:' . $font_family . '; }' . PHP_EOL;

		if ( 'template-two' === $selected_template || 'template-three' === $selected_template ) {
			$apprance_border_color = '#template_container{ border-color: ' . get_option( 'woocommerce_email_border_color', '#202020' ) . ' !important; border-top: ' . get_option( 'woocommerce_email_border_container_top', '0' ) . 'px' . 'solid' . '; border-bottom: ' . get_option( 'woocommerce_email_border_container_bottom', '0' ) . 'px' . 'solid' . '; border-left: ' . get_option( 'woocommerce_email_border_container_left', '0' ) . 'px' . 'solid' . '; border-right: ' . get_option( 'woocommerce_email_border_container_right', '0' ) . 'px' . 'solid' . ';}' . PHP_EOL;
		} else {
			$apprance_border_color = '#template_container{ border-color: ' . get_option( 'woocommerce_email_border_color', '#202020' ) . ' !important; border-top: ' . get_option( 'woocommerce_email_border_container_top', '1' ) . 'px' . 'solid' . '; border-bottom: ' . get_option( 'woocommerce_email_border_container_bottom', '1' ) . 'px' . 'solid' . '; border-left: ' . get_option( 'woocommerce_email_border_container_left', '1' ) . 'px' . 'solid' . '; border-right: ' . get_option( 'woocommerce_email_border_container_right', '1' ) . 'px' . 'solid' . ';}' . PHP_EOL;
		}

		$padding_conatiner = '#header_wrapper {padding-top: ' . get_option( 'woocommerce_email_padding_container_top', '10' ) . 'px' . ' !important; padding-bottom: ' . get_option( 'woocommerce_email_padding_container_bottom', '10' ) . 'px' . ' !important; padding-left: ' . get_option( 'woocommerce_email_padding_container_left_right', '10' ) . 'px' . ' !important; padding-right: ' . get_option( 'woocommerce_email_padding_container_left_right', '10' ) . 'px' . ' !important;}' . PHP_EOL;

		$footer_address = 'table.addresses {background: ' . get_option( 'woocommerce_email_footer_address_background_color', '#202020' ) . ';border: ' . get_option( 'woocommerce_email_footer_address_border', '2' ) . 'px' . get_option( 'woocommerce_email_footer_address_border_style', 'solid' ) . ' ' . get_option( 'woocommerce_email_footer_address_border_color', '#202020' ) . ' !important;}' . PHP_EOL;

		if ( 'template-three' === $selected_template ) {
			$body_title_style = '.has-border { text-decoration: none; margin-bottom: 20px; border: 0; border-bottom: ' . get_option( 'woocommerce_email_footer_address_border', '2' ) . 'px' . get_option( 'woocommerce_email_footer_address_border_style', 'solid' ) . ' ' . get_option( 'woocommerce_email_footer_address_border_color', '#202020' ) . ' !important; }' . PHP_EOL;
		} else if ( 'template-two' === $selected_template ) {
			$body_title_style = '.body-content-title { text-align: center; margin: 40px 0 30px; }' . PHP_EOL;
		} else {
			$body_title_style = '';
		}

		$fottor_botton = '#template_footer{ background:' . get_option( 'woocommerce_email_footer_background_color', '#202020' ) . '; padding-top: ' . get_option( 'woocommerce_email_footer_top_padding', '10' ) . 'px' . '; padding-bottom: ' . get_option( 'woocommerce_email_footer_bottom_padding', '10' ) . 'px' . '; padding-left: ' . get_option( 'woocommerce_email_footer_left_right_padding', '10' ) . 'px' . '; padding-right: ' . get_option( 'woocommerce_email_footer_left_right_padding', '10' ) . 'px' . ';}' . PHP_EOL;

		$image_alignment = '#template_header_image img {float: ' . get_option( 'woocommerce_email_header_image_alignment', 'center' ) . ';}' . PHP_EOL;

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
		$styles .= $fottor_botton;
		$styles .= $image_alignment;
		$styles .= $body_title_style;
		return $styles;
	}

	/**
	 * Replace the footer text with the footer text from the customizer
	 *
	 * @param string $text Email Footer Text.
	 */
	public function wb_email_customizer_email_footer_text( $text ) {
		return get_option( 'woocommerce_email_footer_text', __( 'Email Customizer For Woocommerce - Powered by WooCommerce and WordPress', 'email-customizer-for-woocommerce' ) );
	}

	// public function wbcom_woocommerce_email_header() {
	// wc_get_template( 'emails/email-header.php', array( 'email_heading' => $email_heading, 'email' => $email ) );
	// echo "hello";
	// }

	/**
	 * Enqueues scripts on the control panel side
	 *
	 * @since 1.0.0
	 */
	public function wb_email_customizer_enqueue_customizer_control_script() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'woocommerce-email-customizer-live-preview', EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_URL . '/admin/js/customizer' . $suffix . '.js', array( 'jquery', 'customize-preview' ), EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_VERSION, true );

		$localized_vars = array(
			'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			'ajaxSendEmailNonce' => wp_create_nonce( '_wc_email_customizer_send_email_nonce' ),
			'error'              => __( 'Error, Try again!', 'email-customizer-for-woocommerce' ),
			'success'            => __( 'Email Sent!', 'email-customizer-for-woocommerce' ),
			'saveFirst'          => __( 'Please click on save/publish before sending the test email', 'email-customizer-for-woocommerce' ),
		);

		wp_localize_script( 'woocommerce-email-customizer-controls', 'woocommerce_email_customizer_controls_local', $localized_vars );

		return true;
	}
	/**
	 * Removes enqueued styles in the customiser.
	 */
	public function wb_email_customizer_remove_theme_styles() {
		global $wp_styles;
		$wp_styles->queue = array();
	}
}
