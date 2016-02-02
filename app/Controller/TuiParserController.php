<?php
App::uses('AppController', 'Controller');

class TuiParserController extends AppController {

	const USER = "sollicit";
	const PASSWORD = "ac0pX97n";
	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
//	public $uses = array();
	
	public function beforeFilter() {
		parent::beforeFilter();
		
		$this->autoRender = false;
		$this->autoLayout = false;
	}
	
	public function getArrivals($departure) {
		$flightroutes = $this->parse("http://tstapi.jetair.be/json/1F/flightroutes/?locale=en_GB&departureairport=".$departure);
		$arrivals = [];
		
		foreach($flightroutes["routes"] as $fr) {
			$arrivals[$fr["RetCode"]] = $fr["RetName"].(empty($fr["RetCountryName"]) ? null : " - ".$fr["RetCountryName"]);
		}
		
		asort($arrivals);
		
		return json_encode($arrivals);
	}
	
	public function getAvailability() {
		$data = $this->request->data;
		$flightType = $data["flightType"];
		
		//Convert dates
		$depDate = DateTime::createFromFormat("d/m/Y", $data["scheduleDeparture"]);
		
		if($depDate) {
			$data["scheduleDeparture"] = $depDate->format("Ymd");
		}
		
		if($flightType == "round-trip") {
			$retDate = DateTime::createFromFormat("d/m/Y", $data["scheduleReturn"]);
			
			if($retDate) {
				$data["scheduleReturn"] = $retDate->format("Ymd");
			}
		}
		
		$this->TuiParser->set($data);
		
		if($this->TuiParser->validates()) {
			$this->autoRender = true;
			$this->layout = "ajax";
			$url = sprintf("http://tstapi.jetair.be/json/2F/flightavailability/?locale=en_GB&departureairport=%s&destinationairport=%s&departuredate=%s&adults=%s&children=%s&infants=%s", $data["departure"], $data["arrival"], $data["scheduleDeparture"], $data["adults"], $data["children"], $data["babies"]);

			if($flightType == "round-trip") {
				$url .= sprintf("&returndepartureairport=%s&returndestinationairport=%s&returndate=%s", $data["arrival"], $data["departure"], $data[("scheduleReturn")]);
			}

			$availability = $this->parse($url);

			$this->set(compact("availability", "flightType"));
		} else {
			return json_encode($this->TuiParser->validationErrors);
		}
	}
	
	public function getDepartures() {
		$flightroutes = $this->parse("http://tstapi.jetair.be/json/1F/flightroutes/?locale=en_GB");
		
		foreach($flightroutes["routes"] as $fr) {
			$departures[$fr["DepCode"]] = $fr["DepName"].(empty($fr["DepCountryName"]) ? null : " - ".$fr["DepCountryName"]);
		}
		
		asort($departures);
		
		return $departures;
	}
	
	public function getSchedule($departure, $arrival, $roundTrip = false) {
		$url = "http://tstapi.jetair.be/json/1F/flightschedules/?locale=en_GB&departureairport=".$departure."&destinationairport=".$arrival;
		
		if($roundTrip) {
			$url .= "&returndepartureairport=".$arrival."&returndestinationairport=".$departure;
		}
		
		$schedules = $this->parse($url);
		$dates = [
			"OUT" => [],
			"RET" => []
		];
		
		foreach($schedules["flightSchedule"]["OUT"] as $date) {
			$time = strtotime($date["date"]);
			$dates["OUT"][date("Ymd", $time)] = date("d/m/Y", $time);
		}
		
		if(isset($schedules["flightSchedule"]["RET"])) {
			foreach($schedules["flightSchedule"]["RET"] as $date) {
				$time = strtotime($date["date"]);
				$dates["RET"][date("Ymd", $time)] = date("d/m/Y", $time);
			}
		}
		
//		asort($dates["OUT"]);
//		asort($dates["RET"]);
		
		return json_encode($dates);
	}

	public function parse($url) {
		$curlOptions = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
			CURLOPT_USERPWD => self::USER.":".self::PASSWORD,
			CURLOPT_HEADER => false,
			CURLOPT_URL => rawurldecode($url),
			CURLOPT_HTTPHEADER => ["Cache-Control: max-age=3600, must-revalidate"]
		];
		$ch = curl_init();
		curl_setopt_array($ch, $curlOptions);
		$data = curl_exec($ch);
		curl_close($ch);
		
		if($data === false) {
			return false;
		}
		
		$result = json_decode($data, true);
				
		return $result;
	}
}