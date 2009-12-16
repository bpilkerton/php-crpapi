<?php
/**
 * class php-crpapi
 * Simple PHP client library for working with the Center for Responsive Politics' API
 * Information on CRP's API can be found at http://www.opensecrets.org/action/api_doc.php
 * Information on using this class, including examples at http://github.com/bpilkerton/php-crpapi
 * @author Ben Pilkerton <bpilkerton@gmail.com>
 * @version 0.1
 */

class crpData {

	function __construct($method=NULL,$params=NULL) {
		$this->apikey = "";
		$this->baseurl = "http://api.opensecrets.org/";
		$this->output = "json";
		
		//Allow output type to be overridden on object instantiation
		$this->output = isset($params['output']) ? $params['output']: $this->output;
		$this->method = $method;
		self::loadParams($params);
	}

	private function loadParams($params) {
		$this->url = $this->baseurl . "?method=" . $this->method . 
			"&apikey=" . $this->apikey;
		
		foreach ($params as $key=>$val) {
			$this->url .= "&" . $key . "=" . $val;
			$this->$key = $val;
		}
		
		return;
	}
	
	public function getData() {
	
		$this->rawdata = file_get_contents($this->url);
		
		switch ($this->output) {
			case "json":
				$this->data = json_decode($this->rawdata);
				break;
			case "xml":
				$this->data = simplexml_load_string($this->rawdata);
				break;
		}
	
		return $this->data;
	}
}

?>
