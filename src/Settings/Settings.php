<?php
namespace Spa\Settings;


class Settings {
	/**
	 * Option key to save settings
	 *
	 * @var string
	 */
	protected static $option_key = '_spa_api_settings';

	/**
	 * Default settings
	 *
	 * @var array
	 */
	protected static $defaults = [];

	/**
	 * Get saved settings
	 *
	 * @return array
	 */
	public static function get_settings()
	{
		$saved = get_option(self::$option_key);
		self::$defaults['settings'] = wp_json_encode([
			"Cache" => ["activateCache" => true]
		]);
		return wp_parse_args($saved, self::$defaults);
	}

	/**
	 * Save settings
	 *
	 * Array keys must be whitelisted (IE must be keys of self::$defaults
	 *
	 * @param array $settings
	 */
	public static function save_settings( array  $settings ){
		update_option( self::$option_key, $settings );
	}

	/**
	 * Check request permissions
	 * @return bool
	 */
	public static function can_manage_options()
	{
		return current_user_can( 'manage_options' );
	}
}
