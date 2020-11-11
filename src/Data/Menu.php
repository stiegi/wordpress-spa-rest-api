<?php

namespace Spa\Data;

use WP_Error;
use WP_REST_Response;

class Menu {

	public function register_routes()
	{
		register_rest_route( 'spa/v1', 'menu/(?P<id>\d+)',array(
			'methods'  => 'GET',
			'callback' => [&$this, 'get_menu'],
			'permission_callback' => '__return_true'
		));
	}

	public function get_menu($request)
	{
		$id = $request['id'];
		$items = wp_get_nav_menu_items($id);

		if(is_null($items)) {
			return new WP_Error( 'empty_post', 'there is no menu with this id', array('status' => 404) );
		}

		$items = $this->get_menu_data($items);
		$response = new WP_REST_Response($items);
		$response->set_status(200);
		return $response;
	}

	private function get_menu_data($items)
	{
		$data = [];

		foreach ($items as $item) {
			$item = $item->to_array();
			$domain = explode('?', $item['guid'])[0];
			$slug = explode($domain, $item['url'])[1];
			preg_match_all('/(?<=^|\/).*?(?=\/)/', $slug, $matches);

			$data[] = [
				'object' => $item['object'],
				'type' => $item['type'],
				'title' => $item['title'],
				'id' => $item['object_id'],
				'slugs' => $matches[0]
			];
		}

		return $data;
	}

}
