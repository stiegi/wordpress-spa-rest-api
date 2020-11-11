<?php
namespace Spa\Data;

use Spa\Data\Helper\Blocks;
use Spa\Data\Helper\Categories;
use Spa\Data\Helper\Image;
use WP_REST_Response;

class Url {

	private $response_data = [];

	public function register_routes()
	{
		register_rest_route( 'spa/v1', 'url/(?P<slug>.+)',array(
			'methods'  => 'GET',
			'callback' => [&$this, 'get_post'],
			'permission_callback' => '__return_true'
		));
	}

	public function get_post($request) {
		$request_slug = $request->get_params()['slug'];
		foreach (explode('/', $request_slug) as $slug)
		{
			// TODO check if data is in cache, else:
			$this->get_post_content($slug);

			// TODO check wp_terms -> slug table with get_term_content method, as for now:
			$this->response_data[$slug]['term'] = 'TODO';
		}
		$response = new WP_REST_Response($this->response_data);
		$response->set_status(200);
		return $response;
	}

	/**
	 * @param $slug
	 * @return void
	 */
	private function get_post_content($slug)
	{
		global $wpdb;
		$result = $wpdb->get_results('SELECT ID, post_content FROM ' . $wpdb->posts .  ' WHERE (post_type = "post" OR post_type = "page") AND post_name = "' . $slug . '" LIMIT 1');
		if (empty($result)) {
			$this->response_data[$slug]['post'] = null;
		} else {
			$blocks = Blocks::parse_blocks_recursively($result[0]->post_content);
			$this->response_data[$slug]['post'] = [
				'content' => $blocks,
				'images' => Image::get_image_data([$blocks]),
				'categories' => Categories::get_categories($result[0]->ID)
			];
		}
	}


}
