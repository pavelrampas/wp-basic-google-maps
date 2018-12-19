<?php

class Wp_Basic_Google_Maps_Admin_Pins {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'wp_basic_google_maps_menu_page' ] );
		add_action( 'admin_head', [ $this, 'styles' ] );
		add_action( 'admin_head', [ $this, 'scripts' ] );
	}

	public function styles() {
		echo '
			<style>
				.wp-basic-google-maps-list table {
					border: 1px solid black;
					border-collapse: collapse;
				}
				.wp-basic-google-maps-list th,
				.wp-basic-google-maps-list td {
					border: 1px solid black;
					padding: 1em;
				}
			</style>';
	}

	public function scripts() {
		echo '
			<script>
				function wp_basic_google_maps_confirm_delete() {
					var userselection = confirm("Are you sure you want to delete this pin?");
					if (userselection == true) {
						return true;
					}
					else {
						return false;
					}
				}
			</script>';
	}

	public function wp_basic_google_maps_menu_page() {
		add_submenu_page(
			Wp_Basic_Google_Maps_Admin::TOP_LEVEL_MENU,
			__( 'WP Basic Google maps pins', 'wp-basic-google-maps' ),
			__( 'Pins', 'wp-basic-google-maps' ),
			'edit_posts',
			'wp-basic-google-maps',
			[ $this, 'pins' ]
		);
	}

	public function pins() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-basic-google-maps' ) );
		}

		$pin_id = '';
		$pin = null;
		$action = $this->load_action();
		if ( $action == 'insert' ) {
			$post = filter_input_array( INPUT_POST );
			$id = $post['pin_id'];
			unset( $post['pin_id'] );
			unset( $post['action'] );
			unset( $post['submit'] );
			if ( $id ) {
				Wp_Basic_Google_Maps_Model::update_pin( $id, $post );
			} else {
				Wp_Basic_Google_Maps_Model::create_pin( $post );
			}
		} elseif ( $action == 'delete' ) {
			$id = filter_input( INPUT_GET, 'id' );
			Wp_Basic_Google_Maps_Model::delete_pin( $id );
		} elseif ( $action == 'edit' ) {
			$pin_id = filter_input( INPUT_GET, 'id' );
			$pin = Wp_Basic_Google_Maps_Model::read_pin( $pin_id );
		}

		$html = '';
		$html .= '<div class="wrap">';

		$html .= '<form method="post" action="' . menu_page_url( 'wp-basic-google-maps', false ) . '" _lpchecked="1">';

		$html .= '<input type="hidden" id="action" name="action" value="insert">';
		$html .= '<input type="hidden" id="pin_id" name="pin_id" value="' . $pin_id . '">';

		$html .= '<h2>' . __( 'New pin', 'wp-basic-google-maps' ) . '</h2>';

		$html .= '<table class="form-table"><tbody>';
		$html .= '<tr><th scope="row">' . __( 'Name', 'wp-basic-google-maps' ) . '</th><td>';
		$html .= '<input type="text" id="name" name="name" '
				. ( $pin ? 'value="' . $pin->name . '"' : '' ) . '>';
		$html .= '</td></tr>';
		$html .= '<tr><th scope="row">' . __( 'Web', 'wp-basic-google-maps' ) . '</th><td>';
		$html .= '<input type="text" id="web" name="web"'
				. ( $pin ? 'value="' . $pin->web . '"' : '' ) . '>';
		$html .= '</td></tr>';
		$html .= '<tr><th scope="row">' . __( 'Address', 'wp-basic-google-maps' ) . '</th><td>';
		$html .= '<input type="text" id="address" name="address"'
				. ( $pin ? 'value="' . $pin->address . '"' : '' ) . '>';
		$html .= '</td></tr>';
		$html .= '<tr><th scope="row">' . __( 'Phone', 'wp-basic-google-maps' ) . '</th><td>';
		$html .= '<input type="text" id="phone" name="phone"'
				. ( $pin ? 'value="' . $pin->phone . '"' : '' ) . '>';
		$html .= '</td></tr>';
		$html .= '<tr><th scope="row">' . __( 'Email', 'wp-basic-google-maps' ) . '</th><td>';
		$html .= '<input type="text" id="email" name="email"'
				. ( $pin ? 'value="' . $pin->email . '"' : '' ) . '>';
		$html .= '</td></tr>';
		$html .= '<tr><th scope="row">' . __( 'Latitude', 'wp-basic-google-maps' ) . '</th><td>';
		$html .= '<input type="text" id="latitude" name="latitude"'
				. ( $pin ? 'value="' . $pin->latitude . '"' : '' ) . '>';
		$html .= '</td></tr>';
		$html .= '<tr><th scope="row">' . __( 'Longitude', 'wp-basic-google-maps' ) . '</th><td>';
		$html .= '<input type="text" id="longitude" name="longitude"'
				. ( $pin ? 'value="' . $pin->longitude . '"' : '' ) . '>';
		$html .= '</td></tr>';
		$html .= '</tbody></table>';

		$html .= '<p class="submit">'
			. '<input type="submit" name="submit" id="submit" class="button
				button-primary" value="' . __( 'Save', 'wp-basic-google-maps' ) . '">'
			. '</p>';

		$html .= '<form>';

		$html .= '</div>';

		$html .= $this->pins_list();

		echo $html;
	}

	/**
	 * Load action from POST or GET
	 *
	 * @return string
	 */
	private function load_action() {
		$action = '';
		if ( null !==  filter_input( INPUT_POST, 'action' ) ) {
			$action = filter_input( INPUT_POST, 'action' );
		} elseif ( null !==  filter_input( INPUT_GET, 'action' ) ) {
			$action = filter_input( INPUT_GET, 'action' );
		}
		return $action;
	}

	/**
	 * Load pins from db and show in the list with link to delete
	 *
	 * @global WPDB $wpdb
	 * @return string
	 */
	private function pins_list() {
		$html = '';
		$html .= '<div class="wrap wp-basic-google-maps-list">';
		$html .= '<h3>' . __( 'List of pins', 'wp-basic-google-maps' ) . '</h3>';
		$html .= '<table class="">';
		$html .= '<thead><tr>';
		$html .= '<th>' . __( 'Name', 'wp-basic-google-maps' ) . '</th>';
		$html .= '<th>' . __( 'Web', 'wp-basic-google-maps' ) . '</th>';
		$html .= '<th>' . __( 'Address', 'wp-basic-google-maps' ) . '</th>';
		$html .= '<th>' . __( 'Phone', 'wp-basic-google-maps' ) . '</th>';
		$html .= '<th>' . __( 'Email', 'wp-basic-google-maps' ) . '</th>';
		$html .= '<th>' . __( 'Latitude', 'wp-basic-google-maps' ) . '</th>';
		$html .= '<th>' . __( 'Longitude', 'wp-basic-google-maps' ) . '</th>';
		$html .= '<th colspan="2"></th>';
		$html .= '</tr></thead>';
		$html .= '<tbody>';

		$pins = Wp_Basic_Google_Maps_Model::get_all_pins();
		foreach ($pins as $pin) {
			$link_delete_params = ['action' => 'delete', 'id' => $pin->id];
			$link_delete = add_query_arg(
				$link_delete_params,
				menu_page_url( 'wp-basic-google-maps', false )
			);
			$link_edit_params = ['action' => 'edit', 'id' => $pin->id];
			$link_edit = add_query_arg(
				$link_edit_params,
				menu_page_url( 'wp-basic-google-maps', false )
			);

			$html .= '<tr>';
			$html .= '<td>' . $pin->name . '</td>';
			$html .= '<td>' . $pin->web . '</td>';
			$html .= '<td>' . $pin->address . '</td>';
			$html .= '<td>' . $pin->phone . '</td>';
			$html .= '<td>' . $pin->email . '</td>';
			$html .= '<td>' . $pin->latitude . '</td>';
			$html .= '<td>' . $pin->longitude . '</td>';
			$html .= '<td><a href="' . $link_delete . '" onclick="return wp_basic_google_maps_confirm_delete();">'
				. __( 'delete', 'wp-basic-google-maps' )
				. '</a></td>';
			$html .= '<td><a href="' . $link_edit . '">'
				. __( 'edit', 'wp-basic-google-maps' )
				. '</a></td>';
			$html .= '</tr>';
		}

		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</div>';

		return $html;
	}
}
