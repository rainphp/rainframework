<?php
require_once LIBRARY_DIR . "Loader.php";

$loader = Loader::get_instance()
 ->initSettings() //load the settings
 //->initDB()
 ->initSession()
 ->initLanguage() //set the language
 //->authUser()
 ->initTheme() //set theme
 //->initJS()
;
