<?php 
namespace Base;

class Model
{
	public function __construct($app) {
		$this ->app = $app;
	}

	/*
	*	Create queryBuilder
	*/
	protected function createQuery() {
		return $this ->app['db']->createQueryBuilder();
	}

	/* Make var safe */
	protected function safe($var) {
		return trim(strip_tags($var));
	}
}
