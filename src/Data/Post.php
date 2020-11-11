<?php
namespace Spa\Data;

use Spa\Data\Helper\Blocks;
use Spa\Data\Helper\Categories;
use Spa\Data\Helper\Image;
use WP_Error;
use WP_REST_Response;

class Post {
	public function register_routes()
	{
		register_rest_route( 'spa/v1', 'post/(?P<id>\d+)',array(
			'methods'  => 'GET',
			'callback' => [&$this, 'get_post'],
			'permission_callback' => '__return_true'
		));
	}


	public function get_post($request) {


		// TODO implement preview for categories
//		$a = Preview::get_previews_by_ids([1,2,3], 100);

		$blocks = $this->get_blocks($request['id']);
		if(is_null($blocks)) {
			return new WP_Error( 'empty_post', 'there is no post with this id', array('status' => 404) );
		}

		$blocks = [
			'content' => $blocks,
			'images' => Image::get_image_data([$blocks]),
			'categories' => Categories::get_categories($request['id'])
		];

		$response = new WP_REST_Response($blocks);
		$response->set_status(200);
		return $response;
	}

	/**
	 * @param $id
	 * @return array|null
	 */
	private function get_blocks($id)
	{
		global $wpdb;
		$result = $wpdb->get_results('SELECT post_content FROM ' . $wpdb->posts .  ' WHERE (post_type = "post" OR post_type = "page") AND ID = "' . $id . '" LIMIT 1');
		if (empty($result)) {
			return null;
		}
		return Blocks::parse_blocks_recursively($result[0]->post_content);
	}
}
