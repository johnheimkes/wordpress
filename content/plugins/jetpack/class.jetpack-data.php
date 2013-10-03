<?php

class Jetpack_Data {
	/**
	 * Gets locally stored token
	 *
	 * @return object|false
	 */
	public static function get_access_token( $user_id = false ) {
		if ( $user_id ) {
<<<<<<< HEAD
			if ( !$tokens = Jetpack::get_option( 'user_tokens' ) ) {
				return false;
			}
			if ( $user_id === JETPACK_MASTER_USER ) {
				if ( !$user_id = Jetpack::get_option( 'master_user' ) ) {
=======
			if ( !$tokens = Jetpack_Options::get_option( 'user_tokens' ) ) {
				return false;
			}
			if ( $user_id === JETPACK_MASTER_USER ) {
				if ( !$user_id = Jetpack_Options::get_option( 'master_user' ) ) {
>>>>>>> 7548e64a09c1839a373e5cb390b8f4f5790d2536
					return false;
				}
			}
			if ( !isset( $tokens[$user_id] ) || !$token = $tokens[$user_id] ) {
				return false;
			}
			$token_chunks = explode( '.', $token );
			if ( empty( $token_chunks[1] ) || empty( $token_chunks[2] ) ) {
				return false;
			}
			if ( $user_id != $token_chunks[2] ) {
				return false;
			}
			$token = "{$token_chunks[0]}.{$token_chunks[1]}";
		} else {
<<<<<<< HEAD
			$token = Jetpack::get_option( 'blog_token' );
=======
			$token = Jetpack_Options::get_option( 'blog_token' );
>>>>>>> 7548e64a09c1839a373e5cb390b8f4f5790d2536
			if ( empty( $token ) ) {
				return false;
			}
		}

		return (object) array(
			'secret' => $token,
			'external_user_id' => (int) $user_id,
		);
	}
}
