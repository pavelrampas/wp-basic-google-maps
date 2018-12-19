<?php

class Wp_Basic_Google_Maps {

	const DB_TABLE = 'basic_google_maps';

	const SETTINGS = 'wp_basic_google_maps_settings';
	const GOOGLE_API_KEY = 'google_api_key';
	const MAP_HEIGHT = 'map_height';
	const MAP_ZOOM = 'map_zoom';
	const MAP_CENTER_LATITUDE = 'map_center_latitude';
	const MAP_CENTER_LONGITUDE = 'map_center_longitude';

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . self::DB_TABLE;

		if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
			$sql = "CREATE TABLE " . $table_name . " (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` text NULL,
				`web` text NULL,
				`address` text NULL,
				`phone` varchar(32) NULL,
				`email` text NULL,
				`latitude` varchar(32) NULL,
				`longitude` varchar(32) NULL,
				UNIQUE KEY id (id)
				);";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			$pin = [
				'name' => 'České Budějovice',
				'web' => 'www.domain.cz',
				'address' => 'České Budějovice (Czech Republic)',
				'phone' => '000-000-000',
				'email' => 'email@address.com',
				'latitude' => '48.9745392',
				'longitude' => '14.4742786',
			];
			$wpdb->insert( $table_name, $pin );
		}
	}

	private function define_constants() {
		$this->define( 'WP_BASIC_GOOGLE_MAPS', plugin_dir_path( __FILE__ ) );
		$this->define( 'WP_BASIC_GOOGLE_MAPS_ADMIN_DIR', WP_BASIC_GOOGLE_MAPS . 'admin/' );
		$this->define( 'WP_BASIC_GOOGLE_MAPS_MODEL_DIR', WP_BASIC_GOOGLE_MAPS . 'model/' );
		$this->define( 'WP_BASIC_GOOGLE_MAPS_ABSOLUTE_DIR', '/wp-content/plugins/wp-basic-google-maps/' );
	}

	private function includes() {
		if ( is_admin() ) {
			// model
			include_once WP_BASIC_GOOGLE_MAPS_MODEL_DIR . 'class-wp-basic-google-maps-model.php';
			//admin
			include_once WP_BASIC_GOOGLE_MAPS_ADMIN_DIR . 'class-wp-basic-google-maps-admin.php';
			include_once WP_BASIC_GOOGLE_MAPS_ADMIN_DIR . 'class-wp-basic-google-maps-admin-settings.php';
			include_once WP_BASIC_GOOGLE_MAPS_ADMIN_DIR . 'class-wp-basic-google-maps-admin-pins.php';
		} else {
			// model
			include_once WP_BASIC_GOOGLE_MAPS_MODEL_DIR . 'class-wp-basic-google-maps-model.php';
			// shortcodes
			include_once WP_BASIC_GOOGLE_MAPS . 'class-wp-basic-google-maps-shortcodes.php';
		}
	}

	private function init_hooks() {
		new Wp_Basic_Google_Maps_Model();
		if ( is_admin() ) {
			new Wp_Basic_Google_Maps_Admin();
			new Wp_Basic_Google_Maps_Admin_Pins();
			new Wp_Basic_Google_Maps_Admin_Settings();
		} else {
			new Wp_Basic_Google_Maps_Shortcodes();
		}
	}

	/**
	 * Define constant if not already set
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
}
