<?php


namespace TVMaze;

/**
 * @package TVMaze
 */
class TVProduction {

	/**
	 * @var
	 */
	public $id;
	/**
	 * @var
	 */
	public $url;
	/**
	 * @var
	 */
	public $name;
	/**
	 * @var
	 */
	public $images;
	/**
	 * @var
	 */
	public $mediumImage;
	/**
	 * @var
	 */
	public $originalImage;

	/**
	 * @param $production_data
	 */
	public function __construct($production_data){
		$this->id = $production_data['id'];
		$this->url = $production_data['url'];
		$this->name = $production_data['name'];
		$this->images = $production_data['image'];
		$this->mediumImage = $production_data['image']['medium'];
		$this->originalImage = $production_data['image']['original'];
	}

}