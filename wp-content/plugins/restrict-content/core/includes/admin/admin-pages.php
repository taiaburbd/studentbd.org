<?php
/**
 * Admin Pages
 *
 * @package     Restrict Content Pro
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2017, Restrict Content Pro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Create admin menus and sub-menus
 *
 * @return void
 */
function rcp_settings_menu() {
	global $rcp_members_page, $rcp_customers_page, $rcp_subscriptions_page, $rcp_payments_page,
		   $rcp_settings_page, $rcp_export_page, $rcp_tools_page, $rcp_reminders_page, $restrict_content_pro_why_go_pro, 
		   $restrict_content_pro_help_page, $restrict_content_pro_welcome_page;

	// add settings page
	add_menu_page( __( 'Restrict Content Pro Settings', 'rcp' ), __( 'Restrict', 'rcp' ), 'rcp_view_members', 'rcp-members', 'rcp_members_page', 'dashicons-lock' );
	$rcp_members_page                   = add_submenu_page( 'rcp-members', __( 'Memberships', 'rcp' ), __( 'Memberships', 'rcp' ), 'rcp_view_members', 'rcp-members', 'rcp_members_page', 1 );
	$rcp_customers_page                 = add_submenu_page( 'rcp-members', __( 'Customers', 'rcp' ), __( 'Customers', 'rcp' ), 'rcp_view_members', 'rcp-customers', 'rcp_customers_page', 2 );
	$rcp_subscriptions_page             = add_submenu_page( 'rcp-members', __( 'Membership Levels', 'rcp' ), __( 'Membership Levels', 'rcp' ), 'rcp_view_levels', 'rcp-member-levels', 'rcp_member_levels_page', 3 );
	$rcp_payments_page                  = add_submenu_page( 'rcp-members', __( 'Payments', 'rcp' ), __( 'Payments', 'rcp' ), 'rcp_view_payments', 'rcp-payments', 'rcp_payments_page', 5 );
	$rcp_settings_page                  = add_submenu_page( 'rcp-members', __( 'Restrict Content Pro Settings', 'rcp' ), __( 'Settings', 'rcp' ),'rcp_manage_settings', 'rcp-settings', 'rcp_settings_page', 7 );
	$rcp_tools_page                     = add_submenu_page( 'rcp-members', __( 'Tools', 'rcp' ), __( 'Tools', 'rcp' ), 'rcp_manage_settings', 'rcp-tools', 'rcp_tools_page',  8 );
	$rcp_reminders_page                 = add_submenu_page( 'rcp-members', __( 'Subscription Reminder', 'rcp' ), __( 'Subscription Reminder', 'rcp' ), 'rcp_manage_settings', 'rcp-reminder', 'rcp_subscription_reminder_page', 11 );
	$restrict_content_pro_help_page     = add_submenu_page( 'rcp-members', __( 'Help', 'restrict-content' ), __( 'Help', 'restrict-content' ), 'manage_options', 'rcp-need-help', 'rc_need_help_page_redesign' );

	// If we are not in PRO include the Free menus.
	if( false === has_action('admin_menu','include_pro_pages') ) {
		$restrict_content_pro_why_go_pro    = add_submenu_page( 'rcp-members', __( 'Why Go Pro', 'restrict-content' ), __( 'Why go Pro', 'restrict-content' ), 'manage_options', 'rcp-why-go-pro', 'rc_why_go_pro_page_redesign' );
		$restrict_content_pro_welcome_page  = add_submenu_page( null, __( 'RCP Welcome', 'restrict-content'), __( 'RCP Welcome', 'restrict-content' ), 'manage_options', 'restrict-content-welcome', 'rc_welcome_page_redesign' );
	}
	else {
		$restrict_content_pro_welcome_page  = add_submenu_page( null, __( 'RCP Welcome', 'restrict-content'), __( 'RCP Welcome', 'restrict-content' ), 'manage_options', 'restrict-content-pro-welcome', 'rcp_welcome_page_redesign' );
	}



	// Backwards compatibility - link the old export page to the tools page.
	$rcp_export_page = $rcp_tools_page;

	// Remove the reminders page from the menu.
	add_action( 'admin_head', 'rcp_hide_reminder_page' );

	// Add "Restrict" submenu under each post type.
	foreach ( rcp_get_metabox_post_types() as $post_type ) {
		$post_type_details = get_post_type_object( $post_type );
		$url               = ( 'post' == $post_type ) ? 'edit.php' : 'edit.php?post_type=' . $post_type;
		$slug              = ( 'post' == $post_type ) ? 'rcp-restrict-post-type' : 'rcp-restrict-post-type-' . $post_type;
		$capability        = isset( $post_type_details->cap->edit_posts ) ? $post_type_details->cap->edit_posts : 'edit_posts';
		add_submenu_page( $url, __( 'Restrict Access', 'rcp' ), __( 'Restrict Access', 'rcp' ), $capability, $slug, 'rcp_restrict_post_type_page' );
	}

	if ( get_bloginfo('version') >= 3.3 ) {
		// load each of the help tabs
		add_action( "load-$rcp_members_page", "rcp_help_tabs" );
		add_action( "load-$rcp_customers_page", "rcp_help_tabs" );
		add_action( "load-$rcp_subscriptions_page", "rcp_help_tabs" );
		add_action( "load-$rcp_settings_page", "rcp_help_tabs" );
	}
	add_action( "load-$rcp_members_page", "rcp_screen_options" );
	add_action( "load-$rcp_customers_page", "rcp_screen_options" );
	add_action( "load-$rcp_subscriptions_page", "rcp_screen_options" );
	add_action( "load-$rcp_payments_page", "rcp_screen_options" );
	add_action( "load-$rcp_settings_page", "rcp_screen_options" );
	add_action( "load-$rcp_tools_page", "rcp_screen_options" );
}
add_action( 'admin_menu', 'rcp_settings_menu', 10, 2 );

