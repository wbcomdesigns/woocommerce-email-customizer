<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 9.8.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( isset( $_GET['nonce'] ) && ! wp_verify_nonce( wp_unslash( $_GET['nonce'] ), '_wc_email_customizer_send_email_nonce' ) ) {
    $position = isset( $_GET['woocommerce_email_header_image_placement'] )
        ? sanitize_text_field( wp_unslash( $_GET['woocommerce_email_header_image_placement'] ) )
        : get_option( 'woocommerce_email_header_image_placement' );

    $img = isset( $_GET['woocommerce_email_header_image'] )
        ? esc_url_raw( wp_unslash( $_GET['woocommerce_email_header_image'] ) )
        : get_option( 'woocommerce_email_header_image' );
} else {
    $position = get_option( 'woocommerce_email_header_image_placement' );
    $img      = get_option( 'woocommerce_email_header_image' );
}

if ( apply_filters( 'woocommerce_is_email_preview', false ) ) {
	$img_transient = get_transient( 'woocommerce_email_header_image' );
	$img           = false !== $img_transient ? $img_transient : $img;
}
									
$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );
	$template      = get_option( 'woocommerce_email_template' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<title><?php echo esc_html(get_bloginfo( 'name', 'display' )); ?></title>
	</head>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<table width="100%" id="outer_wrapper">
			<tr>
				<td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
				<td width="600">
					<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
						<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="inner_wrapper">
							<tr>
								<td align="center" valign="top">
									<?php
									
									
									/**
									 * This filter is documented in templates/emails/email-styles.php
									 *
									 * @since 9.6.0
									 */
									

									if ( $email_improvements_enabled && $img && 'outside' === $position ) :
										?>
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td id="template_header_image">
													<?php
														echo '<p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" /></p>';
													?>
												</td>
											</tr>
										</table>
									<?php else : ?>
											<?php
											if ( 'outside' === $position) {
												if($img){
													echo '<div id="template_header_image"><p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" /></p></div>';
												}else{
													echo esc_html( get_bloginfo( 'name', 'display' ) );
												}
											}
											?>
									<?php endif; ?>
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container">
										<tr>
											<td align="center" valign="top">
												<!-- Header -->
												<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header">
													<tr>
														<td id="header_wrapper">
															<?php
															if ( 'inside' === $position) {
																if($img){
																	echo '<div id="template_header_image"><p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" /></p></div>';
																}else{
																	echo esc_html( get_bloginfo( 'name', 'display' ) );
																}
															}else{
																echo '<h1>'. esc_html( $email_heading ). '</h1>';
															}
															?>
														</td>
													</tr>
												</table>
												<!-- End Header -->
											</td>
										</tr>
										<tr>
											<td align="center" valign="top">
												<!-- Body -->
												<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body">
													<tr>
														<td valign="top" id="body_content">
															<!-- Content -->
															<table border="0" cellpadding="20" cellspacing="0" width="100%">
																<tr>
																	<td valign="top" id="body_content_inner_cell">
																		<div id="body_content_inner">
																			<?php
																			if ( 'inside' === $position) {
																				echo '<h1>'. esc_html( $email_heading ). '</h1>';
																			}
																			?>
