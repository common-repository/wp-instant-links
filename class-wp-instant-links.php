<?php

if (!defined('ABSPATH')) die('No direct access.');

/**
 * Register the plugin.
 */
class WpInstantLinks
{

	/**
	 * Plugin Version
	 *
	 * @var string
	 */
	public $version = '1.1.0';

	/**
	 * Plugin Name
	 *
	 * @var string
	 */
	public static $name = 'WP Instant Links';

	/**
	 * Array of various options (could use a refactor)
	 *
	 * @var array
	 */
	protected $options = array();

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
	 * Used to set up the plugin
	 */
	public function setup()
	{
		define('WP_INSTANT_LINKS_SLUG', sanitize_title(self::$name));
		define('WP_INSTANT_LINKS_PATH', plugin_dir_path(__FILE__));
		define('WP_INSTANT_LINKS_BASE_URL', trailingslashit(plugin_dir_url($this->pluginLocation())));

		$this->setSettings();

		$this->addFrontEndHooks();

		WpInstantLinks_admin::getInstance()->setup();
	}

	/**
	 * Constructor. Intentionally left empty and public.
	 */
	public function __construct() {}

	/**
	 * Build the options array
	 */
	public function setSettings()
	{
		$this->options['debug'] = filter_var(get_option(WP_INSTANT_LINKS_SLUG . '_debug', false), FILTER_VALIDATE_BOOLEAN);

		// If in debug mode use the local version (with added console log messages)
		$this->options['useCDN'] = $this->options['debug']  ? false : !filter_var(get_option(WP_INSTANT_LINKS_SLUG . '_dont-use-cdn', true), FILTER_VALIDATE_BOOLEAN);

		$this->options['loadOnAdmin'] = filter_var(get_option(WP_INSTANT_LINKS_SLUG . '_admin', false), FILTER_VALIDATE_BOOLEAN);

		$this->options['allowQueryStrings'] = filter_var(get_option(WP_INSTANT_LINKS_SLUG . '_include_query', false), FILTER_VALIDATE_BOOLEAN);

		$this->options['allowExternalLinks'] = filter_var(get_option(WP_INSTANT_LINKS_SLUG . '_include_external', false), FILTER_VALIDATE_BOOLEAN);

		// The added implode/explode functions will allow some funky spacing from the user 
		$this->options['exclusions'] = implode(', ', explode(',', get_option(WP_INSTANT_LINKS_SLUG . '_exclude', '')));

	}

	/**
	 * Add filters that load stuff on the front end
	 */
	public function addFrontEndHooks() {
		add_action('wp_enqueue_scripts', array($this, 'addScriptToPage'), PHP_INT_MAX);

		if ($this->options['loadOnAdmin']) {
			// TODO: only seems to be loading if in the <head> which breaks everything. 
			// add_filter('admin_enqueue_scripts', array($this, 'addScriptToPage'), PHP_INT_MAX);
		}

		if ($this->options['useCDN']) {
			add_filter('script_loader_tag', array($this, 'addAttributesToScript'), 10, 3);
		}
	}

	/**
	 * Adds the main file to the page
	 */
	public function addScriptToPage() {
		$src = $this->options['useCDN'] ? '//instant.page/1.2.2' : WP_INSTANT_LINKS_BASE_URL . 'js/instantpage.js';
		wp_enqueue_script('wp-instant-links.js', $src, array(), '', true);

		if ($this->options['debug']) {
			wp_add_inline_script('wp-instant-links.js', 'window.wpInstantLinks_showStatus = true');
		}

		if ($exclusions = $this->options['exclusions']) {
			$exclusions_script = "
				var wpil_isSupported = prefetcher.relList && prefetcher.relList.supports && prefetcher.relList.supports('prefetch')
				wpil_isSupported && window.addEventListener('load', function() {
					elementsExcluded = document.querySelectorAll('{$exclusions}');
					window.window.wpInstantLinks_showExclusions && console.log('Elements Excluded: ', elementsExcluded);
					elementsExcluded.forEach(function(elm) {
						elm.dataset.noInstant = true;
					});
				});
			";
			if ($this->options['debug']) {
				$exclusions_script .= 'window.wpInstantLinks_showExclusions = true;';
			}
			wp_add_inline_script('wp-instant-links.js', $exclusions_script);
		}

		if ($this->options['allowQueryStrings']) {
            wp_add_inline_script('wp-instant-links.js', "document.body.dataset.instantAllowQueryString = true;", 'before');
        }

        if ($this->options['allowExternalLinks']) {
            wp_add_inline_script('wp-instant-links.js', "document.body.dataset.instantAllowExternalLinks = true;", 'before');
        }
	}

	/**
	 * Adds additional attributes to the script
	 */
	public function addAttributesToScript($tag, $handle, $src) {
		if ('wp-instant-links.js' === $handle) {
			$tag = '<script src="' . esc_url($src) . '" type="module" integrity="sha384-2xV8M5griQmzyiY3CDqh1dn4z3llDVqZDqzjzcY+jCBCk/a5fXJmuZ/40JJAPeoU"></script>';
		}
		return $tag;
	}

	/**
	 * File location in case it was moved
	 */
	private function pluginLocation() {
		{
			if (!function_exists('get_plugins')) include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			foreach (get_plugins() as $plugin => $data) {
				if ($data['TextDomain'] === 'wp-instant-links')
					return $plugin;
			}

			// return the default
			return 'wp-instant-links/wp-instant-links.php';
		}
	}


}