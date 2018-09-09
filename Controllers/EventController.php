<?php
/**
 * Created by PhpStorm.
 * User: dulo
 * Date: 9/8/18
 * Time: 8:13 PM
 */
require_once 'Database/DbConnection.php';
require_once 'Repositories/EventRepo.php';

class EventController {

	public function __construct() {}

	public static function getEvents() {

		if(!isset($_GET['p'])){
			http_response_code(400);
			return json_encode([
				'message' => 'Bad request!',
				'code'  => http_response_code()
			]);
		}else{
			switch ($_GET['p']) {
				case 'json':
					http_response_code(200);
					$data = EventRepo::getEvents();
					return json_encode($data);
					break;
				case 'csv':
					http_response_code(200);
					$data = EventRepo::getEvents();
					EventController::exportToCsv($data);
					break;
				case 'xml':
					http_response_code(200);
					$data = EventRepo::getEvents();
					EventController::exportToXML($data);
					break;
				default:
					http_response_code(400);
					return json_encode([
						'message' => 'Bad request!',
						'code'  => http_response_code()
					]);
			}
		}

	}

	public static function saveEvent($request) {

		$body = $request->getBody();
		$date = date('Y-m-d');

		if(!isset($body['country']) || !isset($body['event']) || ($body['event'] != 'view' && $body['event'] != 'play' && $body['event'] != 'click' ) ){
			http_response_code(400);
			return json_encode([
				'message' => 'Bad request!',
				'code'  => http_response_code()
			]);
		}else{
			$recordExists = EventRepo::checkIfRecordExists($body['country'], $body['event'], $date);
			if(!$recordExists){
				EventRepo::saveEvent($body['country'], $body['event'], $date);
			}else{
				EventRepo::incrementEventCounter($body['country'], $body['event'], $date);
			}

		}
	}

	private static function exportToCsv($data) {
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.csv');
		$output = fopen("php://output", "w");
		fputcsv($output, array('country', 'event_name', 'SUM'));
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
		fclose($output);
	}

	private static function exportToXML($data) {

		$filePath = 'data.xml';

		$dom     = new DOMDocument('1.0', 'utf-8');

		$root      = $dom->createElement('events');

		for($i=0; $i<count($data); $i++){

			$country        =  $data[$i]['country'];

			$eventName      =  $data[$i]['event_name'];

			$sum            =  $data[$i]['SUM(t2.counter)'];

			$rowDom = $dom->createElement('row');
			$countryDom = $dom->createElement('country', $country);
			$eventNameDom = $dom->createElement('event-name', $eventName);
			$sumDom = $dom->createElement('sum', $sum);

			$root->appendChild($rowDom);
			$rowDom->appendChild($countryDom);
			$rowDom->appendChild($eventNameDom);
			$rowDom->appendChild($sumDom);

		}

		$dom->appendChild($root);

		$dom->save($filePath);

	}

}