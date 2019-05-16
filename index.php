<?php

	$json = '[
	{
		"id":1,
		"title":"T\u00fcrkiye",
		"parent_id":0
	},
	{
		"id":2,
		"title":"\u0130stanbul",
		"parent_id":1
	},
	{
		"id":3,
		"title":"Ankara",
		"parent_id":1
	},
	{
		"id":4,
		"title":"\u0130zmir",
		"parent_id":1
	},
	{
		"id":5,
		"title":"Fatih",
		"parent_id":2
	},
	{
		"id":6,
		"title":"\u00dcsk\u00fcdar",
		"parent_id":2
	},
	{
		"id":7,
		"title":"Kad\u0131k\u00f6y",
		"parent_id":2
	},
	{
		"id":8,
		"title":"\u00c7ankaya",
		"parent_id":3
	},
	{
		"id":9,
		"title":"Ke\u00e7i\u00f6ren",
		"parent_id":3
	},
	{
		"id":10,
		"title":"Buca",
		"parent_id":4
	},
	{
		"id":11,
		"title":"Altunizade Mah.",
		"parent_id":6
	},
	{
		"id":12,
		"title":"G\u00f6ztepe Mah.",
		"parent_id":7
	},
	{
		"id":13,
		"title":"An\u0131ttepe Mah.",
		"parent_id":8
	},
	{
		"id":14,
		"title":"G\u00f6ztepe Park\u0131",
		"parent_id":12
	}
	]';

	$collections = json_decode($json, true);

	$converted = getParentCategories($collections);

	nestable($converted);

	/**
	 * @param array $collections
	 * @param int   $parent
	 * @param int   $level
	 *
	 * @return array
	 */
	function getParentCategories($collections = [], $parent = 0, $level = 0)
	{
		$result = [];
		if ($collections) {
			$parentCategories = search($collections, 'parent_id', $parent);
			if ($parentCategories) {
				foreach ($parentCategories as $index => $collection) {
					$children = getParentCategories($collections, $collection['id'], $level);
					$result[] = [
						'id'       => $collection['id'],
						'title'    => $collection['title'],
						'children' => $children
					];
				}
			}
		}

		return $result;
	}

	/**
	 * @param $array
	 * @param $key
	 * @param $value
	 *
	 * @return array
	 */
	function search($array, $key, $value)
	{
		$results = [];

		if (is_array($array)) {
			if (isset($array[$key]) && $array[$key] == $value) {
				$results[] = $array;
			}

			foreach ($array as $subarray) {
				$results = array_merge($results, search($subarray, $key, $value));
			}
		}

		return $results;
	}

	/**
	 * @param     $datas
	 */
	function nestable($datas)
	{
		foreach ($datas as $data) {
			echo "<ul>";
			echo "<li>";
			echo array_key_exists('title', $data) ? $data['title'] : null;
			if (array_key_exists('children', $data)) {
				nestable($data['children']);
			}
			echo "</li>";
			echo "</ul>";
		}
	}

	// nestable($result);