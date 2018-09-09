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

					break;
				case 'csv':
					http_response_code(200);

					break;
				case 'xml':
					http_response_code(200);

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

		if(!isset($body['country']) || !isset($body['event'])){
			http_response_code(400);
			return json_encode([
				'message' => 'Bad request!',
				'code'  => http_response_code()
			]);
		}else{
			$recordExists = EventRepo::checkIfRecordExists($body['country'], $body['event']);
			if(!$recordExists){
				EventRepo::saveEvent($body['country'], $body['event']);
			}else{

			}

		}
	}

}