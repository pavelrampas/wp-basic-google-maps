<?php

class Wp_Basic_Google_Maps_Admin {

	const TOP_LEVEL_MENU = 'wp-basic-google-maps';

	public function __construct() {
		add_action( 'admin_menu', [$this, 'menu_page'] );
	}

	public function menu_page() {
		add_menu_page(
			__( 'WP Basic Google maps', 'wp-basic-google-maps' ),
			__( 'Google map', 'wp-basic-google-maps' ),
			'edit_posts',
			self::TOP_LEVEL_MENU,
			'__return_false',
			'dashicons-admin-site'
		);
	}
}
