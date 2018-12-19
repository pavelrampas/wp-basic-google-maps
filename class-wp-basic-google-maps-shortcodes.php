<?php

class Wp_Basic_Google_Maps_Shortcodes {

	public function __construct() {
		add_shortcode( 'wp_basic_google_maps', [ $this, 'map' ] );
		add_shortcode( 'wp_basic_google_maps_list', [ $this, 'pins_list' ] );
	}

	public function map( $atts, $content ) {
		$this->load_scripts();

		$pins = Wp_Basic_Google_Maps_Model::get_all_pins();
		foreach ( $pins as $pin ) {
			$locations[] = '["'
				. $pin->latitude . '", "'
				. $pin->longitude . '", "'
				. $pin->name . '", "'
				. $pin->web . '", "'
				. $pin->address . '", "'
				. $pin->phone . '", "'
				. $pin->email . '"]';
		}

		$height =  Wp_Basic_Google_Maps_Model::get_option( Wp_Basic_Google_Maps::MAP_HEIGHT );
		$map_zoom =  Wp_Basic_Google_Maps_Model::get_option( Wp_Basic_Google_Maps::MAP_ZOOM );
		$map_center_lat =  Wp_Basic_Google_Maps_Model::get_option( Wp_Basic_Google_Maps::MAP_CENTER_LATITUDE );
		$map_center_lon =  Wp_Basic_Google_Maps_Model::get_option( Wp_Basic_Google_Maps::MAP_CENTER_LONGITUDE );

		$html = '';
		$html .= '
			<script type="text/javascript">
				var wp_basic_google_maps_locations = [' . implode( ",", $locations ) . '];
				var wp_basic_google_maps_zoom = ' . $map_zoom . ';
				var wp_basic_google_maps_center_lat = ' . $map_center_lat . ';
				var wp_basic_google_maps_center_lon = ' . $map_center_lon . ';
			</script>';
		$html .= '
			<div class="wp-basic-google-maps-container">
				<div id="wp_basic_google_maps_canvas" class="wp_basic_google_maps_canvas"
					style="width:100%;height:' . $height . 'px;"></div>
			</div>';

		return $html;
	}

	public function pins_list( $atts, $content ) {
		$pins = Wp_Basic_Google_Maps_Model::get_all_pins();

		$html = '<ul class="wp-basic-google-maps-list">';
		foreach ( $pins as $pin ) {
			$html .= '<li><div>';
			$html .= $pin->name ? ( '<h3>' . $pin->name . '</h3>' ) : '';
			$html .= $pin->web ?
				( '<p class="web"><span>' . __( 'Web', 'wp-basic-google-maps' ) . ': </span>'
					. '<a href="//' . $pin->web . '" target="_blank">' . $pin->web . '</a>'
					. '</p>' )
				: '';
			$html .= $pin->address ?
				( '<p class="address"><span>' . __( 'Address', 'wp-basic-google-maps' ) . ': </span>' . $pin->address . '</p>' )
				: '';
			$html .= $pin->phone
				? ( '<p class="phone"><span>' . __( 'Phone', 'wp-basic-google-maps' ) . ': </span>' . $pin->phone . '</p>' )
				: '';
			$html .= $pin->email
				? ( '<p class="email"><span>' . __( 'Email', 'wp-basic-google-maps' ) . ': </span>' . $pin->email . '</p>' )
				: '';
			$html .= '</div></li>';
		}
		$html .= '</ul>';

		return $html;
	}

	protected function load_scripts() {
		$google_api_key =  Wp_Basic_Google_Maps_Model::get_option(
			Wp_Basic_Google_Maps::GOOGLE_API_KEY
		);
		wp_enqueue_script(
			'wp-basic-google-maps-api',
			esc_url_raw( 'https://maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&libraries=geometry' ),
			[],
			null,
			true
		);
		wp_enqueue_script(
			'wp-basic-google-maps-js',
			WP_BASIC_GOOGLE_MAPS_ABSOLUTE_DIR . 'assets/js/wp-basic-google-maps.js',
			[],
			null,
			true
		);
		$translation_array = [
			'web' => __( 'Web', 'wp-basic-google-maps' ),
			'address' => __( 'Address', 'wp-basic-google-maps' ),
			'phone' => __( 'Phone', 'wp-basic-google-maps' ),
			'email' => __( 'Email', 'wp-basic-google-maps' ),
		];
		wp_localize_script(
			'wp-basic-google-maps-js',
			'wp_basic_google_maps_texts',
			$translation_array
		);
	}
}
