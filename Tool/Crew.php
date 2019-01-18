<?php


namespace JPinkney\TVMaze;

/**
 *
 *
 * @package TVMaze
 */
class Crew {

	/**
	 * @var
	 */
	public $type;

	/**
	 * @param $crew_data
	 */
	public function __construct($crew_data){
		$this->type = $crew_data['type'];
	}

}