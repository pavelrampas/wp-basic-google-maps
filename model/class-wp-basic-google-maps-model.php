<?php

class Wp_Basic_Google_Maps_Model {

	private static $options = null;

	/**
	 * Get option
	 *
	 * @param  string $name
	 * @return string
	 */
	public static function get_option( $name ) {
		if ( ! self::$options ) {
			self::$options = get_option( 'wp_basic_google_maps_settings' );
		}

		return isset( self::$options[$name] )
			? esc_attr( self::$options[$name] )
			: '';
	}

	/**
	 * Get all pins
	 *
	 * @global WPDB $wpdb
	 * @return type
	 */
	public static function get_all_pins() {
		global $wpdb;
		return $wpdb->get_results(
			'SELECT * FROM ' . self::table_name() . ' ORDER BY name'
		);
	}

	/**
	 * Save pin
	 *
	 * @param array $pin
	 * @global WPDB $wpdb
	 */
	public static function create_pin( $pin ) {
		global $wpdb;
		$wpdb->insert( self::table_name(), $pin );
	}

	/**
	 * Get pin
	 *
	 * @param int $id
	 * @global WPDB $wpdb
	 */
	public static function read_pin( $id ) {
		global $wpdb;
		if ( $id ) {
			return $wpdb->get_row(
				'SELECT * FROM ' . self::table_name() . ' WHERE id = ' . $id
			);
		}
	}

	/**
	 * Update pin
	 *
	 * @param int $id
	 * @param array $pin
	 * @global WPDB $wpdb
	 */
	public static function update_pin( $id, $pin ) {
		global $wpdb;
		$wpdb->update( self::table_name(), $pin, ['id' => $id] );
	}

	/**
	 * Delete pin
	 *
	 * @param int $id
	 * @global WPDB $wpdb
	 */
	public static function delete_pin( $id ) {
		global $wpdb;
		if ( $id ) {
			$wpdb->delete( self::table_name(), ['id' => $id] );
		}
	}

	/**
	 * Get table name
	 *
	 * @global WPDB $wpdb
	 * @return type
	 */
	public static function table_name(){
		global $wpdb;
		return $wpdb->prefix . Wp_Basic_Google_Maps::DB_TABLE;
	}
}
