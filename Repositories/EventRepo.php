<?php
/**
 * Created by PhpStorm.
 * User: dulo
 * Date: 9/9/18
 * Time: 3:04 PM
 */
require_once 'IEvent.php';

class EventRepo implements IEvent {

	public static function saveEvent($country, $event, $date) {

		try{
			$conn = DbConnection::connectToDb();
			$stmt = $conn->prepare("INSERT INTO events (country, event_name, `date`, counter) VALUES(:country, :event_name, :event_date, :counter)");
			$stmt->execute(array(
					"country" => $country,
					"event_name" => $event,
					"event_date" => $date,
					"counter" => 1)
			);

			$conn = null;

		}catch (PDOException $e){
			echo $e->getMessage();
		}


	}

	public static function checkIfRecordExists($country, $event, $date) {

		try{
			$conn = DbConnection::connectToDb();
			$stmt = $conn->prepare("SELECT * FROM events AS e WHERE e.country='".$country."' AND e.event_name='".$event."' AND e.date='".$date."'");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if(empty($result)){
				$conn = null;
				return false;
			}else{
				$conn = null;
				return true;
			}
		}catch (PDOException $e) {
			echo $e->getMessage();
		}


	}

	public static function incrementEventCounter($country, $event, $date) {

		try{
			$conn = DbConnection::connectToDb();
			$stmt = $conn->prepare("UPDATE events SET counter=counter+1 WHERE country='".$country."' AND event_name='".$event."' AND `date`='".$date."'");
			$stmt->execute();
			$conn = null;
		}catch (PDOException $e) {
			echo $e->getMessage();
		}


	}

	public static function getEvents() {

		try{
			$conn = DbConnection::connectToDb();
			$stmt = $conn->prepare("SELECT t2.country, t2.event_name, SUM(t2.counter) 
								FROM (SELECT country, SUM(counter) as cnt 
									  FROM events e 
									  GROUP BY country 
									  ORDER BY cnt DESC LIMIT 5) t1 
								LEFT JOIN events t2 ON t1.country = t2.country 
								WHERE t2.date >= DATE(NOW()) - INTERVAL 7 DAY 
								GROUP BY t2.country, t2.event_name");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$conn = null;

			return $result;
		}catch (PDOException $e) {
			echo $e->getMessage();
		}

	}

}