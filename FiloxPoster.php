<?php
defined ( 'ABSPATH' ) or die ( 'Nope, not accessing this' );

/**
* @package Naq
*/
/*
Plugin Name: Filox Poster
Description: Plugin to register posts from front-end view
Version: 1.0.0
Author: Rodolfos Thymis
License: GPLv2 or later
*/

class naq_class {

  public function __construct(){
    add_action ('init', array($this, 'example_function'));

  }

}

include(plugin_dir_path(__FILE__) . 'inc/flx_post_shortcode.php');
