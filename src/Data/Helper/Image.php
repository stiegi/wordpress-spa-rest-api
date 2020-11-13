<?php

namespace Spa\Data\Helper;

class Image {

	public static function get_image_data($all_posts, $image_ids = [])
	{
		foreach ($all_posts as $block_array) {
			foreach ($block_array as &$block_entry)
			{
				if($block_entry['type'] === 'image') {
					array_push($image_ids, $block_entry['_']['id']);
				}
				elseif ($block_entry['type'] === 'gallery') {
					foreach ($block_entry['_']['ids'] as $id) {
						array_push($image_ids, $id);
					}
				}
			}
		}
		$images = array();
		$allSizes = get_intermediate_image_sizes();
		foreach (array_unique($image_ids) as $image_id) {
			$images[$image_id] = array();
			$image = get_post($image_id);
			$images[$image_id]['title'] = $image->post_title;
			$images[$image_id]['description'] = $image->post_content;
			$images[$image_id]['caption'] = $image->post_excerpt;
			$images[$image_id]['alt'] = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

			preg_match('/(^.*\/)(.*$)/', wp_get_attachment_image_url( $image_id, 'full' ), $matches);
			$images[$image_id]['path'] = $matches[1];
			$images[$image_id]['file'] = $matches[2];
			foreach ($allSizes as $size) {
				$images[$image_id][$size] =
					explode($images[$image_id]['path'], wp_get_attachment_image_url($image_id, $size))[1];
			}
		}
		return $images;
	}
}
