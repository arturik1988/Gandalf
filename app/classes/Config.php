<?php
class Config {
	public static function get($items) {
		global $config;

		$items = explode(".", $items);
		$value = $config;

		// Drill down
		foreach($items as $item) {
			$value = $value[$item];
		}

		return $value;
	}
}