<?php

// default database

$db['default']['driver']   = "mysql";
$db['default']['hostname'] = "localhost";
$db['default']['username'] = "root";
$db['default']['password'] = "root";
$db['default']['database'] = "rainframework2";
$db['default']['path']     = ""; // only for sqlite

if (!defined("DB_PREFIX"))
	define("DB_PREFIX", "RAIN_");

?>