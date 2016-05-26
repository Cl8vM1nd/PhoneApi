<?php 
namespace Service;

class MusicService extends \Base\Service 
{
	public function __construct($app) {
		$this ->app = $app;
	}

	/**
	*	Updating users choice
	*	@param $type - one of musical instruments [Guitar, Electric, Bass, Banjo]
	*/
	public function updateMusic($type) {
		$this ->app['MusicModel'] ->updateMusic($type);
	}


	/**
	*	Show Music table with percentage
	*	@param $name - user name
	**/
	public function showMusic($name) {
		$return = $this ->app['MusicModel'] ->getMusic();
		for($i = 0; $i < sizeof($return); $i++) {
			foreach ($return[$i] as $key => $value) {
				if($key == 'instr_value') {
					//Calculating percentage
					$return[$i][$key] =  round ($value * $this ->app['music.ratio'], 2) . '%';
				}
			}
		}
		$name = ($name) ? $name : 'Username';
		$return['name'] = $name;
		return $return;
	}
}
