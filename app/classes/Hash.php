<?php
class Hash {
	public static function make($string) {
		return password_hash($string, PASSWORD_BCRYPT, array("cost" => 10));
	}

	public static function verify($plain, $hashed) {
		return password_verify($plain, $hashed);
	}
}