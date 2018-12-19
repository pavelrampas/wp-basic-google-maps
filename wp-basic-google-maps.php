<?php

/*
Plugin Name:  WordPress Basic Google Maps
Plugin URI:   https://github.com/pavelrampas/wp-basic-google-maps
Description:  Google map with custom pins.
Version:      1.0.0
Author:       Pavel Rampas
Author URI:   https://pavelrampas.cz/
Text Domain:  wp-basic-google-maps
Domain Path:  /languages
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

WordPress Basic Google Maps is free software: you can redistribute it and/or
modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WordPress Basic Google Maps is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WordPress Basic Google Maps.
If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) {
     die;
}

load_plugin_textdomain(
	'wp-basic-google-maps',
	false,
	plugin_basename( dirname( __FILE__ ) ) . '/languages'
);

register_activation_hook( __FILE__, [ 'Wp_Basic_Google_Maps', 'activate' ] );

if ( ! class_exists( 'Wp_Basic_Google_Maps' ) ) {
	include_once ( 'class-wp-basic-google-maps.php' );
	new Wp_Basic_Google_Maps();
}
