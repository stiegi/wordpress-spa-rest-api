<?php
namespace Spa\Settings;

use WP_Error;
use WP_HTTP_Response;
use WP_REST_Response;
use WP_REST_Request;

class SettingRoutes {

	/**
	 * Add routes
	 */
	public function register_routes()
	{
		register_rest_route( 'spa/v1', '/settings',
			[
				'methods'         => 'POST',
				'callback'        => [&$this, 'update_settings'],
				'args' => [
					'settings' => [
						'type' => 'string',
						'required' => false,
						'sanitize_callback' => 'sanitize_text_field'
					]
				],
				'permission_callback' => [&$this, 'can_manage_options']
			]
		);
		register_rest_route( 'spa/v1', '/settings',
			[
				'methods'         => 'GET',
				'callback'        => [&$this, 'get_settings'],
				'args'            => [],
				'permission_callback' => [&$this, 'can_manage_options']
			]
		);
	}

	/**
	 * Check request permissions
	 * @return bool
	 */
	public function can_manage_options()
	{
		return current_user_can( 'manage_options' );
	}

	/**
	 * Update settings
	 * @param WP_REST_Request $request
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function update_settings( WP_REST_Request $request )
	{
		$settings = ['settings' => $request->get_body()];
		Settings::save_settings( $settings );
		$response = rest_ensure_response( Settings::get_settings());
		$response->set_status(201);
		return $response;
	}

	/**
	 * Get settings via API
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_settings()
	{
		return rest_ensure_response( Settings::get_settings());
	}
}
