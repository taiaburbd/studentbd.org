<?php
/**
 * Settings Page
 *
 * @package     Restrict Content
 * @subpackage  Settings
 * @copyright   Copyright (c) 2017, Restrict Content Pro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Render settings page
 *
 * @return void
 */
function rc_settings_page() {
	global $rc_options;

	?>
	<div class="wrap">
		<div id="upb-wrap" class="upb-help">

			<h2><?php _e( 'Restrict Content Settings', 'restrict-content' ); ?></h2>

			<div class="rcp-settings-wrap">

				<div class="rcp-settings-content">

					<?php
					if ( ! isset( $_REQUEST['updated'] ) ) {
						$_REQUEST['updated'] = false;
					}
					if ( false !== $_REQUEST['updated'] ) {
						?>
						<div class="updated fade">
							<p><strong><?php _e( 'Options saved', 'restrict-content' ); ?></strong></p>
						</div>
						<?php
					}
					?>

					<form method="post" action="options.php">

						<?php settings_fields( 'rc_settings_group' ); ?>

						<table class="form-table">
							<tr valign="top">
								<th colspan="2"><strong><?php _e( 'Short Code Messages', 'restrict-content' ); ?></strong></th>
							</tr>
							<tr valign="top">
								<th><?php _e( 'Restricted Message', 'restrict-content' ); ?></th>
								<td>
									<input
                                            id="rc_settings[shortcode_message]"
                                            class="large-text"
                                            name="rc_settings[shortcode_message]"
                                            type="text"
                                            value="<?php echo ! empty( $rc_options['shortcode_message'] ) ? esc_attr( $rc_options['shortcode_message'] ) : __( 'You do not have access to this post.', 'restrict-content' ); ?>"/><br/>
									<label class="description" for="rc_settings[shortcode_message]"><?php _e( 'When using the [restrict ... ] .... [/restrict] Short Code, this is the message displayed when a user does not have the appropriate permissions.', 'restrict-content' ); ?></label><br/>
									<small style="color: #666;"><?php _e( 'The <strong>{userlevel}</strong> tag will be automatically replaced with the permission level needed.', 'restrict-content' ); ?></small>
								</td>
							</tr>
							<tr>
								<th colspan="2">
									<strong><?php _e( 'User Level Restriction Messages', 'restrict-content' ); ?></strong>
                                </th>
							</tr>
							<tr valign="top">
								<th><?php _e( 'Administrators', 'restrict-content' ); ?></th>
								<td>
									<input id="rc_settings[administrator_message]" class="large-text" name="rc_settings[administrator_message]" type="text" value="<?php echo ! empty( $rc_options['administrator_message'] ) ? esc_attr( $rc_options['administrator_message'] ) : __( 'This content is for Administrator Users.', 'restrict-content' ); ?>"/><br/>
									<label class="description" for="rc_settings[administrator_message]"><?php _e( 'Message displayed when a user does not have permission to view Administrator restricted content', 'restrict-content' ); ?></label><br/>
								</td>
							</tr>
							<tr valign="top">
								<th><?php _e( 'Editors', 'restrict-content' ); ?></th>
								<td>
									<input id="rc_settings[editor_message]" class="large-text" name="rc_settings[editor_message]" type="text" value="<?php echo ! empty( $rc_options['editor_message'] ) ? esc_attr( $rc_options['editor_message'] ) : __( 'This content is for Editor Users.', 'restrict-content' ); ?>"/><br/>
									<label class="description" for="rc_settings[editor_message]"><?php _e( 'Message displayed when a user does not have permission to view Editor restricted content', 'restrict-content' ); ?></label><br/>
								</td>
							</tr>
							<tr valign="top">
								<th><?php _e( 'Authors', 'restrict-content' ); ?></th>
								<td>
									<input id="rc_settings[author_message]" class="large-text" name="rc_settings[author_message]" type="text" value="<?php echo ! empty( $rc_options['author_message'] ) ? esc_attr( $rc_options['author_message'] ) : __( 'This content is for Author Users.', 'restrict-content' ); ?>"/><br/>
									<label class="description" for="rc_settings[author_message]"><?php _e( 'Message displayed when a user does not have permission to view Author restricted content', 'restrict-content' ); ?></label><br/>
								</td>
							</tr>
							<tr valign="top">
								<th><?php _e( 'Contributors', 'restrict-content' ); ?></th>
								<td>
									<input id="rc_settings[contributor_message]" class="large-text" name="rc_settings[contributor_message]" type="text" value="<?php echo ! empty( $rc_options['contributor_message'] ) ? esc_attr( $rc_options['contributor_message'] ) : __( 'This content is for Contributor Users.', 'restrict-content' ); ?>"/><br/>
									<label class="description" for="rc_settings[contributor_message]"><?php _e( 'Message displayed when a user does not have permission to view Contributor restricted content', 'restrict-content' ); ?></label><br/>
								</td>
							</tr>
							<tr valign="top">
								<th><?php _e( 'Subscribers', 'restrict-content' ); ?></th>
								<td>
									<input id="rc_settings[subscriber_message]" class="large-text" name="rc_settings[subscriber_message]" type="text" value="<?php echo ! empty( $rc_options['subscriber_message'] ) ? esc_attr( $rc_options['subscriber_message'] ) : __( 'This content is for Subscribers Users.', 'restrict-content' ); ?>"/><br/>
									<label class="description" for="rc_settings[subscriber_message]"><?php _e( 'Message displayed when a user does not have permission to view Subscriber restricted content', 'restrict-content' ); ?></label><br/>
								</td>
							</tr>
                            <tr>
                                <th><?php _e( 'Restrict Content Welcome', 'restrict-content' ); ?></th>
                                <td>
                                    <label><a href="admin.php?page=restrict-content-welcome"><?php _e( 'View the Restrict Content Welcome page again for some helpful tips and links!', 'restrict-content' ) ?></label>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e( 'Switch Restrict Content Version', 'restrict-content' ); ?></th>
                                <td>
                                    <input
                                            type="hidden"
                                            name="rcp_settings_nonce"
                                            id="rcp_settings_nonce"
                                            value="<?php echo wp_create_nonce( 'rc_process_legacy_nonce' ); ?>"
                                    />
                                    <label for="understand">I understand that once I upgrade, I CANNOT downgrade back to legacy</label>
                                    <input
                                    type="checkbox"
                                    id="restrict_content_legacy_switch_agree"
                                    name="understand"
                                    value="understand"
                                    onchange="enableUpgradeButton()"

                                    />
                </br></br>
                                    <input
                                            type="button"
                                            id="restrict_content_legacy_switch"
                                            class="button-primary btn-success"
                                            value="<?php _e( 'Use the new version of Restrict Content?', 'restrict-content' ); ?>"
                                            disabled="true"
                                            
                                    />
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e( 'Why Upgrade?', 'restrict-content' ); ?></th>
                                <td><?php
                                    printf(
                                        __( 'Restrict Content 3 includes a robust new suite of features, including creating memberships and collectings payments. After upgrading, your content will remain restricted, and the associated restricted content messages will update to the new default restriction message. <a href="%s">Learn More</a>', 'restrict-content' ),
                                        'https://help.ithemes.com/hc/en-us/articles/4411587693211'
                                    )
                                ?></td>
                            </tr>
						</table>

						<!-- save the options -->
						<p class="submit">
							<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'restrict-content' ); ?>"/>
						</p>
					</form>

					

				</div>

			</div>

		</div><!--end sf-wrap-->

	</div><!--end wrap-->

	<?php
}

