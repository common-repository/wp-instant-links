<?php

if (!defined('ABSPATH')) die('No direct access.');

class WpInstantLinks_admin
{

	/**
	 * Instance object
	 *
	 * @var object
	 * @see get_instance()
	 */
	protected static $instance = null;


	/**
	 * Used to access the instance
	 */
	public static function getInstance()
	{
		if (null === self::$instance) self::$instance = new self();
		return self::$instance;
	}

	/**
	 * Set up the admin page
	 */
	public function setup()
	{
		add_submenu_page('tools.php', WpInstantLinks::$name, WpInstantLinks::$name, 'manage_options', WP_INSTANT_LINKS_SLUG, array($this, 'addAdminPage'));

		add_action('admin_init', array($this, 'addSettingsFields'));
	}

	/**
	 * Add some fields
	 */
	public function addSettingsFields()
	{
		// CDN
		add_settings_section(WP_INSTANT_LINKS_SLUG, '', null, WP_INSTANT_LINKS_SLUG);
		add_settings_field(WP_INSTANT_LINKS_SLUG . '_dont-use-cdn', 'File Source', array($this, 'fieldLoadFileFrom'), WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG);
		register_setting(WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG . '_dont-use-cdn');

		// Admin TODO: figure out why it wont load in admin footer
		// add_settings_section(WP_INSTANT_LINKS_SLUG, '', null, WP_INSTANT_LINKS_SLUG);
		// add_settings_field(WP_INSTANT_LINKS_SLUG . '_admin', 'Admin', array($this, 'fieldLoadOnAdmin'), WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG);
		// register_setting(WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG . '_admin');

		// Include links with query strings
		add_settings_section(WP_INSTANT_LINKS_SLUG, '', null, WP_INSTANT_LINKS_SLUG);
		add_settings_field(WP_INSTANT_LINKS_SLUG . '_include_query', 'Query Strings', array($this, 'fieldQueryStrings'), WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG);
		register_setting(WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG . '_include_query');

		// Include external links
		add_settings_section(WP_INSTANT_LINKS_SLUG, '', null, WP_INSTANT_LINKS_SLUG);
		add_settings_field(WP_INSTANT_LINKS_SLUG . '_include_external', 'Query Strings', array($this, 'fieldExternalLinks'), WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG);
		register_setting(WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG . '_include_external');

		// Exclusions
		add_settings_section(WP_INSTANT_LINKS_SLUG, '', null, WP_INSTANT_LINKS_SLUG);
		add_settings_field(WP_INSTANT_LINKS_SLUG . '_exclude', 'Exclusions', array($this, 'fieldExclusions'), WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG);
		register_setting(WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG . '_exclude');

		// Debug
		add_settings_section(WP_INSTANT_LINKS_SLUG, '', null, WP_INSTANT_LINKS_SLUG);
		add_settings_field(WP_INSTANT_LINKS_SLUG . '_debug', 'Debug Mode', array($this, 'fieldDebugMode'), WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG);
		register_setting(WP_INSTANT_LINKS_SLUG, WP_INSTANT_LINKS_SLUG . '_debug');
	}

