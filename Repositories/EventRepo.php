<?php
/**
 * Created by PhpStorm.
 * User: dulo
 * Date: 9/9/18
 * Time: 3:04 PM
 */
require_once 'IEvent.php';

class EventRepo implements IEvent {

	public static function saveEvent($country, $event) {

		$date = date('Y-m-d');

		$conn = DbConnection::connectToDb();
		$stmt = $conn->prepare("INSERT INTO events (country, event_name, `date`) VALUES(:country, :event_name, :event_date)");
		$stmt->execute(array(
			"country" => $country,
			"event_name" => $event,
			"event_date" => $date)
		);

		$conn = null;

	}

	public static function checkIfRecordExists($country, $event) {

		$date = date('Y-m-d');

		$conn = DbConnection::connectToDb();
		$stmt = $conn->prepare("SELECT * FROM events AS e WHERE e.country='".$country."' AND e.event_name='".$event."' AND e.date='".$date."'");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(empty($result)){
			return false;
		}else{
			return true;
		}

	}

}