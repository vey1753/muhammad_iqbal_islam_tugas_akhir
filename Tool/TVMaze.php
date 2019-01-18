<?php

namespace JPinkney\TVMaze;

class TVMaze {

	CONST APIURL = 'https://api.tvmaze.com';

	/**

	 * @param $show_name
	 * @return array
	 */
	public function search($show_name)
	{
		$relevant_shows = false;
		$url = self::APIURL. '/search/shows?q=' . rawurlencode($show_name);

		$shows = $this->getFile($url);

		if (is_array($shows)) {
			$relevant_shows = [];
			foreach ($shows as $series) {
				$TVShow = new TVShow($series['show']);
				$relevant_shows[] = $TVShow;
			}
		}
		return $relevant_shows;
	}

	/**

	 *
	 */
	public function singleSearch($show_name){

		$url = self::APIURL. '/singlesearch/shows?q=' . rawurlencode($show_name) . '&embed=episodes';
		$shows = $this->getFile($url);

		$episode_list = [];
		foreach($shows['_embedded']['episodes'] as $episode){
			$ep = new Episode($episode);
			$episode_list[] = $ep;
		}

		$TVShow = new TVShow($shows);

		return [$TVShow, $episode_list];
	}

	/**
	 *
	 * @param $show_name
	 *
	 * @return array
	 */
	public function singleSearchAkas($show_name)
	{
		$TVShow = false;
		$url = self::APIURL . '/singlesearch/shows?q=' . rawurlencode($show_name) . '&embed=akas';
		$shows = $this->getFile($url);

		if (is_array($shows)) {
			$TVShow = new TVShow($shows);
		}
		return [$TVShow];
	}

	/**
	 *
	 * @param $site
	 * @param $ID
	 *
	 * @return TVShow
	 */
	public function getShowBySiteID($site, $ID){
		$site = strtolower($site);
		$url = self::APIURL.'/lookup/shows?'.$site.'='.$ID;
		$show = $this->getFile($url);

		return new TVShow($show);
	}

	/**
	 * 
	 * @param $name
	 *
	 * @return array
	 */
	public function getPersonByName($name){
		$name = strtolower($name);
		$url = self::APIURL.'/search/people?q='.$name;
		$person = $this->getFile($url);

		$people = [];
		foreach($person as $peeps){
		$people[] = new Actor($peeps['person']);
		}

		return $people;
	}

	/**
	
	 *
	 * @param null $country
	 * @param null $date
	 *
	 * @return array
	 */
	public function getSchedule($country = null, $date = null){
		if($country != null && $date != null) {
			$url = self::APIURL . '/schedule?country=' . $country .'&date='. $date;
		} else if($country == null && $date != null){
			$url = self::APIURL . '/schedule?date=' . $date;
		} else if($country != null && $date == null){
			$url = self::APIURL . '/schedule?country=' . $country;
		} else{
			$url = self::APIURL . '/schedule';
		}

		$schedule = $this->getFile($url);

		$show_list = [];
		foreach($schedule as $episode){
			$ep = new Episode($episode);
			$show = new TVShow($episode['show']);
			array_push($show_list, $show, $ep);
		}

		return $show_list;
	}

	/**
	 *
	 *
	 * @param  
	 * @param 
	 *
	 * @return 
	 */
	public function getShowByShowID($ID, $embed_cast=null){
		if($embed_cast === true){
			$url = self::APIURL.'/shows/'.$ID.'?embed=cast';
		}else{
			$url = self::APIURL.'/shows/'.$ID;
		}

		$show = $this->getFile($url);

		$cast = [];
		foreach($show['_embedded']['cast'] as $person){
			$actor = new Actor($person['person']);
			$character = new Character($person['character']);
			$cast[] = [$actor, $character];
		}

		$TVShow = new TVShow($show);

		return $embed_cast === true ? [$TVShow, $cast] : [$TVShow];
	}
	
	/**
	 * 
	 *
	 *
	 * @param $ID
	 *
	 * @return 
	 */
	public function getShowAKAs($ID)
	{
		$url = self::APIURL . '/shows/' . $ID . '/akas';

		$akas = $this->getFile($url);

		$AKA = new AKA($akas);

		if (!empty($akas['name'])) {

			return $AKA;
		}

		return false;
	}

