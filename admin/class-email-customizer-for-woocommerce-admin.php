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

		if ( isset( $_GET[ $this->email_trigger ] ) ) {
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

		add_submenu_page( 'woocommerce', __( 'Woocommerce Email Customizer', 'email-customizer-for-woocommerce' ), __( 'WC Email Customizer', 'email-customizer-for-woocommerce' ), 'manage_woocommerce', $this->plugin_name, array( $this, 'wb_email_customizer_admin_page' ) );
		$submenu['woocommerce'][2][2] = esc_url_raw( $url );
	}

	/**
	 * Added admin page.
	 *
	 * @since   1.0
	 */
	public function wb_email_customizer_admin_page() {
		require_once 'partials/email-customizer-for-woocommerce-admin-display.php';
	}
	/**
	 * Added Customizer Sections.
	 *
	 * @since   1.0
	 * @param string $wp_customize Get a Customizer Section.
	 */
	public function wb_email_customizer_add_sections( $wp_customize ) {

		$wp_customize->add_panel(
			'wc_email_header',
			array(
				'title'      => __( 'Email Customizer', 'email-customizer-for-woocommerce' ),
				'capability' => 'edit_theme_options',
				'priority'   => 10,
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
	/**
	 * Added Customizer Controls.
	 *
	 * @since   1.0
	 * @param string $wp_customize Get a Customizer Section.
	 */
	public function wb_email_customizer_add_controls( $wp_customize ) {

		/**
		 * Email Header.
		 */
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
					'section'  => 'wc_email_body',
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
					'label'    => __( 'Text Color', 'eemail-customizer-for-woocommerce' ),
					'priority' => 70,
					'section'  => 'wc_email_body',
					'settings' => 'woocommerce_email_body_text_color',
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
			new WP_Customize_Control(
				$wp_customize,
				'wc_email_width_control',
				array(
					'label'    => __( 'Email Width', 'email-customizer-for-woocommerce' ),
					'priority' => 130,
					'section'  => 'wc_email_body',
					'settings' => 'woocommerce_email_width',
					'type'     => 'select',
					'choices'  => array(
						'500' => __( 'Narrow', 'email-customizer-for-woocommerce' ),
						'600' => __( 'Default', 'email-customizer-for-woocommerce' ),
						'700' => __( 'Wide', 'email-customizer-for-woocommercer' ),
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
				'section'     => 'wc_email_body',
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
				'section'     => 'wc_email_body',
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

	}
	/**
	 * Show only our email settings in the preview
	 *
	 * @since 1.0.0
	 */
	public function control_filter( $active, $control ) {
		if ( in_array( $control->section, array( 'wc_email_header', 'wc_email_body', 'wc_email_footer', 'wc_email_send' ) ) ) {

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
				'default'   => '#fdfdfd',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_header_background_color',
			array(
				'type'      => 'option',
				'default'   => '#557da1',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_header_text_color',
			array(
				'type'      => 'option',
				'default'   => '#ffffff',
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
			'woocommerce_email_body_font_size',
			array(
				'type'      => 'option',
				'default'   => '12',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_setting(
			'woocommerce_email_rounded_corners',
			array(
				'type'      => 'option',
				'default'   => '6',
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

			$mailer = WC()->mailer();

			wp_head();

			ob_start();

			include EMAIL_CUSTOMIZER_FOR_WOOCOMMERCE_PLUGIN_PATH . '/admin/partials/email-customizer-for-woocommerce-admin-display.php';

			$message = ob_get_clean();

			$email_heading = __( 'HTML Email Template!', 'email-customizer-for-woocommerce' );

			$email = new WC_Email();

			$message = $email->style_inline( $mailer->wrap_message( $email_heading, $message ) );

			wp_footer();

			echo $message;
			exit;
		}

		return $wp_query;
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

		$bg_color = 'body, body > div, body > #wrapper > table > tbody > tr > td { background-color:' . get_option( 'woocommerce_email_background_color', '#f5f5f5' ) . '; }' . PHP_EOL;

		$body_bg_color = '#template_container { border-color:' . get_option( 'woocommerce_email_body_background_color', '#fdfdfd' ) . '; background-color:' . get_option( 'woocommerce_email_body_background_color', '#fdfdfd' ) . '; } #template_body, #template_body td, #body_content { background: transparent none; }' . PHP_EOL;

		$header_bg_color = '#template_header { background-color:' . get_option( 'woocommerce_email_header_background_color', '#557da1' ) . '; } #header_wrapper, #header_wrapper h1 { background: transparent none; }' . PHP_EOL;

		$header_text_color = '#template_header h1 { color:' . get_option( 'woocommerce_email_header_text_color', '#ffffff' ) . '; text-shadow:0 1px 0 ' . get_option( 'woocommerce_email_header_text_color', '#ffffff' ) . '; }' . PHP_EOL;

		$header_font_size = '#template_header h1 { font-size:' . get_option( 'woocommerce_email_header_font_size', '30' ) . 'px' . '; }' . PHP_EOL;

		$header_cell_padding = '#header_wrapper { padding:36px 48px !important; }' . PHP_EOL;

		$header_image = '#template_header_image { margin-left: auto; margin-right: auto; }' . PHP_EOL;

		$template_container_border = 'table, table th, table td { border:none; border-style:none; border-width:0; }' . PHP_EOL;

		$template_rounded_corners = '#template_container { border-radius:' . get_option( 'woocommerce_email_rounded_corners', '6' ) . 'px !important; } #template_header { border-radius:' . get_option( 'woocommerce_email_rounded_corners', '6' ) . 'px ' . get_option( 'woocommerce_email_rounded_corners', '6' ) . 'px 0 0 !important; } #template_footer { border-radius:0 0 ' . get_option( 'woocommerce_email_rounded_corners', '6' ) . 'px ' . get_option( 'woocommerce_email_rounded_corners', '6' ) . 'px !important; }' . PHP_EOL;

		$template_shadow = '#template_container { box-shadow:0 0 6px ' . get_option( 'woocommerce_email_box_shadow_spread', '1' ) . 'px rgba(0,0,0,0.2) !important; }' . PHP_EOL;

		$body_items_table = '#body_content_inner table { border-collapse: collapse; width:100%; }' . PHP_EOL;

		$body_text_color = '#template_body div, #template_body div p, #template_body h2, #template_body h3, #template_body table td, #template_body table th, #template_body table tr, #template_body table, #template_body table h3 { color:' . get_option( 'woocommerce_email_body_text_color', '#505050' ) . '; }' . PHP_EOL;

		$body_border_color = '#body_content_inner table td, #body_content_inner table th { border-color:' . get_option( 'woocommerce_email_body_text_color', '#505050' ) . '; border-width:1px; border-style:solid; text-align:left; }' . PHP_EOL;

		$addresses = '.addresses td { border:none !important; line-height:1.5; padding-left: 0 !important; padding-right: 12px !important; } .addresses td + td { padding-left: 12px !important; padding-right: 0 !important; }' . PHP_EOL;

		$body_font_size = '#template_body div, #template_body div p, #template_body h2, #template_body h3, #template_body table td, #template_body table th, #template_body table h3 { font-size:' . get_option( 'woocommerce_email_body_font_size', '12' ) . 'px' . '; }' . PHP_EOL;

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

		$styles .= PHP_EOL;
		$styles .= $template_container_border;
		$styles .= $bg_color;
		$styles .= $body_bg_color;
		$styles .= $header_bg_color;
		$styles .= $header_image;
		$styles .= $header_font_size;
		$styles .= $header_cell_padding;
		$styles .= $header_text_color;
		$styles .= $body_items_table;
		$styles .= $body_text_color;
		$styles .= $body_border_color;
		$styles .= $body_font_size;
		$styles .= $body_link_color;
		$styles .= $width;
		$styles .= $footer_text_color;
		$styles .= $footer_font_size;
		$styles .= $font_family;
		$styles .= $template_rounded_corners;
		$styles .= $template_shadow;
		$styles .= $addresses;

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
