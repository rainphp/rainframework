<?php

#--------------------------------
# Base application directory
#--------------------------------
$app = "app";

#--------------------------------
# Load the class
#--------------------------------
require_once "config/directory.php";

#--------------------------------
# Load the bootstrap
#--------------------------------
require_once "$app/bootstrap.php";

#--------------------------------
# Auto Load the Controller
# init_route set the controller/action/params
# to load the controller
#--------------------------------
$loader->auto_load_controller();

#--------------------------------
# Load model
# load the model and assign the result
# @params model, action, params, assign_to
#--------------------------------
$loader->load_menu();

#--------------------------------
# Assign Layout variables
#--------------------------------
$loader->assign( 'title', 'RainFramework' );

#--------------------------------
# Print the layout
#--------------------------------
$loader->draw();