	/**
	 * Field about how to load the JS file
	 */
	public function fieldLoadFileFrom()
	{ ?>
	<label>
		<input type="checkbox" value="1" name="<?php echo WP_INSTANT_LINKS_SLUG . '_dont-use-cdn'; ?>" <?php checked(get_option(WP_INSTANT_LINKS_SLUG . '_dont-use-cdn')); ?> />
		<span><?php _ex('Use the local file instead of the CDN (default: off)', '"off" refers to a checkbox being in the off state', WP_INSTANT_LINKS_SLUG); ?></span>
	</label>
<?php }

/**
 * Field about whether to load on admin screens too
 */
public function fieldLoadOnAdmin()
{ ?>
	<label>
		<input type="checkbox" value="1" name="<?php echo WP_INSTANT_LINKS_SLUG . '_admin'; ?>" <?php checked(get_option(WP_INSTANT_LINKS_SLUG . '_admin')); ?> />
		<span><?php _ex('Load this script on admin pages (default: off)', '"off" refers to a checkbox being in the off state', WP_INSTANT_LINKS_SLUG); ?></span>
	</label>
<?php }

/**
 * Field about which elements to include
 */
public function fieldQueryStrings()
{ ?>
	<label>
		<input type="checkbox" value="1" name="<?php echo WP_INSTANT_LINKS_SLUG . '_include_query'; ?>" <?php checked(get_option(WP_INSTANT_LINKS_SLUG . '_include_query')); ?> />
		<span><?php _e('By default, pages with a query string (a “?”) in their URL aren’t preloaded. This is to avoid loading logout pages, etc. Enable this to allow these pages to be preloaded. Use caution and be sure to add exclusions below as necessary. (default: off)', WP_INSTANT_LINKS_SLUG); ?></span>
	</label>
<?php
}

/**
 * Field about which elements to include
 */
public function fieldExternalLinks()
{ ?>
	<label>
		<input type="checkbox" value="1" name="<?php echo WP_INSTANT_LINKS_SLUG . '_include_external'; ?>" <?php checked(get_option(WP_INSTANT_LINKS_SLUG . '_include_external')); ?> />
		<span><?php _e('By default, external URLs aren’t preloaded. Use this to allow external links to be preloaded. Alternatively, add the data-instant attribute to external links manually. (default: off)', WP_INSTANT_LINKS_SLUG); ?></span>
	</label>
<?php
}

/**
 * Field about which elements to include
 */
public function fieldExclusions()
{ ?>
	<label>
		<span><?php _e('Add a comma separated list of query selectors here that you wish to exclude from preloading.', WP_INSTANT_LINKS_SLUG); ?></span>
	</label>
	<textarea id="" name="<?php echo WP_INSTANT_LINKS_SLUG . '_exclude' ?>" cols="80" rows="10" class="large-text" placeholder="#logout, .endpoint-to-avoid, ul > li > a"><?php echo esc_html(get_option(WP_INSTANT_LINKS_SLUG . '_exclude')) ?></textarea>
<?php
}

/**
 * Field about enabling debug mode
 */
public function fieldDebugMode()
{ ?>
	<label>
		<input type="checkbox" value="1" name="<?php echo WP_INSTANT_LINKS_SLUG . '_debug'; ?>" <?php checked(get_option(WP_INSTANT_LINKS_SLUG . '_debug')); ?> />
		<span><?php _ex('View the developer\'s console for preloaded link info. Note: this will use the local file instead of the CDN. (default: off)', '"off" refers to a checkbox being in the off state', WP_INSTANT_LINKS_SLUG); ?></span>
	</label>
<?php }

/**
 * Output admin page html. Keeping it simple
 */
public function addAdminPage()
{ ?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h1><?php esc_attr_e('WP Instant Links', WP_INSTANT_LINKS_SLUG); ?></h1>

		<div id="poststuff" style="max-width:1400px">
			<div id="post-body" class="metabox-holder columns-2">
				<!-- main content -->
				<div id="post-body-content">
					<?php if (isset($_REQUEST['settings-updated']) && filter_var($_REQUEST['settings-updated'], FILTER_VALIDATE_BOOLEAN)) { ?>
						<div class="notice notice-success inline">
							<p><?php _e('Settings saved.', WP_INSTANT_LINKS_SLUG); ?></p>
						</div>
					<?php
				} ?>
					<div class="meta-box-sortables ui-sortable">
						<div class="postbox">
							<h2><span><?php esc_attr_e('Settings', WP_INSTANT_LINKS_SLUG); ?></span></h2>
							<div class="inside">
								<form method="POST" action="options.php">
									<?php
									settings_fields(WP_INSTANT_LINKS_SLUG);
									do_settings_sections(WP_INSTANT_LINKS_SLUG);
									submit_button();
									?>
								</form>
							</div><!-- .inside -->
						</div><!-- .postbox -->
					</div><!-- .meta-box-sortables .ui-sortable -->
				</div><!-- post-body-content -->

				<!-- sidebar -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="meta-box-sortables">
						<div class="postbox">

							<h2>Additional Links</h2>
							<div class="inside">
								<ul>
									<li>
										<a target="_blank" href="https://wordpress.org/support/plugin/wp-instant-links/">
											<?php _e('Support', WP_INSTANT_LINKS_SLUG); ?>
										</a>
									</li>
									<li>
										<?php printf(_x('%sDonate%s ($5)', 'Donate is wrapped with an anchor tag. Currency should remain in USD', WP_INSTANT_LINKS_SLUG), '<a target="_blank" href="https://www.paypal.me/kevinbatdorf/5">', '</a>'); ?>
									</li>
								</ul>
							</div><!-- .inside -->

						</div><!-- .postbox -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #postbox-container-1 .postbox-container -->
			</div><!-- #post-body .metabox-holder .columns-2 -->
			<br class="clear">
		</div><!-- #poststuff -->
	</div> <!-- .wrap -->
<?php }
}
