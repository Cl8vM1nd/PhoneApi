<?php 

namespace Model;
use Doctrine\DBAL\Query\QueryBuilder;

class AccessModel extends \Base\Model
{
	public function __construct($app) {
		$this ->app = $app;
	}

	/* If token exists delete it */
	public function deleteTokenIfExist($id) {
		$query = $this ->createQuery();
		$result = $query
            ->select('tokens.app_id')
            ->from('app.tokens', 'tokens')
            ->where('tokens.app_id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetch();

        // delete it
        if($result) {
        	$query = $this ->createQuery();
        	$query
        	->delete('app.tokens')
            ->where('tokens.app_id = :id')
            ->setParameter('id', $id)
            ->execute();
        }
        return true;
	}

	/**
	*	Check if there is token with that id
	*	@param $id - app id
	*/
	public function getTokenById($id) {
		$query = $this ->createQuery();
		$result = $query
        ->select('users.app_key')
        ->from('app.users', 'users')
        ->where('users.app_key = :id')
        ->setParameter('id', $id)
        ->execute()
        ->fetch();

        return $result;
	}

	/**
	*	Saving new token to database
	*	@param $id - app id
	*	@param $token - new token
	**/
	public function saveToken($id, $token) {
    	// Save app_id and a new token
    	$this->app['db']->insert('app.tokens', array(
    		'app_id' => $id,
    		'token'  => $token));
	}

	/**
	*	Return token if it exist
	*	@param $token - token
	**/
	public function getToken($token) {
		$query = $this ->createQuery();
		$result = $query
        ->select('tokens.token')
        ->from('app.tokens', 'tokens')
        ->where('tokens.token = :token')
        ->setParameter('token', $token)
        ->execute()
        ->fetchAll();

        return $result;
	}

	/**
	*	Register a new app
	*	@param $id - app_id
	**/
	public function registerApp($id) {
		$this->app['db']->insert('app.users', array('app_key' => $id));
	}

}
