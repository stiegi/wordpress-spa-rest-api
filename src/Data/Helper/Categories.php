<?php

namespace Spa\Data\Helper;

class Categories {
	public static function get_categories($post)
	{
		$categories = get_the_category($post->ID);
		$category_groups = [];
		$parent_count = 0;
		foreach ($categories as $key => $category) {
			if($category->parent === 0 || !self::does_post_have_category($post, $category->parent)) {
				$category_groups[$parent_count] = [];
				array_push($category_groups[$parent_count], $category);
				unset($categories[$key]);
				$parent_count++;
			}
		}
		$unsorted_categories = array_values($categories);
		self::sort_categories_recursively($unsorted_categories, $category_groups);

		$output = [];
		foreach ($category_groups as $group_index => $category_group) {
			$output[$group_index] = [];
			foreach ($category_group as $index => $category) {
				$output[$group_index][$index] = [
					'name' => $category->name,
					'slug' => $category->slug,
					'description' => $category->category_description,
					'count' => $category->count
				];
			}
		}
		return $output;
	}

	private static function sort_categories_recursively(&$unsorted_categories, &$all_parents)
	{
		foreach ($unsorted_categories as $key => $unsorted_category) {
			foreach ($all_parents as &$parents) {
				$direct_parent = $parents[count($parents)-1];
				if($direct_parent->term_id === $unsorted_category->parent) {
					array_push($parents, $unsorted_category);
					unset($unsorted_categories[$key]);
				}
			}
		}
		if(count($unsorted_categories) > 0) {
			self::sort_categories_recursively($unsorted_categories, $all_parents);
		}
	}

	private static function does_post_have_category($post, $term_id)
	{
		$all_term_IDs = [];
		foreach (get_the_category($post->ID) as $term) {
			array_push($all_term_IDs, $term->term_id);
		}
		return in_array($term_id, $all_term_IDs);
	}
}
