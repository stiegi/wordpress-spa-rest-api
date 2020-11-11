<?php

namespace Spa;

use Spa\Cache\File;
use Spa\Data\Post;
use Spa\Data\Menu;
use Spa\Data\Url;
use Spa\Settings\SettingRoutes;
use Spa\Settings\SettingsMenu;

class Init {

	public function __construct()
	{
		add_action( 'init', [SettingsMenu::class, 'add_settings_menu']);

		// TODO check & implement apc_u cache, else:

		$instances = [
			new Post(),
			new SettingRoutes(),
			new Menu(),
			new Url(),
			new File()
		];

		$this->addEndpoints($instances);





	}

	private function addEndpoints($instances) {
		foreach ($instances as $instance) {
			add_action('rest_api_init', [$instance, 'register_routes']);
		}
	}
}
