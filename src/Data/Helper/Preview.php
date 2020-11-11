<?php

namespace Spa\Data\Helper;

class Preview {

	public static function get_previews_by_ids($ids, $length = 0)
	{
		global $wpdb;
		$results = $wpdb->get_results('SELECT ID as id, post_name as url, post_title as title, post_content as content, post_excerpt as excerpt, post_date as created, post_modified as modified FROM ' . $wpdb->posts .  ' WHERE ID IN (' . implode(',', $ids) . ') ORDER BY post_modified DESC');
		if (empty($results)) {
			return null;
		}
		$results = self::process_results($results);
		$results = self::limit_excerpts($results, $length);
		return $results;
	}

	private static function process_results(&$results)
	{
		$paragraph_content = '/(?<=<p>).*?(?=<\/p>)/';
		foreach ($results as $key => $result) {
			if($result->excerpt === '' && preg_match_all($paragraph_content, $result->content, $matches)) {
				$results[$key]->excerpt = implode('\n', $matches[0]);
			}
			unset($results[$key]->content);
			$results[$key]->img = wp_get_attachment_image_url(get_post_thumbnail_id($result->id), 'thumbnail');
		}
		return $results;
	}

	private static function limit_excerpts(&$results, $length)
	{
		if($length > 5) {
			foreach ($results as &$result) {
				$result->excerpt = strlen($result->excerpt) > ($length - 3) ?
					substr($result->excerpt, 0, $length) . '...' : $result->excerpt;
			}
		}
		return $results;
	}
}

