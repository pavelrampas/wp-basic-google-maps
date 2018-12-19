<?php

class Wp_Basic_Google_Maps_Admin_Settings {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'menu_page' ] );
		add_action( 'admin_init', [ $this, 'menu_page_init' ] );
	}

	public function menu_page() {
		add_submenu_page(
			'wp-basic-google-maps',
			__( 'WP Basic Google maps settings', 'wp-basic-google-maps' ),
			__( 'Settings', 'wp-basic-google-maps' ),
			'manage_options',
			'wp-basic-google-maps-settings',
			[ $this, 'settings' ]
		);
	}

	public function menu_page_init() {
		register_setting(
			Wp_Basic_Google_Maps::SETTINGS,
			Wp_Basic_Google_Maps::SETTINGS
		);

		add_settings_section(
			'wp_basic_google_maps_settings_section',
			__( 'Settings', 'wp-basic-google-maps' ),
			[ $this, 'settings_section' ],
			Wp_Basic_Google_Maps::SETTINGS
		);

		add_settings_field(
			'field_google_api_key',
			__( 'Google API Key', 'wp-basic-google-maps' ),
			[ $this, 'field_google_api_key' ],
			Wp_Basic_Google_Maps::SETTINGS,
			'wp_basic_google_maps_settings_section'
		);

		add_settings_field(
			'field_map_height',
			__( 'Map height (px)', 'wp-basic-google-maps' ),
			[ $this, 'field_map_height' ],
			Wp_Basic_Google_Maps::SETTINGS,
			'wp_basic_google_maps_settings_section'
		);

		add_settings_field(
			'field_map_zoom',
			__( 'Map zoom', 'wp-basic-google-maps' ),
			[ $this, 'field_map_zoom' ],
			Wp_Basic_Google_Maps::SETTINGS,
			'wp_basic_google_maps_settings_section'
		);

		add_settings_field(
			'field_map_center_latitude',
			__( 'Map center latitude', 'wp-basic-google-maps' ),
			[ $this, 'field_map_center_latitude' ],
			Wp_Basic_Google_Maps::SETTINGS,
			'wp_basic_google_maps_settings_section'
		);

		add_settings_field(
			'field_map_center_longitude',
			__( 'Map center longitude', 'wp-basic-google-maps' ),
			[ $this, 'field_map_center_longitude' ],
			Wp_Basic_Google_Maps::SETTINGS,
			'wp_basic_google_maps_settings_section'
		);
	}

	public function settings_section() {
		echo __( 'Setting page for WP Basic Google maps.', 'wp-basic-google-maps' );
	}

	public function settings() {
		echo '<div class="wrap">';
		echo '<form method="post" action="options.php">';

		settings_fields( Wp_Basic_Google_Maps::SETTINGS );
		do_settings_sections( Wp_Basic_Google_Maps::SETTINGS );
		submit_button();

		echo '</form>';
		echo '</div>';
	}

	public function field_google_api_key() {
		$google_api_key = Wp_Basic_Google_Maps_Model::get_option(
			Wp_Basic_Google_Maps::GOOGLE_API_KEY
		);
		echo '<input type="text"
			id="' . Wp_Basic_Google_Maps::GOOGLE_API_KEY . '"
			name="' . Wp_Basic_Google_Maps::SETTINGS
				. '[' . Wp_Basic_Google_Maps::GOOGLE_API_KEY . ']"
			value="' . $google_api_key . '">';
	}

	public function field_map_height() {
		$map_height = Wp_Basic_Google_Maps_Model::get_option(
			Wp_Basic_Google_Maps::MAP_HEIGHT
		);
		echo '<input type="text"
			id="' . Wp_Basic_Google_Maps::MAP_HEIGHT . '"
			name="' . Wp_Basic_Google_Maps::SETTINGS
				. '[' . Wp_Basic_Google_Maps::MAP_HEIGHT . ']"
			value="' . $map_height . '">';
	}

	public function field_map_zoom() {
		$map_zoom = Wp_Basic_Google_Maps_Model::get_option(
			Wp_Basic_Google_Maps::MAP_ZOOM
		);
		echo '<input type="text"
			id="' . Wp_Basic_Google_Maps::MAP_ZOOM . '"
			name="' . Wp_Basic_Google_Maps::SETTINGS
				. '[' . Wp_Basic_Google_Maps::MAP_ZOOM . ']"
			value="' . $map_zoom . '">';
	}

	public function field_map_center_latitude() {
		$map_center_latitude = Wp_Basic_Google_Maps_Model::get_option(
			Wp_Basic_Google_Maps::MAP_CENTER_LATITUDE
		);
		echo '<input type="text"
			id="' . Wp_Basic_Google_Maps::MAP_CENTER_LATITUDE . '"
			name="' . Wp_Basic_Google_Maps::SETTINGS
				. '[' . Wp_Basic_Google_Maps::MAP_CENTER_LATITUDE . ']"
			value="' . $map_center_latitude . '">';
	}

	public function field_map_center_longitude() {
		$map_center_longitude = Wp_Basic_Google_Maps_Model::get_option(
			Wp_Basic_Google_Maps::MAP_CENTER_LONGITUDE
		);
		echo '<input type="text"
			id="' . Wp_Basic_Google_Maps::MAP_CENTER_LONGITUDE . '"
			name="' . Wp_Basic_Google_Maps::SETTINGS
				. '[' . Wp_Basic_Google_Maps::MAP_CENTER_LONGITUDE . ']"
			value="' . $map_center_longitude . '">';
	}
}
