<?php
/**
 * Module Name: JSON API
 * Module Description: Allow applications to securely access your content through the cloud.
 * Sort Order: 100
 * First Introduced: 1.9
 * Requires Connection: Yes
<<<<<<< HEAD
=======
 * Auto Activate: Public
>>>>>>> 7548e64a09c1839a373e5cb390b8f4f5790d2536
 */

function jetpack_json_api_toggle() {
	$jetpack = Jetpack::init();
	$jetpack->sync->register( 'noop' );

	if ( false !== strpos( current_filter(), 'jetpack_activate_module_' ) ) {
		Jetpack::check_privacy( __FILE__ );
	}
}

add_action( 'jetpack_activate_module_json-api',   'jetpack_json_api_toggle' );
add_action( 'jetpack_deactivate_module_json-api', 'jetpack_json_api_toggle' );
