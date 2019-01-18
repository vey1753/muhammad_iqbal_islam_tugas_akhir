<?php

namespace TVMaze;

/**
 * Class Episode
 *
 * @package TVMaze
 */
class Episode extends TVProduction {

	/**
	 * @var
	 */
	public $season;
	/**
	 * @var
	 */
	public $number;
	/**
	 * @var
	 */
	public $airdate;
	/**
	 * @var
	 */
	public $airtime;
	/**
	 * @var
	 */
	public $airstamp;
	/**
	 * @var
	 */
	public $runtime;
	/**
	 * @var string
	 */
	public $summary;

	/**
	 * @param $episode_data
	 */
	public function __construct($episode_data){
		parent::__construct($episode_data);
		$this->season = $episode_data['season'];
		$this->number = $episode_data['number'];
		$this->airdate = $episode_data['airdate'];
		$this->airtime = $episode_data['airtime'];
		$this->airstamp = $episode_data['airstamp'];
		$this->runtime = $episode_data['runtime'];
		$this->summary = strip_tags($episode_data['summary']);
	}

}