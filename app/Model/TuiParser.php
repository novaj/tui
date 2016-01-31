<?php
App::uses('AppModel', 'Model');

/**
 * CakePHP TuiParser
 */
class TuiParser extends AppModel {
	public $useTable = false;
	public $validate = [
		"departure" => [
			"rule" => "notEmpty",
		],
		"arrival" => [
			"rule" => "notEmpty",
		],
		"scheduleDeparture" => [
			"rule" => "notEmpty",
		],
		"scheduleReturn" => [
			"rule" => ["checkScheduleReturn"],
		],
		"adults" => [
			"rule" => "notEmpty",
		]
	];
	
	public function checkScheduleReturn($check) {
		if($this->data[$this->alias]["flightType"] == "round-trip") {
			return !empty($check["scheduleReturn"]);
		} else {
			return true;
		}
	}
}