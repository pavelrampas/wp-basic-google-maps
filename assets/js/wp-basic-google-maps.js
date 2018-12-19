function initialize_wp_basic_google_maps() {
	var wp_basic_google_maps_map = new google.maps.Map(
		document.getElementById( 'wp_basic_google_maps_canvas' ),
		{
			zoom: wp_basic_google_maps_zoom,
			center: new google.maps.LatLng(
				wp_basic_google_maps_center_lat,
				wp_basic_google_maps_center_lon
			),
			mapTypeId: google.maps.MapTypeId['roadmap'],
			mapTypeControl: false,
			zoomControl: true,
			scaleControl: false,
			streetViewControl: false,
			fullscreenControl: false,
			rotateControl: false
		}
	);
	var marker_active = null;
	var wp_basic_google_maps_bounds = new google.maps.LatLngBounds();

	for( var i = 0; i < wp_basic_google_maps_locations.length; i++ ) {(
		function() {
			var latlong = new google.maps.LatLng(
				wp_basic_google_maps_locations[i][0],
				wp_basic_google_maps_locations[i][1]
			);
			wp_basic_google_maps_bounds.extend( latlong );

			var info = '<div class="wp_basic_google_maps_pin_info">';
			if (wp_basic_google_maps_locations[i][2]) {
				info += '<span class="wp_basic_google_maps_pin_name">'
					+ wp_basic_google_maps_locations[i][2] + '</span><br>';
			}
			if (wp_basic_google_maps_locations[i][3]) {
				info += '<span class="wp_basic_google_maps_pin_web">'
					+ wp_basic_google_maps_texts.web + ': '
					+ '<a href="//' + wp_basic_google_maps_locations[i][3] + '" target="_blank">'
						+ wp_basic_google_maps_locations[i][3]
						+ '</a>'
					+ '</span><br>';
			}
			if (wp_basic_google_maps_locations[i][4]) {
				info += '<span class="wp_basic_google_maps_pin_address">'
					+ wp_basic_google_maps_texts.address + ': '
					+ wp_basic_google_maps_locations[i][4]
					+ '</span><br>';
			}
			if (wp_basic_google_maps_locations[i][5]) {
				info += '<span class="wp_basic_google_maps_pin_phone">'
					+ wp_basic_google_maps_texts.phone + ': '
					+ wp_basic_google_maps_locations[i][5]
					+ '</span><br>';
			}
			if (wp_basic_google_maps_locations[i][6]) {
				info += '<span class="wp_basic_google_maps_pin_email">'
					+ wp_basic_google_maps_texts.email + ': '
					+ wp_basic_google_maps_locations[i][6]
					+ '</span><br>';
			}
			info += '</div>';

			var marker = new google.maps.Marker({
				position: latlong,
				map: wp_basic_google_maps_map,
				title: wp_basic_google_maps_locations[i][2],
				infowindow: new google.maps.InfoWindow( { content: info } )
			});

			marker.addListener("click", function() {
				if ( marker_active ) {
					marker_active.infowindow.close();
				}
				marker.infowindow.open( wp_basic_google_maps_map, marker );
				marker_active = marker;
			});
		})();
	}
	if( wp_basic_google_maps_locations.length > 1 ) {
		wp_basic_google_maps_map.fitBounds( wp_basic_google_maps_bounds );
	}
}

initialize_wp_basic_google_maps();