/**
 * Register plugin settings
 *
 * @return void
 */
function rc_register_settings() {

	register_setting( 'rc_settings_group', 'rc_settings' );
}
add_action( 'admin_init', 'rc_register_settings' );

/**
 * Register plugin settings
 *
 * @return void
 */
function rc_settings_menu() {
	add_menu_page( __( 'Restrict Content Settings', 'restrict-content' ), __( 'Restrict', 'restrict-content'), 'manage_options', 'restrict-content-settings', 'rc_settings_page' );
	$rc_settings_page = add_submenu_page( 'restrict-content-settings', __( 'Settings', 'restrict-content' ), __( 'Settings', 'restrict-content' ), 'manage_options', 'restrict-content-settings', 'rc_settings_page' );
	$rc_why_go_pro_page = add_submenu_page( 'restrict-content-settings', __( 'Why Go Pro', 'restrict-content' ), __( 'Why go Pro', 'restrict-content' ), 'manage_options', 'rcp-why-go-pro', 'rc_why_go_pro_page' );
	$rc_help_page = add_submenu_page( 'restrict-content-settings', __( 'Help', 'restrict-content' ), __( 'Help', 'restrict-content' ), 'manage_options', 'rcp-need-help', 'rc_need_help_page' );
	$rc_welcome_page = add_submenu_page( null, __( 'Welcome', 'restrict-content'), __( 'Welcome', 'restrict-content' ), 'manage_options', 'restrict-content-welcome', 'rc_welcome_page' );
}
add_action( 'admin_menu', 'rc_settings_menu');

