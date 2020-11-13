<?php
namespace Spa\Data;

use Spa\Data\Helper\Fetcher;
use WP_REST_Response;

class Url {

	public function register_routes()
	{
		register_rest_route( 'spa/v1', 'url/(?P<slug>.+)',array(
			'methods'  => 'GET',
			'callback' => [&$this, 'get_post'],
			'permission_callback' => '__return_true'
		));
	}

	public function get_post($request) {
		$request_slugs = explode('/', $request->get_params()['slug']);
		$response_data = Fetcher::get_posts_by_slugs($request_slugs);
		// TODO check wp_terms -> slug table with get_term_content method

		$response = new WP_REST_Response($response_data);
		$response->set_status(200);
		return $response;
	}


}
