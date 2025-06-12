<?php

class Email_Customizer_Subscription_Handler {

    /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
        if ($this->wb_email_customizer_is_subscriptions_active()) {
            add_action('init', array($this, 'wb_email_customizer_init_subscription_emails'));
        }
	}


    private function wb_email_customizer_is_subscriptions_active() {
        return class_exists('WC_Subscriptions');
    }
    
    public function wb_email_customizer_init_subscription_emails() {
        add_filter('wb_email_customizer_templates_for_preview', array($this, 'wb_email_customizer_add_subscription_emails'));
    }

    public function wb_email_customizer_add_subscription_emails($email_templates)
    {
        $email_templates[] = 'emails/admin-new-renewal-order.php';
        $email_templates[] = 'emails/admin-new-switch-order.php';
        $email_templates[] = 'emails/customer-processing-renewal-order.php';
        $email_templates[] = 'emails/customer-completed-renewal-order.php';
        $email_templates[] = 'emails/customer-on-hold-renewal-order.php';
        $email_templates[] = 'emails/customer-completed-switch-order.php';
        $email_templates[] = 'emails/customer-renewal-invoice.php';
        $email_templates[] = 'emails/cancelled-subscription.php';
        $email_templates[] = 'emails/expired-subscription.php';
        $email_templates[] = 'emails/on-hold-subscription.php';
 

        return $email_templates;
    }
}