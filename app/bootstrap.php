<?php
require_once LIBRARY_DIR . "Loader.php";

$loader = Loader::get_instance();
$loader->init_settings();//load the settings
$loader->init_db();
$loader->init_session();
$loader->init_language();//set the language
$loader->auth_user();
//$loader->auth_user();
$loader->init_theme();//set theme
$loader->init_js();
