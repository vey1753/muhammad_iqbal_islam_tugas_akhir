<?php



namespace TVMaze;

/**
 * @package TVMaze
 */
class Client
{
	/**
	 * @var TVMaze
	 */
	public $TVMaze;

	public function __construct()
	{
		$this->TVMaze = new TVMaze();
	}
}
