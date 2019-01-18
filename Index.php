<?php

require_once 'Includes.php';

$Client = new TVMaze\Client;

$Client->TVMaze->search('Arrow');
$Client->TVMaze->singleSearch('Flash');
$Client->TVMaze->getShowBySiteID('TVRage', 33272);

?>