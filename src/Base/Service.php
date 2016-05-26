<?php 
namespace Base;

class Service 
{
	// making seed
	protected function make_seed() {
	    list($usec, $sec) = explode(' ', microtime());
	    return (float) $sec + ((float) $usec * 100000);
	}

	/* Make var safe */
	protected function safe($var) {
		return trim(strip_tags($var));
	}
}