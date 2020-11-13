<?php
namespace Spa\Data\Helper;

class Fetcher {

	/**
	 * @param $slugs array|null
	 * @return array
	 */
	public static function get_posts_by_slugs($slugs = null)
	{
		global $wpdb;
		$query = 'SELECT ID, post_content, post_name, post_title, post_excerpt FROM ' . $wpdb->posts .
			' WHERE (post_type = "post" OR post_type = "page")' .
			' AND post_status = "publish"' .
			(is_null($slugs) ? '' : ' AND post_name IN ("' . implode("\",\"", $slugs) . '")');
		$post_objects = [];
		$posts = $wpdb->get_results($query);
		foreach ($posts as $post) {
			$post_objects[$post->post_name]['post'] = self::process_post($post);
		}
		return $post_objects;
	}

	public static function process_post($post)
	{
		$blocks = Blocks::parse_blocks_recursively($post->post_content);
		$thumbnail = get_post_thumbnail_id($post->ID);
		$thumbnail_array = $thumbnail === 0 ? [] : [$thumbnail];
		return [
			'content' => $blocks,
			'images' => Image::get_image_data([$blocks], $thumbnail_array),
			'categories' => Categories::get_categories($post),
			'title' => $post->post_title,
			'excerpt' => $post->post_excerpt,
			'thumbnail' => $thumbnail,
			'meta' => self::get_meta($post)
		];
	}

	private static function get_meta($post)
	{
		$keys_to_unset = [
			'_edit_lock',
			'_thumbnail_id',
			'_wp_old_slug',
			'_edit_last'
		];
		$meta = get_post_meta($post->ID);
		foreach ($keys_to_unset as $key) {
			if(isset($meta[$key])) {
				unset($meta[$key]);
			}
		}
		return $meta;
	}
}
