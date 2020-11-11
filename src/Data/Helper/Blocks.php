<?php
namespace Spa\Data\Helper;

class Blocks {

	public static function parse_blocks_recursively($content)
	{
		$reg = '/<!--\swp:(.+?) (.*?)-->\n(.*?)\n<!-- \/wp:\1 -->/s';
		preg_match_all($reg, $content, $matches);
		$blocks = [];
		for ($x = 1; $x < count($matches); $x++) {
			foreach($matches[$x] as $key => $match) {
				switch ($x) {
					case 1:
						$blocks[$key]['type'] = $match;
						break;
					case 2:
						$match = json_decode($match, true);
						$blocks[$key]['_'] = is_null($match) ? [] : $match;
						break;
					case 3:
						if(preg_match_all($reg, $match, $matches)) {
							$blocks[$key]['content'] = self::parse_blocks_recursively($match);
						} else {
							$blocks[$key]['content'] = $match;
						}
						break;
					default:
						break;
				}
			}
		}
		return self::parse_types($blocks);
	}

	private static function parse_types($blocks)
	{
		foreach ($blocks as &$block) {
			switch ($block['type']) {
				case 'image':
				case 'gallery':
					unset($block['content']);
					break;
				case 'list':
					preg_match_all('/<li>(.*?)<\/li>/', $block['content'], $matches);
					$block['content'] = $matches[1];
					break;
				case 'table':
					$block['content'] = self::parse_table($block['content']);
			}
		}
		return $blocks;
	}

	private static function parse_table($html) {
		preg_match_all('/<tr>(.*?)<\/tr>/', $html, $rows );
		$data = array();
		foreach ($rows[1] as $row) {
			preg_match_all('/<td>(.*?)<\/td>/', $row, $columns);
			$data[] = $columns[1];
		}
		return $data;
	}

}