/**
 * Determines whether or not the current page is an RCP admin page.
 *
 * @since 3.3.7
 * @return bool
 */
function rcp_is_rcp_admin_page() {

	$screen = get_current_screen();

	global $rcp_members_page, $rcp_customers_page, $rcp_subscriptions_page, $rcp_discounts_page, $rcp_payments_page, $rcp_reports_page, $rcp_settings_page, $rcp_help_page, $rcp_tools_page, $restrict_content_pro_welcome_page, $restrict_content_pro_help_page;
	$pages = array( $rcp_members_page, $rcp_customers_page, $rcp_subscriptions_page, $rcp_discounts_page, $rcp_payments_page, $rcp_reports_page, $rcp_settings_page, $rcp_tools_page, $rcp_help_page, $restrict_content_pro_welcome_page, $restrict_content_pro_help_page );

	// Include post types that support restrictions.
	if ( 'post' === $screen->base && ! empty( $screen->post_type ) && in_array( $screen->post_type, rcp_get_metabox_post_types() ) ) {
		$pages[] = $screen->id;
	}

	if( false !== strpos( $screen->id, 'rcp-restrict-post-type' ) ) {
		$pages[] = $screen->id;
	}

	$is_admin = in_array( $screen->id, $pages );

	/**
	 * Filters whether or not the current page is an RCP admin page.
	 *
	 * @param bool      $is_admin
	 * @param WP_Screen $screen
	 *
	 * @since 3.3.7
	 */
	return apply_filters( 'rcp_is_rcp_admin_page', $is_admin, $screen );

}

/**
 * Returns the URL to the memberships page.
 *
 * @param array $args Query args to add.
 *
 * @since 3.0
 * @return string
 */
function rcp_get_memberships_admin_page( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'page' => 'rcp-members'
	) );

	$sanitized_args = array();

	foreach ($args as $key => $value) {
		$sanitized_key   = urlencode( $key );
		$sanitized_value = urlencode( $value );

		$sanitized_args[ $sanitized_key ] = $sanitized_value;
	}

	$memberships_page = add_query_arg( $sanitized_args, admin_url(  'admin.php'  ) );

	return $memberships_page;

}

/**
 * Returns the URL to the customers page.
 *
 * @param array $args Query args to add.
 *
 * @since 3.0
 * @return string
 */
