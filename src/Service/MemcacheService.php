<?php 
namespace Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MemcacheService extends \Base\Service
{
	protected $server = null;
	protected $time   = null;

	public function __construct($app) {
		$this ->app 	= $app;
		// Setting var time expiration
		$this ->time 	= $app['memcache.expires'];
		$this ->server 	= new \Memcache;
		$this ->server ->connect('127.0.0.1', 11211);
	}

	public function get($var) {
		return $this ->server ->get($var);
	}

	/**
	*	Saving var to memcache
	*	@param $key   - varible key
	*	@param $value - varible value
	*	@param $type  - memcache type. Can be 0 or MEMCACHE_COMPRESSED
	*	@param $time  - How long var to save?
	**/
	public function set($key, $value, $type = 0, $time = null) {
		$time = (!$time) ? $this ->time : $time;
		$this ->server ->set($key, $value, $type, $time);
	}

}