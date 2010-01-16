<?php
/**
 * class php-crpapi
 * Simple PHP client library for working with the Center for Responsive Politics' API.
 * Information on CRP's API can be found at http://www.opensecrets.org/action/api_doc.php
 * Information on using this class, including examples at http://github.com/bpilkerton/php-crpapi
 * @author Ben Pilkerton <bpilkerton@gmail.com>
 * @version 0.2
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
		
		$this->fileHash = md5($method . "," . implode(",",$params));
		$this->cacheHash = "dataCache/" . $this->fileHash;
		$this->cacheTime = 86400; #one day
		
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
	
	public function getData($useCache=true) {
	
		if ($useCache and file_exists($this->cacheHash) and (time() - filectime($this->cacheHash) < $this->cacheTime)) {
		
			$this->cacheHit = true;
			$file = fopen($this->cacheHash,"r");
			$this->data = stream_get_contents($file);
			$this->data = gzuncompress($this->data);
			$this->data = unserialize($this->data);
			fclose($file);

		} else {
			$this->cacheHit = false;
			$this->data = file_get_contents($this->url);

			switch ($this->output) {
				case "json":
					$this->data = json_decode($this->data,true);
					break;
				case "xml":
					$this->data = simplexml_load_string($this->data);
					break;
				default:
					die("Unknown output type.  Use 'json' or 'xml'");
			}

			if ($useCache) {
				$file = fopen($this->cacheHash,"w");
				$store = serialize($this->data);
				$store = gzcompress($store);
				fwrite($file,$store);
				fclose($file);
			}
		}
		
		return $this->data;
	}
	
	function getCacheStatus() {
		return $this->cacheHit;
	}

}
?>
