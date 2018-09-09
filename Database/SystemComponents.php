<?php
/**
 * Created by PhpStorm.
 * User: dulo
 * Date: 9/9/18
 * Time: 12:48 AM
 */

abstract class SystemComponents {

	protected $settings;

	public static function getSettings() {

		$settings['dbhost'] = 'localhost';
		$settings['dbusername'] = 'root';
		$settings['dbpassword'] = '';
		$settings['dbname'] = 'quantox-test';

		return $settings;
	}

}