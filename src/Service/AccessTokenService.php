<?php 
namespace Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccessTokenService extends \Base\Service
{
	// MAIN API KEY OF APP
	const APP_API_KEY 	= 'W|eU/9h.pBIVi^Ei"V^PcIT)Cbsvlfi)-eH1U\(z';
	// Token min length
	protected $min 		= 15;
	// Token max length
	protected $max 		= 30;
	protected $letters 	= 'abcdefghijklmnopqrstuvwxyz0123456789';

	public function __construct($app) {
		$this ->app = $app;
	}

	/*
	*	Generating Token
	**/
	public function generateToken() { 
		mt_srand($this ->make_seed());
		// Selecting random length
		$length 	= mt_rand($this ->min, $this ->max);
		$cryptList 	= null;

		for($i = 0; $i < $length; $i++) {
			mt_srand($this ->make_seed());
			// Big or small?
			if(mt_rand(0, 1)) {
				$cryptList .= strtolower($this ->letters[mt_rand(0, strlen($this ->letters) - 1)]);   
			} else {
				$cryptList .= strtoupper($this ->letters[mt_rand(0, strlen($this ->letters) - 1)]);   
			}     
		} 

		$array_mix = preg_split('//', $cryptList);  
		mt_srand($this ->make_seed());
		// Shuffle it
		while($length--) {
		  shuffle($array_mix);
		}

		return password_hash(implode($array_mix, ''), PASSWORD_BCRYPT);
	}

	/**
	*	Registering app in database and giving an unique identifier 
	*	@param $app_id = Unique app const
	*	@return app_unique_id
	*/
	public function register($app_id) {
		if(self::APP_API_KEY != $app_id) {
			throw new NotFoundHttpException('Wrong key');
			return;
		} else {
			$id = $this ->generateToken();
			$this ->app['AccessModel'] ->registerApp($id);
			return json_encode(array('id' => $id));	
		}
	}

	/*
	*	Check if token is right every request except where we get it
	*/
	public function check() {
		$token 	= $this ->safe($this->app['request'] ->query ->get('token'));
		$action = $this ->safe($this->app['request'] ->attributes ->get('action'));

		// If its not reserved one
		if($action != 'getToken' && $action != 'register') {
			// Our tokens are bigger then 30 letter for sure
			if(strlen($token) < 30) {
				throw new NotFoundHttpException('Wrong token');
			}
			
			$result = $this ->app['AccessModel'] ->getToken($token);
			if(!$result) {
				throw new NotFoundHttpException('Wrong token');
			}
		}
	}


}