	/**
	 * @param $ID
	 *
	 * @return array
	 */
	public function getEpisodesByShowID($ID){

		$url = self::APIURL.'/shows/'.$ID.'/episodes';

		$episodes = $this->getFile($url);

		$allEpisodes = [];
		foreach($episodes as $episode){
			$ep = new Episode($episode);
			$allEpisodes[] = $ep;
		}

		return $allEpisodes;
	}

	/**
	 *
	 * @param $ID
	 * @param $season
	 * @param $episode
	 *
	 * @return Episode|mixed
	 */
	public function getEpisodeByNumber($ID, $season, $episode)
	{
		$ep = false;
		$url = self::APIURL . '/shows/' . $ID . '/episodebynumber?season='. $season . '&number=' . $episode;
		$response = $this->getFile($url);
		if (is_array($response)) {
			$ep = new Episode($response);
		}
		return $ep;
	}

	/**
	 *
	 * @param $ID
	 * @param $airdate
	 *
	 * @return Episode|mixed
	 */
	public function getEpisodesByAirdate($ID, $airdate)
	{
		$url = self::APIURL . '/shows/' . $ID . '/episodesbydate?date=' . date('Y-m-d', strtotime($airdate));
		$episodes = $this->getFile($url);

		$allEpisodes = [];
		if (is_array($episodes)) {
			foreach ($episodes as $episode) {
				$ep = new Episode($episode);
				$allEpisodes[] = $ep;
			}
		}
		return $allEpisodes;
	}

	/**
	 *
	 * @param $ID
	 *
	 * @return array
	 */
	public function getCastByShowID($ID){
		$url = self::APIURL.'/shows/'.$ID.'/cast';
		$people = $this->getFile($url);

		$cast = [];
		foreach($people as $person){
			$actor = new Actor($person['person']);
			$character = new Character($person['character']);
			$cast[] = [$actor, $character];
		}

		return $cast;
	}

	/**
	 *
	 * @param null $page
	 *
	 * @return array
	 */
	public function getAllShowsByPage($page=null){
		if($page == null){
			$url = self::APIURL.'/shows';
		}else{
			$url = self::APIURL.'/shows?page'.$page;
		}

		$shows = $this->getFile($url);

		$relevant_shows = [];
		foreach($shows as $series){
			$TVShow = new TVShow($series);
			$relevant_shows[] = $TVShow;
		}
		return $relevant_shows;
	}

	/**
	 * 
	 *
	 * @param 
	 *
	 * @return 
	 */
	public function getPersonByID($ID){
		$url = self::APIURL.'/people/'.$ID;
		$show = $this->getFile($url);
		return new Actor($show);
	}

	/**
	 * 
	 *
	 * @param 
	 *
	 * @return 
	 */
	public function getCastCreditsByID($ID){
		$url = self::APIURL.'/people/'.$ID.'/castcredits?embed=show';
		$castCredit = $this->getFile($url);

		$shows_appeared = [];
		foreach($castCredit as $series){
			$TVShow = new TVShow($series['_embedded']['show']);
			$shows_appeared[] = $TVShow;
		}
		return $shows_appeared;
	}

	/**
	 *
	 *
	 * @param 
	 *
	 * @return 
	 */
	public function getCrewCreditsByID($ID){
		$url = self::APIURL.'/people/'.$ID.'/crewcredits?embed=show';
		$crewCredit = $this->getFile($url);

		$shows_appeared = [];
		foreach($crewCredit as $series){
			$position = $series['type'];
			$TVShow = new TVShow($series['_embedded']['show']);
			$shows_appeared[] = [$position, $TVShow];
		}
		return $shows_appeared;
	}

	/**
	 * 
	 *
	 * @param 
	 *
	 * @return 
	 */
	private function getFile($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($result, TRUE);
		if (is_array($response) && count($response) > 0 && (!isset($response['status']) || $response['status'] != '404')) {
			return $response;
		}
		
		return false;
	}

}
