<?php
/*
 * Load module code that is needed even when a module isn't active.
 * For example, if a module shouldn't be activatable unless certain conditions are met, the code belongs in this file.
 */

// Happy Holidays!
require_once( dirname( __FILE__ ) . '/holiday-snow.php' );

// Include extra tools that aren't modules, in a filterable way
$jetpack_tools_to_include = apply_filters( 'jetpack-tools-to-include', array( 'theme-tools.php' ) );

if ( ! empty( $jetpack_tools_to_include ) ) {
	foreach ( $jetpack_tools_to_include as $tool ) {
		if ( file_exists( JETPACK__PLUGIN_DIR . '/modules/' . $tool ) ) {
			require_once( JETPACK__PLUGIN_DIR . '/modules/' . $tool );
		}
	}
<<<<<<< HEAD
}
add_action( 'setup_theme', 'jetpack_load_infinite_scroll_annotation' );

/**
 * Prevent IS from being activated if theme doesn't support it
 *
 * @param bool $can_activate
 * @filter jetpack_can_activate_infinite-scroll
 * @return bool
 */
function jetpack_can_activate_infinite_scroll( $can_activate ) {
	return (bool) current_theme_supports( 'infinite-scroll' );
}
add_filter( 'jetpack_can_activate_infinite-scroll', 'jetpack_can_activate_infinite_scroll' );

// Happy Holidays!
require_once( dirname( __FILE__ ) . '/holiday-snow.php' );

require_once( dirname( __FILE__ ) . '/featured-content/featured-content.php' );

require_once( dirname( __FILE__ ) . '/social-links.php' );
=======
}
>>>>>>> 7548e64a09c1839a373e5cb390b8f4f5790d2536
