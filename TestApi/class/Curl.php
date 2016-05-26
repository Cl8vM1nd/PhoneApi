<?php 

class Curl 
{

	private $curl 					= null;
	private $curlOptionsDefault 	= array (
			CURLOPT_CONNECTTIMEOUT	=> 10,
			CURLOPT_TIMEOUT			=> 20,
			CURLOPT_USERAGENT		=> 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MRA 4.4 (build 01331))',
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER			=> false,
			CURLOPT_POST			=> false,
		);

	private $httpsOptions 			= array(
			CURLOPT_SSL_VERIFYPEER	=> false,
			CURLOPT_SSL_VERIFYHOST	=> false,
			CURLOPT_VERBOSE			=> true,
		);

	private $headers 				= array (
	    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*;q=0.8',
	    'Accept-Language: ru,en-us;q=0.7,en;q=0.3',
	    'Accept-Encoding: deflate',
	    'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7'
	);

	private $start					= null;
	private $end					= null;

	public function __construct($opt = null) {
		if( $this ->curl = curl_init() ) { 
			foreach ($this ->curlOptionsDefault as $key => $value) {
				curl_setopt($this ->curl, $key, $value);
			}

			curl_setopt($this ->curl, CURLOPT_HTTPHEADER, $this ->headers);

			if( ($opt) && ( is_array($opt) ) ) {
				foreach ($opt as $key => $value) {
					curl_setopt($this ->curl, $key, $value);
				}
			}
		} else {
			return new Exception('Curl not initialized');
		}
	}

	private function https($https) {
		if($https) {
			foreach ($this ->httpsOptions as $key => $value) {
				curl_setopt($this ->curl, $key, $value);
			}
		}
	}

	public function connect($url, $https) {
		$this ->https($https);
		$this ->start = microtime(true);

		curl_setopt($this ->curl, CURLOPT_URL, $url);
		$out = curl_exec($this ->curl);
		curl_close($this ->curl);

		$this ->end = microtime(true);
		$runtime = $this ->end - $this ->start;
		echo ' Speed: ' . round($runtime, 2) . "</br> data:</br> " . $out;
	}


}

?>