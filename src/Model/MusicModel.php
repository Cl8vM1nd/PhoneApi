<?php 

namespace Model;
use Doctrine\DBAL\Query\QueryBuilder;

class MusicModel extends \Base\Model
{
	public function __construct($app) {
		$this ->app = $app;
	}

	/*
	*	Get Music Table
	**/
	public function getMusic() {
		$query = $this ->createQuery();
		$result = $query
        ->select('*')
        ->from('app.music', 'music')
        ->execute()
        ->fetchAll();

        return $result;
	}

	/**
	*	Updating users choice
	*	@param $type - one of musical instruments [Guitar, Electric, Bass, Banjo]
	*/
	public function updateMusic($type) {
		$query = $this ->createQuery();
		$query->update('app.music', 'm')
		->set('m.instr_value', 'm.instr_value + 1')
		->where('m.instr_name = :type')
        ->setParameter('type', $type)
        ->execute();
	}

}
