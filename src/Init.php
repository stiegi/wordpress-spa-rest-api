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
		add_action( 'enqueue_block_editor_assets', 'Spa\Settings\BlockEditor\BlockId::add_block_id_field' );
	}

	private function addEndpoints($instances) {
		foreach ($instances as $instance) {
			add_action('rest_api_init', [$instance, 'register_routes']);
		}
	}

}
