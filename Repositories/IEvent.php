<?php
/**
 * Created by PhpStorm.
 * User: dulo
 * Date: 9/9/18
 * Time: 3:04 PM
 */

interface IEvent {
	public static function saveEvent($country, $event, $date);
	public static function checkIfRecordExists($country, $event, $date);
	public static function incrementEventCounter($country, $event, $date);
}