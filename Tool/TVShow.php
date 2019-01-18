<?php


namespace JPinkney\TVMaze;

/**
 * @package JPinkney\TVMaze
 */
class TVShow extends TVProduction{

	/**
	 * @var
	 */
	public $type;

	/**
	 * @var
	 */
	public $language;

	/**
	 * @var
	 */
	public $genres;

	/**
	 * @var
	 */
	public $status;

	/**
	 * @var
	 */
	public $runtime;

	/**
	 * @var
	 */
	public $premiered;

	/**
	 * @var
	 */
	public $rating;

	/**
	 * @var
	 */
	public $weight;

	/**
	 * @var
	 */
	public $network_array;

	/**
	 * @var
	 */
	public $network;

	/**
	 * @var
	 */
	public $webChannel;

	/**
	 * @var
	 */
	public $externalIDs;

	/**
	 * @var string
	 */
	public $summary;

	/**
	 * @var
	 */
	public $nextAirDate;

	/**
	 * @var bool|string
	 */
	public $airTime;

	/**
	 * @var bool|string
	 */
	public $airDay;

	/**
	 * @var
	 */
	public $akas;

	/**
	 * @var
	 */
	public $country;

	/**
	 * @param $show_data
	 */
	public function __construct($show_data){
		parent::__construct($show_data);
		$this->type = $show_data['type'];
		$this->language = $show_data['language'];
		$this->genres = $show_data['genres'];
		$this->status = $show_data['status'];
		$this->runtime = $show_data['runtime'];
		$this->premiered = $show_data['premiered'];
		$this->rating = $show_data['rating'];
		$this->weight = $show_data['weight'];
		$this->network_array = $show_data['network'];
		$this->network = $show_data['network']['name'];
		$this->webChannel = $show_data['webChannel'];
		$this->country = $show_data['network']['country']['code'];
		if ($show_data['webChannel'] !== null && $show_data['webChannel']['country'] !== null) {
			$this->country = $show_data['webChannel']['country']['code'];
		}
		$this->externalIDs = $show_data['externals'];
		$this->summary = strip_tags($show_data['summary']);
		$this->akas = (isset($show_data['_embedded']['akas']) ? $show_data['_embedded']['akas'] : null);

		$current_date = date('Y-m-d');
		if (!empty($show_data['_embedded']['episodes'])) {
			foreach ($show_data['_embedded']['episodes'] as $episode) {
				if ($episode['airdate'] >= $current_date) {
					$this->nextAirDate = $episode['airdate'];
					$this->airTime = date('g:i A', $episode['airtime']);
					$this->airDay = date('l', strtotime($episode['airdate']));
					break;
				}
			}
		}

	}

	/**
		 * @return 
	 */
	public function isEmpty(){
		return($this->id == null || ($this->id == 0 && $this->url == null && $this->name == null));
	}

}
