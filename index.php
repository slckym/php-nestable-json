<?php

	$json = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "data.json");

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