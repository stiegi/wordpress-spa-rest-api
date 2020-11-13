<?php
namespace Spa\Cache;

use Spa\Data\Helper\Blocks;
use Spa\Data\Helper\Categories;
use Spa\Data\Helper\Fetcher;
use Spa\Data\Helper\Image;
use WP_REST_Response;

class File {
	private $files_written = 0;
	private $bytes_written = 0;
	private $errors = 0;
	private $cache_directory;

	public function __construct()
	{
		$this->cache_directory = explode('src/Cache', dirname(__FILE__))[0] . 'cache/';
		add_action('post_updated', [&$this, 'subscribe_to_post_updates'], 10, 3);
	}

	/**
	 * Add routes
	 */
	public function register_routes()
	{
		register_rest_route( 'spa/v1', '/cache/file/build',
			[
				'methods'         => 'GET',
				'callback'        => [&$this, 'build'],
				'args'            => [],
				'permission_callback' => 'Spa\Settings\Settings::can_manage_options'
			]
		);

		register_rest_route( 'spa/v1', '/cache/file/get/(?P<slug>.+)',
			[
				'methods'  => 'GET',
				'callback' => [&$this, 'get'],
				'permission_callback' => '__return_true'
			]
		);
	}

	public function subscribe_to_post_updates($post_id, $post_after, $post_before)
	{
		$post_before_filename = $this->cache_directory . $post_before->post_name . '.json';
		$post_after_filename = $this->cache_directory . $post_after->post_name . '.json';
		// TODO implement taxonomies (don't delete if there is still a taxonomy, and if yes, only with tax data
		if(file_exists($post_before_filename)) {
			unlink($post_before_filename);
		}
		if(file_exists($post_after_filename)) {
			unlink($post_after_filename);
		}
		if($post_after->post_status === 'publish')
		{
			$post_objects = [
				$post_after->post_name => Fetcher::process_post($post_after)
			];
			$this->write_files($post_objects);
		}
	}

	public function build()
	{
		$start_time = microtime(true);
		if(!file_exists($this->cache_directory)) {
			mkdir($this->cache_directory);
		}
		// delete existing files
		array_map('unlink', glob($this->cache_directory . '/*.json'));

		$this->write_files(Fetcher::get_posts_by_slugs());
		$result = [
			'count' => $this->files_written,
			'time' => microtime(true) - $start_time,
			'bytes' => $this->bytes_written,
			'errors' => $this->errors
		];

		$response = new WP_REST_Response($result);
		$response->set_status(200);
		return $response;
	}

	public function get($request)
	{
		$file_names = explode('/', $request->get_params()['slug']);
		$response_data = [];
		foreach ($file_names as $file_name) {
			$file_path = $this->cache_directory . $file_name . '.json';
			$file = @file_get_contents($file_path);
			$response_data = array_merge($response_data, $file ? json_decode($file, true) : [ $file_name => null ]);
		}
		$response = new WP_REST_Response($response_data);
		$response->set_status(200);
		return $response;
	}

//	private function write_files()
//	{
//		global $wpdb;
//		$results = $wpdb->get_results('SELECT ID, post_content, post_name FROM ' . $wpdb->posts .  ' WHERE post_type = "post" OR post_type = "page"');
//		if (!empty($results)) {
//			foreach ($results as $result) {
//				$post_object = $this->handle_block_content($result->ID, $result->post_name, $result->post_content);
//				$this->write_file($post_object, $result->post_name);
//			}
//		}
//	}

//	private function handle_block_content($id, $slug, $content)
//	{
//		$blocks = Blocks::parse_blocks_recursively($content);
//		$post_object = [];
//		$post_object[$slug]['post'] = [
//			'content' => $blocks,
//			'images' => Image::get_image_data([$blocks]),
//			'categories' => Categories::get_categories($id)
//		];
//		return $post_object;
//	}

	private function write_files($post_objects)
	{
		foreach ($post_objects as $slug => $post_object) {
			$json = json_encode([$slug => $post_object], JSON_UNESCAPED_UNICODE);
			$fp = fopen($this->cache_directory . $slug . '.json', 'w');
			$bytes_written = fwrite($fp, $json);
			if($bytes_written) {
				$this->bytes_written += $bytes_written;
				$this->files_written++;
			} else {
				$this->errors++;
			}
			fclose($fp);
		}
	}

//	Abstraction:
//- get a list of slots (z.B. posts die zu einem geänderten term gehören)
//- where in [names / IDs...] sql requests zu posts und terms -> 2 db queries
//- die results in 2 handler durchlaufen lassen, wenns n result gibt content- oder tax-handler, sonst null
// - beide null (kann nach edit passieren,wen slot geändert wurde, aber name ist mit drin vom before), dann kann die datei gelöscht werden

}
