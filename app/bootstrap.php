<?php
require_once LIBRARY_DIR . "Loader.php";

$loader = Loader::get_instance()
 ->init_settings() //load the settings
//->init_db()
 ->init_session()
 ->init_language() //set the language
//->auth_user()
 ->init_theme(); //set theme
// ->init_js();