/**
 * Render Why Go Pro Page
 *
 * @return void
 */
function rc_why_go_pro_page() {
    ?>
    <div class="wrap">
        <div class="rcp-why-go-pro-wrap">
            <img class="restrict-content-logo" src="<?php echo esc_url( RC_PLUGIN_URL . '/includes/assets/images/restrict_content_logo.svg' ); ?>" >
            <div class="rcp-go-pro-color-container">
                <div class="rcp-why-go-pro-inner-wrapper">
                    <div class="rcp-top-header">
                        <h1>
                            <?php _e( 'Why Go Pro?', 'restrict-content' ); ?></h1>
                        
                    </div>
                    <h2><?php _e( 'Grow Your Sales with Premium Features and Add-ons in Restrict Content PRO', 'restrict-content' ); ?></h2>
                    <div class="rcp-pro-features-container">
                        <!-- MEMBERSHIP LEVELS FEATURE -->
                        <a href="https://restrictcontentpro.com/tour/features/subscription-levels/">
                            <div class="rcp-membership-levels feature">
                                <img src="<?php echo esc_url( RC_PLUGIN_URL . 'includes/assets/images/memb-levels.svg' ); ?>" >
                                <div class="feature-text">
                                    <h3><?php _e( 'Membership Levels', 'restrict-content' ); ?></h3>
                                    <p><?php _e( 'Offer multiple membership levels with unique prices and restrictions.', 'restrict-content' ); ?></p>
                                </div>
                            </div>
                        </a>
                        <!-- COLLECT PAYMENTS FEATURE -->
                        <a href="https://restrictcontentpro.com/tour/payment-gateways/">
                            <div class="rcp-collect-payments feature">
                                <img src="<?php echo esc_url( RC_PLUGIN_URL . 'includes/assets/images/collect-payments.svg' ); ?>" >
                                <div class="feature-text">
                                    <h3><?php _e( 'Collect Payments', 'restrict-content' ); ?></h3>
                                    <p><?php _e( "Collect recurring payments is easy with Restrict Content Pro's simple payment gateway integrations.", 'restrict-content' ); ?></p>
                                </div>
                            </div>
                        </a>
                        <!-- CUSTOMER DASHBOARD FEATURE -->
                        <a href="https://restrictcontentpro.com/tour/features/">
                            <div class="rcp-customer-dashboard feature">
                                <img src="<?php echo esc_url( RC_PLUGIN_URL . 'includes/assets/images/customer-dash.svg' ); ?>" >
                                <div class="feature-text">
                                    <h3><?php _e( 'Customer Dashboard', 'restrict-content' ); ?></h3>
                                    <p><?php _e( 'Let your members easily view and manage their account details.', 'restrict-content' ); ?></p>
                                </div>
                            </div>
                        </a>
                        <!-- MARKETING INTEGRATION FEATURE -->
                        <a href="https://restrictcontentpro.com/add-ons/pro/">
                            <div class="rcp-marketing-integration feature">
                                <img src="<?php echo esc_url( RC_PLUGIN_URL . 'includes/assets/images/mkt-integration.svg' ); ?>" >
                                <div class="feature-text">
                                    <h3><?php _e( 'Marketing Integration', 'restrict-content' ); ?></h3>
                                    <p><?php _e( 'Subscribe members to your Mailchimp, AWeber, ConvertKit, etc., mailing lists.', 'restrict-content' ); ?></p>
                                </div>
                            </div>
                        </a>
                        <!-- GROUP ACCOUNTS FEATURE -->
                        <a href="https://restrictcontentpro.com/downloads/group-accounts/">
                            <div class="rcp-group-accounts feature">
                                <img src="<?php echo esc_url( RC_PLUGIN_URL . 'includes/assets/images/group-acct.svg' ); ?>" >
                                <div class="feature-text">
                                    <h3><?php _e( 'Group Accounts', 'restrict-content' ); ?></h3>
                                    <p><?php _e( 'Sell enterprise or group memberships with multiple sub accounts.', 'restrict-content' ); ?></p>
                                </div>
                            </div>
                        </a>
                        <!-- DRIP CONTENT FEATURE -->
                        <a href="https://restrictcontentpro.com/downloads/drip-content/">
                            <div class="rcp-drip-content feature">
                                <img src="<?php echo esc_url( RC_PLUGIN_URL . 'includes/assets/images/drip-content.svg' ); ?>" >
                                <div class="feature-text">
                                    <h3><?php _e( 'Drip Content', 'restrict-content' ); ?></h3>
                                    <p><?php _e( 'Time-release content to new members based on their start date.', 'restrict-content' ); ?></p>
                                </div>
                            </div>
                        </a>
                        <!-- OFFER DISCOUNTS FEATURE -->
                        <a href="https://restrictcontentpro.com/tour/features/discount-codes/">
                            <div class="rcp-offer-discounts feature">
                                <img src="<?php echo esc_url( RC_PLUGIN_URL . 'includes/assets/images/offer-discounts.svg' ); ?>" >
                                <div class="feature-text">
                                    <h3><?php _e( 'Offer Discounts', 'restrict-content' ); ?></h3>
                                    <p><?php _e( 'Attract new customers with special promotional codes that give them a discount on the purchase of a membership.', 'restrict-content' ); ?></p>
                                </div>
                            </div>
                        </a>
                        <!-- RESTRICT PAST CONTENT FEATURE -->
                        <a href="https://restrictcontentpro.com/downloads/restrict-past-content/">
                            <div class="rcp-restrict-past-content feature">
                                <img src="<?php echo esc_url( RC_PLUGIN_URL . 'includes/assets/images/restrict-content.svg' ); ?>" >
                                <div class="feature-text">
                                    <h3><?php _e( 'Restrict Past Content', 'restrict-content' ); ?></h3>
                                    <p><?php _e( "Restrict content published before a member's join date.", 'restrict-content' ); ?></p>
                                </div>
                            </div>
                        </a>
                        <!-- PREMIUM SUPPORT FEATURE -->
                        <div class="rcp-premium-support feature">
                            <img src="<?php echo esc_url( RC_PLUGIN_URL . 'includes/assets/images/premium-support.svg' ); ?>" >
                            <div class="feature-text">
                                <h3><?php _e( 'Premium Support', 'restrict-content' ); ?></h3>
                                <p><?php _e( 'Get help from our team of membership experts.', 'restrict-content' ); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="rcp-why-go-pro-buttons-container">
                        <a class="try-before-you-buy" href="https://restrictcontentpro.com/demo/">
                            <?php _e( 'Try Before You Buy', 'restrict-content' ); ?>
                        </a>
                        <a class="rcp-unlock-pro-features-add-ons" href="https://restrictcontentpro.com/pricing/">
                            <?php _e( 'Unlock Pro Features & Add-Ons', 'restrict-content' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function rc_welcome_page() {
    $current_user = wp_get_current_user();

	$rc_welcome_try_free_meta_nonce = wp_create_nonce( 'rc_welcome_try_free_meta_nonce' );
    ?>
    <div class="restrict-content-welcome-header">
        <img class="restrict-content-logo" src="<?php echo esc_url( RC_PLUGIN_URL . '/includes/assets/images/restrict_content_logo.svg' ); ?>" >
    </div>
    <div class="restrict-content-welcome-top-container">
        <div class="restrict-content-welcome-left-container">
            <h1 class="restrict-content-welcome-user">
                <?php
                    printf( __( 'Welcome %s!', 'restrict-content' ),
                        $current_user->first_name ?: $current_user->display_name
                    );
                ?>
            </h1>
            <div class="restrict-content-inner-container">
                <div class="restrict-content-welcome-body-container">
                    <div class="restrict-content-welcome-body restrict-content-container-section">
                        <h2 class="restrict-content-thanks-header"><?php _e( 'Thanks For Installing Restrict Content!', 'restrict-content' ); ?></h2>
                        <p class="restrict-content-thanks-message"><?php _e( 'Restrict Content is a simple WordPress membership plugin that gives you full control over who can and cannot view content on your WordPress site.', 'restrict-content' ); ?></p>
                        <p class="restrict-content-thanks-message"><?php _e( 'Restrict access to your website based on user role so that your posts, pages, media, and custom post types become viewable by logged-in members only.', 'restrict-content' ); ?></p>
                    </div>
                    <div class="restrict-content-welcome-standing-rex">
                        <img src="<?php echo esc_url( RC_PLUGIN_URL . '/includes/assets/images/restrict-content-pro-rex-standing.png' ); ?>" >
                    </div>
                </div>
                <div class="restrict-content-welcome-body-container">
                    <div class="restrict-content-how-to-body restrict-content-container-section">
                        <h2><?php _e( 'How To Use Restrict Content', 'restrict-content' ); ?></h2>
                        <p class="restrict-content-how-to-message"><?php _e( "To restrict an entire post or page, simply select the user level you'd like to restrict the post or page to from the drop down menu added just below the post/page editor.", 'restrict-content' ); ?></p>
                        <p class="restrict-content-how-to-message"><?php _e( 'Accepted user-level values are:', 'restrict-content' ); ?></p>
                        <p>
                            * <?php _e( 'admin', 'restrict-content' ); ?><br>
                            * <?php _e( 'editor', 'restrict-content' ); ?><br>
                            * <?php _e( 'author', 'restrict-content' ); ?><br>
                            * <?php _e( 'subscriber', 'restrict-content' ); ?><br>
                            * <?php _e( 'contributor', 'restrict-content' ); ?>
                        </p>
                        <p class="restrict-content-how-to-message"><?php _e( "To restrict just a section of content within a post or page or to display registration forms, you can use shortcodes:", 'restrict-content' ); ?></p>
                        <ul>
                            <li><?php _e( 'Limit access to content with a shortcode. <span class="restrict-content-example-text">Example: [restrict] This content is limited to logged in users. [/restrict]</span>', 'restrict-content' ); ?></li>
                            <li><?php _e( 'Limit access to content based on user role. <span class="restrict-content-example-text">Example [restrict userlevel="editor"] Only editors and higher can see this contents.[/restrict]</span>', 'restrict-content' ); ?></li>
                            <li><?php _e( 'Limit access to content if user is not logged in. <span class="restrict-content-example-text">Example: [not_logged_in] This content is only shown to non-logged-in users.[/not_logged_in]</span>', 'restrict-content' ); ?></li>
                            <li><?php _e( 'Display a registration form for new accounts on any page of you website with <span class="restrict-content-example-text">[register_form].</span>', 'restrict-content' ); ?></li>
                            <li><?php _e( 'Display a login form for existing users on any page of your website with <span class="restrict-content-example-text">[login_form]</span>.', 'restrict-content' ); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="restrict-content-welcome-body-container">
                    <div class="restrict-content-helpful-resources restrict-content-container-section">
                        <h2><?php _e( 'Helpful Resources', 'restrict-content' ); ?></h2>
                        <div class="restrict-content-resource-container">
                            <h3><?php _e( 'Getting Started with Restrict Content', 'restrict-content' ); ?></h3>
                            <p><?php _e( 'Learn how to start restricting content on your website.', 'restrict-content' ); ?></p>
                        </div>
                        <div class="restrict-content-resource-container">
                            <h3><?php _e( 'Help Center', 'restrict-content' ); ?></h3>
                            <p>
				                <?php
				                printf(
					                __( 'Our <a href="%s">Help Center</a> will help you become a Restrict Content & Restrict Content Pro expert.', 'restrict-content' ),
					                'https://help.ithemes.com'
				                );
				                ?>
                            </p>
                        </div>
                        <div class="restrict-content-resource-container">
                            <h3><?php _e( 'Need More Control Over Your Content & Memberships?', 'restrict-content' ); ?></h3>
                            <p><?php _e( 'Check out Restrict Content Pro and our suite of add-ons for building awesome membership websites.', 'restrict-content' ); ?> <br><a href="https://restrictcontentpro.com/add-ons/">https://restrictcontentpro.com/add-ons/</a></p>
                        </div>
                        <div class="restrict-content-resource-container">
                            <h3><?php _e( 'Introduction to Restrict Content Pro', 'restrict-content' ); ?></h3>
                            <p><?php _e( 'Get a full overview of Restrict Content Pro and dive into several of its key features.', 'restrict-content' ) ?><br><a href="https://training.ithemes.com/webinar/introduction-to-restrict-content-pro/">https://training.ithemes.com/webinar/introduction-to-restrict-content-pro/</a></p>
                        </div>
                        <div class="restrict-content-resource-container">
                            <h3><?php _e( 'Try Restrict Content Pro for Free', 'restrict-content' ); ?></h3>
                            <p><?php _e( 'Give Restrict Content Pro a spin, along with the full suite of add-ons, before buying a subscription.', 'restrict-content' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="restrict-content-welcome-right-container">
            <div class="restrict-content-welcome-advertisement">
                <div class="logo">
                    <img class="restrict-content-welcome-advertisement-logo" src="<?php echo esc_url( RC_PLUGIN_URL . '/includes/assets/images/restrict-content-pro-logo-vertical-blue-black.svg' ); ?>" >
                </div>
                <div class="restrict-content-welcome-try-for-free">
                    <p><?php _e( 'Try a Demo!', 'restrict-content' ); ?></p>
                </div>
                <div class="restrict-content-welcome-advertisement-content">
                    <p><?php _e( 'Lock away your exclusive content. Give access to valued members.', 'restrict-content' ); ?></p>
                    <p class="rcp-highlight"><?php _e( 'A Full-Featured Powerful Membership Solution for WordPress.', 'restrict-content' ); ?></p>
                    <p><?php _e( 'Give Restrict Content Pro a spin, along with the full suite of add-ons. Enter your email and we’ll automatically send you a link to a personal WordPress demo site, no strings attached!', 'restrict-content' ); ?></p>
                </div>
                <div class="restrict-content-welcome-advertisement-form">
                    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="restrict_content_try_free">
                        <input type="hidden" name="action" value="restrict_content_try_free">
                        <input type="hidden" name="rc_welcome_try_free_meta_nonce" value="<?php echo $rc_welcome_try_free_meta_nonce; ?>" >
                        <input type="hidden" name="source_page" value="welcome_page">
                        <input type="email" name="try_email_address" id="try_email_address" placeholder="Email Address">
                        <input type="submit" class="restrict-content-welcome-button" value="<?php _e( 'Get Your Demo!', 'restrict-content' ); ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}

add_action( 'in_admin_header', function() {
    if ( ! empty( $_GET['page'] ) && $_GET['page'] === 'rcp-why-go-pro' ) {
	    remove_all_actions( 'all_admin_notices' );
	    remove_all_actions( 'network_admin_notices' );
	    remove_all_actions( 'admin_notices' );
    }
});

function rc_need_help_page() {
    ?>
    <div class="restrict-content-welcome-header">
        <img class="restrict-content-logo" src="<?php echo esc_url( RC_PLUGIN_URL . '/includes/assets/images/restrict_content_logo.svg' ); ?>" >
    </div>
    <div class="restrict-content-welcome-top-container">
        <div class="restrict-content-welcome-left-container">
            <h1 class="restrict-content-welcome-user"><?php _e( 'Need Help?', 'restrict-content' ); ?></h1>
            <p>
                <?php
                    printf(
                        __('Are you new to Restrict Content? Check out the Getting Started with <a href="%s">Restrict Content guide.</a>', 'restrict-content' ),
	                    'https://help.ithemes.com/hc/en-us/articles/4402387794587-Getting-Started-with-Restrict-Content'
                    );
                ?>
            </p>
            <div class="restrict-content-inner-container">
                <a class="restrict-content-section-link" href="https://help.ithemes.com">
                    <div class="restrict-content-help-section">
                        <div class="restrict-content-help-section-icon">
                            <div id="restrict-content-help-center" class="restrict-content-help-section-trouble-shooting-image"></div>
                        </div>
                        <div class="restrict-content-help-section-content">
                            <h3><?php _e( 'Help Center', 'restrict-content' ); ?></h3>
                            <p><?php _e( 'Our Help Center is filled with articles to help you learn more about using Restrict Content and Restrict Content Pro.', 'restrict-content' ); ?></p>
                        </div>
                        <img class="restrict-content-help-section-arrow hidden" style="display: none;" src="<?php echo esc_url( RC_PLUGIN_URL . '/includes/assets/images/purple-arrow-right.svg' ); ?>" >
                    </div>
                </a>
                <a class="restrict-content-section-link" href="https://help.ithemes.com/hc/en-us/articles/115003073433-Checking-for-a-Conflict">
                    <div id="restrict-content-troubleshooting-link" class="restrict-content-help-section">
                        <div class="restrict-content-help-section-icon">
                            <div id="restrict-content-trouble-shooting" class="restrict-content-help-section-trouble-shooting-image"></div>
                        </div>
                        <div class="restrict-content-help-section-content">
                            <h3><?php _e( 'Troubleshooting', 'restrict-content' ); ?></h3>
                            <p><?php _e( 'If you run into any errors or things aren’t working as expected, the first step in troubleshooting is to check for a plugin or theme conflict.', 'restrict-content' ); ?></p>
                        </div>
                        <img class="restrict-content-help-section-arrow hidden" style="display: none;" src="<?php echo esc_url( RC_PLUGIN_URL . '/includes/assets/images/purple-arrow-right.svg' ); ?>" >
                    </div>
                </a>
                <a class="restrict-content-section-link" href="https://wordpress.org/support/plugin/restrict-content/">
                    <div id="restrict-content-support-link" class="restrict-content-help-section">
                        <div class="restrict-content-help-section-icon">
                            <div id="restrict-content-support-forum" class="restrict-content-help-section-trouble-shooting-image"></div>
                        </div>
                        <div class="restrict-content-help-section-content">
                            <h3><?php _e( 'Support Forum', 'restrict-content' ); ?></h3>
                            <p><?php _e( 'If you are still having trouble after checking for a conflict, feel free to start a new thread on the Restrict Content support forum.', 'restrict-content' ); ?></p>
                        </div>
                        <img class="restrict-content-help-section-arrow hidden" style="display:none;" src="<?php echo esc_url( RC_PLUGIN_URL . '/includes/assets/images/purple-arrow-right.svg' ); ?>" >
                    </div>
                </a>
                <div class="restrict-content-premium-support">
                    <div class="premium-support-content">
                        <h3><?php _e( 'Get Premium Support', 'restrict-content' ); ?></h3>
                        <p>
                            <?php
                                printf(
                                    __( 'Purchase any <a href="%s">Restrict Content Pro subscription</a> and get access to our ticketed support system. Our team of experts is ready to help!', 'restrict-content' ),
                                    'https://restrictcontentpro.com/pricing/'
                                );
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="restrict-content-welcome-right-container">
            <div class="restrict-content-welcome-advertisement">
                <div class="logo">
                    <img class="restrict-content-welcome-advertisement-logo" src="<?php echo esc_url( RC_PLUGIN_URL . '/includes/assets/images/restrict-content-pro-logo-vertical-blue-black.svg' ); ?>" >
                </div>
                <div class="restrict-content-welcome-try-for-free">
                    <p><?php _e( 'Try a Demo!', 'restrict-content' ); ?></p>
                </div>
                <div class="restrict-content-welcome-advertisement-content">
                    <p><?php _e( 'Lock away your exclusive content. Give access to valued members.', 'restrict-content' ); ?></p>
                    <p class="rcp-highlight"><?php _e( 'A Full-Featured Powerful Membership Solution for WordPress.', 'restrict-content' ); ?></p>
                    <p><?php _e( 'Give Restrict Content Pro a spin, along with the full suite of add-ons. Enter your email and we’ll automatically send you a link to a personal WordPress demo site, no strings attached!', 'restrict-content' ); ?></p>
                </div>
                <div class="restrict-content-welcome-advertisement-form">
                    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="restrict_content_try_free">
                        <input type="hidden" name="action" value="restrict_content_try_free">
                        <input type="hidden" name="rc_welcome_try_free_meta_nonce" value="<?php echo wp_create_nonce( 'rc_welcome_try_free_meta_nonce' ); ?>" >
                        <input type="hidden" name="source_page" value="help_page">
                        <input type="email" name="try_email_address" id="try_email_address" placeholder="Email Address">
                        <input type="submit" class="restrict-content-welcome-button" value="<?php _e( 'Get Your Demo!', 'restrict-content' ); ?>">
                    </form>
                </div>
            </div>
            <div class="restrict-content-unlock-premium-features">
                <h3><?php _e( 'Unlock Premium Features', 'restrict-content' ); ?></h3>
                <p><?php _e( 'Go beyond the basics with premium features & support.', 'restrict-content' ); ?></p>
                <div class="tabs">
                    <div class="tablist" role="tablist" aria-label="<?php esc_attr_e( 'Pricing Plans', 'restrict-content' ); ?>">

                        <button role="tab" aria-selected="true" aria-controls="1sitetab" id="1site">
                            <?php _e( '1 Site', 'restrict-content' ); ?>
                        </button>
                        <button role="tab" aria-selected="false" aria-controls="10sitetab" id="10site" tabindex="-1">
                            <?php _e( '10 Sites', 'restrict-content' ); ?>
                        </button>
                        <button role="tab" aria-selected="false" aria-controls="unlimitedtab" id="unlimited" tabindex="-1">
                            <?php _e( 'Unlimited', 'restrict-content' ); ?>
                        </button>
                    </div>
                    <div class="tabpanel" tabindex="0" role="tabpanel" id="1sitetab" aria-labelledby="1site">
                        <h4><?php _e( '$99', 'restrict-content' ); ?></h4>
                        <p><?php _e( 'Includes updates & support for one year.', 'restrict-content' ); ?></p>
                    </div>
                    <div class="tabpanel" tabindex="0" role="tabpanel" id="10sitetab" aria-labelledby="10site" hidden="">
                        <h4><?php _e( '$149', 'restrict-content' ); ?></h4>
                        <p><?php _e( 'Includes updates & support for one year.', 'restrict-content' ); ?></p>
                    </div>
                    <div class="tabpanel" tabindex="0" role="tabpanel" id="unlimitedtab" aria-labelledby="unlimited" hidden="">
                        <h4><?php _e( '$249', 'restrict-content' ); ?></h4>
                        <p><?php _e( 'Includes updates & support for one year.', 'restrict-content' ); ?></p>
                    </div>
                </div>
                <a href="https://restrictcontentpro.com/pricing/" class="go-pro-now"><?php _e( 'Go Pro Now', 'restrict-content' ); ?></a>
                <p class="whats-included"><a href="https://restrictcontentpro.com/why-go-pro/"><?php _e( "What's included with Pro?", 'restrict-content' ); ?></a></p>
            </div>
        </div>
    </div>
    <?php
}

function rc_screen_options() {

}

function restrict_content_admin_try_free_success() {
	if ( ! empty( $_GET['message'] ) && ! empty( $_GET['page'] ) && $_GET['page'] === 'rcp-need-help') {
		if ( $_GET['message'] === 'success' ) {
			?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Email Sent Successfully.', 'restrict-content' ); ?></p>
            </div>
			<?php
		} else if ( $_GET['message'] === 'failed' ) {
			?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Unable to send email.', 'restrict-content' ); ?></p>
            </div>
			<?php
		}
	} else if ( ! empty( $_GET['message'] ) && ! empty( $_GET['page'] ) && $_GET['page'] === 'restrict-content-welcome' ) {
		if ( $_GET['message'] === 'success' ) {
			?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Email Sent Successfully.', 'restrict-content' ); ?></p>
            </div>
			<?php
		} else if ( $_GET['message'] === 'failed' ) {
			?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Unable to send email.', 'restrict-content' ); ?></p>
            </div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'restrict_content_admin_try_free_success' );

function restrict_content_admin_try_free () {

	if( isset( $_POST['rc_welcome_try_free_meta_nonce'] ) && wp_verify_nonce( $_POST['rc_welcome_try_free_meta_nonce'], 'rc_welcome_try_free_meta_nonce') ) {

        $body = array(
            'template_name' => 'rcp-demo-delivery',
            'email' => $_POST['try_email_address']
        );

		$fields = array(
			'method'        => 'POST',
			'body'  => json_encode( $body )
		);

        $response = wp_remote_request( 'https://api.ithemes.com/email/send', $fields );

        if ( ! is_wp_error( $response ) && $_POST['source_page'] === 'welcome_page' ) {
	        wp_redirect( add_query_arg( 'message', 'success', admin_url( 'admin.php?page=restrict-content-welcome' ) ) );
        } else if ( ! is_wp_error( $response ) && $_POST['source_page'] === 'help_page' ) {
	        wp_redirect( add_query_arg( 'message', 'success', admin_url( 'admin.php?page=rcp-need-help' ) ) );
        } else {
	        wp_redirect( add_query_arg( 'message', 'failed', admin_url( 'admin.php?page=rcp-need-help' ) ) );
        }
    }
}

add_action( 'admin_post_restrict_content_try_free', 'restrict_content_admin_try_free' );
