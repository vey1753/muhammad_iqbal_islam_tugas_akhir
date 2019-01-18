<?php

namespace TVMaze;

Class AKA
{
	/**
	 * @param 
	 */
	public function __construct($aka_data)
	{
		$this->akas = '';
		
		if(!empty($aka_data['name'])) {
			$this->akas = $aka_data['name'];
		}
	}
}
