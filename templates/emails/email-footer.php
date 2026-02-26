<?php
/**
 * Email Footer
 *
 * Custom email footer template with social media links support.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Email_Customizer_For_Woocommerce\Templates\Emails
 * @version 10.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email = $email ?? null;
?>
																	</div>
																</td>
															</tr>
														</table>
														<!-- End Content -->
													</td>
												</tr>
											</table>
											<!-- End Body -->
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td align="center" valign="top">
								<!-- Footer -->
								<table border="0" cellpadding="10" cellspacing="0" width="100%" id="template_footer" role="presentation">
									<tr>
										<td valign="top">
											<table border="0" cellpadding="10" cellspacing="0" width="100%" role="presentation">
												<tr>
													<td colspan="2" valign="middle" id="credit">
														<?php
														$email_footer_text = get_option( 'woocommerce_email_footer_text' );
														/**
														 * This filter is documented in templates/emails/email-styles.php
														 *
														 * @since 9.6.0
														 */
														if ( apply_filters( 'woocommerce_is_email_preview', false ) ) {
															$text_transient    = get_transient( 'woocommerce_email_footer_text' );
															$email_footer_text = false !== $text_transient ? $text_transient : $email_footer_text;
														}
														echo wp_kses_post(
															wpautop(
																wptexturize(
																	/**
																	 * Provides control over the email footer text.
																	 *
																	 * @since 4.0.0
																	 *
																	 * @param string $email_footer_text
																	 */
																	apply_filters( 'woocommerce_email_footer_text', $email_footer_text, $email )
																)
															)
														);
														?>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<!-- End Footer -->
							</td>
						</tr>
					</table>
				</div>
			</td>
			<td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
		</tr>
	</table>
</body>
</html>