function rcp_get_customers_admin_page( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'page' => 'rcp-customers'
	) );

	$customers_page = add_query_arg( $args, admin_url(  'admin.php'  ) );

	return $customers_page;

}

function rc_why_go_pro_page_redesign() {
	?>
	<div class="wrap">
		<div class="rcp-why-go-pro-wrap">
			<img class="restrict-content-logo" src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/rc_logo_horizontal_black.svg' ); ?>" >
			<div class="rcp-go-pro-color-container">
				<div class="rcp-why-go-pro-inner-wrapper">
					<div class="rcp-top-header">
						<h1>
							<?php _e( 'Why Go Pro?', 'restrict-content' ); ?></h1>

					</div>
					<h2><?php _e( 'Grow Your Sales with Premium Features and Add-ons in Restrict Content PRO', 'restrict-content' ); ?></h2>
					<div class="rcp-pro-features-container">
						<!-- LIMIT NUMBER OF CONNECTIONS FEATURE -->
						<a href="https://restrictcontentpro.com/pricing/">
							<div class="rcp-limit-number-of-connections feature">
								<img src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/memb-levels.svg' ); ?>" >
								<div class="feature-text">
									<h3><?php _e( 'Limit Number of Connections', 'restrict-content' ); ?></h3>
									<p><?php _e( 'Prevent password sharing by limiting the number of simultaneous connections for each member.', 'restrict-content' ); ?></p>
								</div>
							</div>
						</a>
						<!-- REMOVE STRIPE FEE FEATURE -->
						<a href="https://restrictcontentpro.com/pricing">
							<div class="rcp-remove-stripe-fee feature">
								<img src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/collect-payments.svg' ); ?>" >
								<div class="feature-text">
									<h3><?php _e( 'Remove Stripe Fee', 'restrict-content' ); ?></h3>
									<p><?php _e( "Remove the 2% fee for processing Stripe payments by upgrading to Restrict Content Pro.", 'restrict-content' ); ?></p>
								</div>
							</div>
						</a>
						<!-- PRO EMAILS FEATURE -->
						<a href="https://restrictcontentpro.com/tour/features/member-emails/">
							<div class="rcp-pro-emails feature">
								<img src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/customer-dash.svg' ); ?>" >
								<div class="feature-text">
									<h3><?php _e( 'Pro Emails', 'restrict-content' ); ?></h3>
									<p><?php _e( 'Unlock email personalization and automatically member expiration & renewal email reminders.', 'restrict-content' ); ?></p>
								</div>
							</div>
						</a>
						<!-- MARKETING INTEGRATION FEATURE -->
						<a href="https://restrictcontentpro.com/add-ons/pro/">
							<div class="rcp-marketing-integration feature">
								<img src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/mkt-integration.svg' ); ?>" >
								<div class="feature-text">
									<h3><?php _e( 'Marketing Integration', 'restrict-content' ); ?></h3>
									<p><?php _e( 'Subscribe members to your Mailchimp, AWeber, ConvertKit, etc., mailing lists.', 'restrict-content' ); ?></p>
								</div>
							</div>
						</a>
						<!-- GROUP ACCOUNTS FEATURE -->
						<a href="https://restrictcontentpro.com/downloads/group-accounts/">
							<div class="rcp-group-accounts feature">
								<img src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/group-acct.svg' ); ?>" >
								<div class="feature-text">
									<h3><?php _e( 'Group Accounts', 'restrict-content' ); ?></h3>
									<p><?php _e( 'Sell enterprise or group memberships with multiple sub accounts.', 'restrict-content' ); ?></p>
								</div>
							</div>
						</a>
						<!-- DRIP CONTENT FEATURE -->
						<a href="https://restrictcontentpro.com/downloads/drip-content/">
							<div class="rcp-drip-content feature">
								<img src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/drip-content.svg' ); ?>" >
								<div class="feature-text">
									<h3><?php _e( 'Drip Content', 'restrict-content' ); ?></h3>
									<p><?php _e( 'Time-release content to new members based on their start date.', 'restrict-content' ); ?></p>
								</div>
							</div>
						</a>
						<!-- OFFER DISCOUNTS FEATURE -->
						<a href="https://restrictcontentpro.com/tour/features/discount-codes/">
							<div class="rcp-offer-discounts feature">
								<img src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/offer-discounts.svg' ); ?>" >
								<div class="feature-text">
									<h3><?php _e( 'Offer Discounts', 'restrict-content' ); ?></h3>
									<p><?php _e( 'Attract new customers with special promotional codes that give them a discount on the purchase of a membership.', 'restrict-content' ); ?></p>
								</div>
							</div>
						</a>
						<!-- RESTRICT PAST CONTENT FEATURE -->
						<a href="https://restrictcontentpro.com/downloads/restrict-past-content/">
							<div class="rcp-restrict-past-content feature">
								<img src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/restrict-content.svg' ); ?>" >
								<div class="feature-text">
									<h3><?php _e( 'Restrict Past Content', 'restrict-content' ); ?></h3>
									<p><?php _e( "Restrict content published before a member's join date.", 'restrict-content' ); ?></p>
								</div>
							</div>
						</a>
						<!-- PREMIUM SUPPORT FEATURE -->
						<div class="rcp-premium-support feature">
							<img src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/premium-support.svg' ); ?>" >
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

function rc_need_help_page_redesign() {
	?>
	<div class="restrict-content-welcome-header">
		<img class="restrict-content-logo" src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/rc_logo_horizontal_black.svg' ); ?>" >
	</div>
	<div class="restrict-content-welcome-top-container">
		<div class="restrict-content-welcome-left-container">
			<h1 class="restrict-content-welcome-user"><?php _e( 'Need Help?', 'restrict-content' ); ?></h1>
			<p>
				<?php
				printf(
					__('Are you new to Restrict Content? Check out the Getting Started with <a href="%s">Restrict Content guide.</a>', 'restrict-content' ),
					'https://help.ithemes.com/hc/en-us/sections/360008799334-Getting-Started'
				);
				?>
			</p>
			<div class="restrict-content-inner-container">
				<a class="restrict-content-section-link" href="https://restrictcontentpro.com/knowledgebase" target="_blank">
					<div class="restrict-content-help-section">
						<div class="restrict-content-help-section-icon">
							<div id="restrict-content-help-center" class="restrict-content-help-section-trouble-shooting-image"></div>
						</div>
						<div class="restrict-content-help-section-content">
							<h3><?php _e( 'Help Center', 'restrict-content' ); ?></h3>
							<p><?php _e( 'Our Help Center is filled with articles to help you learn more about using Restrict Content and Restrict Content Pro.', 'restrict-content' ); ?></p>
						</div>
						<img class="restrict-content-help-section-arrow hidden" style="display: none;" src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/purple-arrow-right.svg' ); ?>" >
					</div>
				</a>
				<a class="restrict-content-section-link" href="https://restrictcontentpro.com/knowledgebase/testing-for-conflicts-with-themes-and-other-plugins/" target="_blank">
					<div id="restrict-content-troubleshooting-link" class="restrict-content-help-section">
						<div class="restrict-content-help-section-icon">
							<div id="restrict-content-trouble-shooting" class="restrict-content-help-section-trouble-shooting-image"></div>
						</div>
						<div class="restrict-content-help-section-content">
							<h3><?php _e( 'Troubleshooting', 'restrict-content' ); ?></h3>
							<p><?php _e( 'If you run into any errors or things aren’t working as expected, the first step in troubleshooting is to check for a plugin or theme conflict.', 'restrict-content' ); ?></p>
						</div>
						<img class="restrict-content-help-section-arrow hidden" style="display: none;" src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/purple-arrow-right.svg' ); ?>" >
					</div>
				</a>
				
				<?php if( false === has_action('admin_menu','include_pro_pages') ) { ?>

				<a class="restrict-content-section-link" href="https://wordpress.org/support/plugin/restrict-content/" target="_blank">
					<div id="restrict-content-support-link" class="restrict-content-help-section">
						<div class="restrict-content-help-section-icon">
							<div id="restrict-content-support-forum" class="restrict-content-help-section-trouble-shooting-image"></div>
						</div>
						<div class="restrict-content-help-section-content">
							<h3><?php _e( 'Support Forum', 'restrict-content' ); ?></h3>
							<p><?php _e( 'If you are still having trouble after checking for a conflict, feel free to start a new thread on the Restrict Content support forum.', 'restrict-content' ); ?></p>
						</div>
						<img class="restrict-content-help-section-arrow hidden" style="display:none;" src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/purple-arrow-right.svg' ); ?>" >
					</div>
				</a>
				<?php } else { ?>
					<a class="restrict-content-section-link" href="https://restrictcontentpro.com/support/" target="_blank">
					<div id="restrict-content-support-link" class="restrict-content-help-section">
						<div class="restrict-content-help-section-icon">
							<div id="restrict-content-support-forum" class="restrict-content-help-section-trouble-shooting-image"></div>
						</div>
						<div class="restrict-content-help-section-content">
							<h3><?php _e( 'Submit Support Ticket', 'restrict-content' ); ?></h3>
							<p><?php _e( 'If you are still having trouble after checking for a conflict, feel free to start a new thread on the Restrict Content support forum.', 'restrict-content' ); ?></p>
						</div>
						<img class="restrict-content-help-section-arrow hidden" style="display:none;" src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/purple-arrow-right.svg' ); ?>" >
					</div>
				</a>

				<?php } ?>
				<?php if( false === has_action('admin_menu','include_pro_pages') ) { ?>
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
				<?php } ?>
			</div>
		</div>
		<div class="restrict-content-welcome-right-container">
			<div class="restrict-content-welcome-advertisement">
				<div class="logo">
					<img class="restrict-content-welcome-advertisement-logo" src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/Stacked_Logo_V2.svg' ); ?>" >
				</div>
				<div class="restrict-content-welcome-try-for-free">
					<p><?php _e( 'Try For Free!', 'restrict-content' ); ?></p>
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
						<input type="submit" class="restrict-content-welcome-button" value="<?php _e( 'Try Now, Free!', 'restrict-content' ); ?>">
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

/**
 * Build out the Welcome page for Restrict Content 3.0 and Restrict Content Pro
 *
 * @since 3.6
 */
function rcp_welcome_page_redesign() {
	$current_user = wp_get_current_user();

	//  $rc_welcome_try_free_meta_nonce = wp_create_nonce( 'rc_welcome_try_free_meta_nonce' );
	?>
	<div class="restrict-content-welcome-header">
		<img class="restrict-content-logo" src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/Full-Logo-1.svg' ); ?>" >
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
						<h2 class="restrict-content-thanks-header"><?php _e( 'Thanks for installing Restrict Content Pro!', 'restrict-content' ); ?></h2>
						<p class="restrict-content-thanks-message"><?php _e( 'Restrict Content Pro is a simple, yet powerful WordPress membership plugin that gives you full control over who can and cannot view content on your WordPress site.', 'restrict-content' ); ?></p>
						<p class="restrict-content-thanks-message"><?php _e( 'Start your membership site and create multiple Membership Levels and collect payments with Stripe, PayPal, Braintree or Authorize.net.', 'restrict-content' ); ?></p>
					</div>

				</div>
				<div class="restrict-content-welcome-body-container">
					<div class="restrict-content-how-to-body restrict-content-container-section">
						<h2><?php _e( 'Collect Payments with Stripe, PayPal, Braintree or Authorize.net', 'restrict-content' ); ?></h2>

						<p class="restrict-content-how-to-message">
							<?php
							printf(
								__( 'With Pro you can use several popular payment gateways to collect payments. We even have an <a href="%s" target="_blank">API</a> that you can use to integrate RCP with additional payment gateways. ', 'restrict-content' ),
						'https://help.ithemes.com/hc/en-us/articles/360052351054-Payment-Gateway-API'
							);
							?>
							</p>


					</div>
				</div>
				<div class="restrict-content-welcome-body-container">
					<div class="restrict-content-helpful-resources restrict-content-container-section">
						<h2><?php _e( 'Helpful Resources', 'restrict-content' ); ?></h2>
						<div class="restrict-content-resource-container">
							<!-- <h3><?php _e( 'Knowledgebase', 'restrict-content' ); ?></h3> -->
							<p>
								<?php
								printf(
									__( 'Our <a href="%s">Knowledgebase</a> will help you become a Restrict Content & Restrict Content Pro expert.', 'restrict-content' ),
									'https://restrictcontentpro.com/knowledgebase'
								);
								?>
							</p>
						</div>
						<div class="restrict-content-resource-container">
							<!-- <h3><?php _e( 'Need More Control Over Your Content & Memberships?', 'restrict-content' ); ?></h3> -->
							<p>
								 <?php
								 printf(
                                     __( 'Check out our <a href="%s">suite of add-ons</a> for building awesome membership websites.', 'restrict-content' ),
                                     'https://restrictcontentpro.com/add-ons/'
								 );
								 ?>
							</p>
						</div>
						<div class="restrict-content-resource-container">
							<!-- <h3><?php _e( 'Introduction to Restrict Content Pro', 'restrict-content' ); ?></h3> -->
							<p>
								<?php
								printf(
										__( 'Get a <a href="%s">full overview of Restrict Content Pro</a> and dive into several of its key features.', 'restrict-content' ),
										'https://training.ithemes.com/webinar/introduction-to-restrict-content-pro/'
								);
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
	<?php
}
function rc_welcome_page_redesign() {
	$current_user = wp_get_current_user();

	$rc_welcome_try_free_meta_nonce = wp_create_nonce( 'rc_welcome_try_free_meta_nonce' );
	?>
	<div class="restrict-content-welcome-header">
		<img class="restrict-content-logo" src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/rc_logo_horizontal_black.svg' ); ?>" >
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
						<p class="restrict-content-thanks-message"><?php _e( 'Start your membership site and create multiple Membership Levels and collect payments with Stripe.', 'restrict-content' ); ?></p>
					</div>

				</div>
				<div class="restrict-content-welcome-body-container">
					<div class="restrict-content-how-to-body restrict-content-container-section">
						<h2><?php _e( 'Collect Payments with Stripe', 'restrict-content' ); ?></h2>
						<p class="restrict-content-how-to-message"><?php _e( "Install the free Restrict Content Stripe add-on to start accepting credit and debit card payments.", 'restrict-content' ); ?></p>
						<p class="restrict-content-how-to-message"><?php _e( 'Stripe is an excellent payment gateway with a simple setup process and exceptional reliability.', 'restrict-content' ); ?></p>
						<p class="restrict-content-how-to-message"><?php _e( "Placeholder text for Stripe add-on sign-up link.", 'restrict-content' ); ?></p>

					</div>
				</div>
				<div class="restrict-content-welcome-body-container">
					<div class="restrict-content-helpful-resources restrict-content-container-section">
						<h2><?php _e( 'Helpful Resources', 'restrict-content' ); ?></h2>
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
							<p>
								 <?php
								 printf(
                                     __( 'Check out Restrict Content Pro and our <a href="%s">suite of add-ons</a> for building awesome membership websites.', 'restrict-content' ),
                                     'https://restrictcontentpro.com/add-ons/'
								 );
								 ?>
							</p>
						</div>
						<div class="restrict-content-resource-container">
							<h3><?php _e( 'Introduction to Restrict Content Pro', 'restrict-content' ); ?></h3>
							<p>
								<?php
								printf(
										__( 'Get a <a href="%s">full overview of Restrict Content Pro</a> and dive into several of its key features.', 'restrict-content' ),
										'https://training.ithemes.com/webinar/introduction-to-restrict-content-pro/'
								);
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="restrict-content-welcome-right-container">
			<div class="restrict-content-welcome-advertisement">
				<div class="logo">
					<img class="restrict-content-welcome-advertisement-logo" src="<?php echo esc_url( RCP_PLUGIN_URL . 'core/includes/images/Stacked_Logo_V2.svg' ); ?>" >
				</div>
				<div class="restrict-content-welcome-try-for-free">
					<p><?php _e( 'Try For Free!', 'restrict-content' ); ?></p>
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
						<input type="submit" class="restrict-content-welcome-button" value="<?php _e( 'Try Now, Free!', 'restrict-content' ); ?>">
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
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
