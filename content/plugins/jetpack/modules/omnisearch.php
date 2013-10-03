<?php

/**
 * Module Name: Omnisearch
 * Module Description: A single search box, that lets you search many different things.
 * Sort Order: 8
 * First Introduced: 2.3
 * Requires Connection: No
<<<<<<< HEAD
=======
 * Auto Activate: Yes
>>>>>>> 7548e64a09c1839a373e5cb390b8f4f5790d2536
 */

// Only do Jetpack Omnisearch if there isn't already a Core WP_Omnisearch Class.
if ( ! class_exists( 'WP_Omnisearch' ) )
	require_once( dirname( __FILE__ ) . '/omnisearch/omnisearch-core.php' );

