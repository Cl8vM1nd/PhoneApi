<?php 
namespace Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class AppController extends \Silex\Controller 
{
	public function __construct($app) {
		$this ->app = $app;
	}

	/**
	*	Main rote function
	*	@param $action - action
	**/
	public function route($action = 'index') {
		$action = $this ->safe($action);
		switch ($action) {
			case 'getToken':
				$id = $this ->safe($this->app['request'] ->query->get('id'));
				return $this ->getToken($id);
				break;

			case 'register':
				$id = $this ->safe($this->app['request'] ->query->get('id'));
				return $this ->app['AccessTokenService'] ->register($id);
				break;

			case 'select':
				$type = $this ->safe($this->app['request'] ->query->get('type'));
				$name = $this ->safe($this->app['request'] ->query->get('name'));
				return $this ->selectMusic($type, $name);
				break;

			default:
				throw new NotFoundHttpException('Page ' . $action . ' not found');
				break;
		}

	}

	/**
	*	Generating Token 
	*	@param $id - app id
	*/
	protected function getToken($id) {
		// if there is data stored
		if(!empty($this ->app['MemcacheService'] ->get($id))) {
			return $this ->app['MemcacheService'] ->get($id);
		} else {
			// Check if id is right
			$result = $this ->app['AccessModel'] ->getTokenById($id);
			
            if(!$result) {
            	throw new NotFoundHttpException('Invalid id');
            } else {
            	// If token exist -> delete it
				$this ->app['AccessModel'] ->deleteTokenIfExist($id);

            	// Generating new token
            	$newToken = $this ->app['AccessTokenService'] ->generateToken();
            	
            	// Save app_id and a new token
            	$this ->app['AccessModel'] ->saveToken($id, $newToken);
            	
            	// Our data array
            	$data = json_encode(array(
					'time' 		=> time(),
					'expires' 	=> $this ->app['memcache.expires'],
					'token' 	=> $newToken
				));
				// Saving it to memcache
				$this ->app['MemcacheService'] ->set($id, $data);

				return $data;

            }
		}
	}

	/**
	*	Selecting one of instruments
	*	@param $type - one of musical instruments [Guitar, Electric, Bass, Banjo]
	*	@param $name - name of user
	*/
	protected function selectMusic($type, $name) {
		$this ->app['MusicService'] ->updateMusic($type);
		return json_encode($this ->app['MusicService'] ->showMusic($name));
	}


	/* Make var safe */
	protected function safe($var) {
		return trim(strip_tags($var));
	}


}