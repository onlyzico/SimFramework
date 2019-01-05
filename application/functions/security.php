<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

function csrf_token() {
  global $aeo_config;
  return md5( $aeo_config['options']['csrf_token_key'] . $_SERVER['HTTP_USER_AGENT'] );
}

function sanitize_input( $data, $exclude = [] ) {
  if ( $data && is_array( $data ) ) {
    $all_data = [];

    foreach ( $data as $key => $value ) {
      if ( $exclude && ! in_array( $key, $exclude ) || empty( $exclude ) ) {
        if ( ! is_array( $value ) ) {
          $all_data[$key] = trim( strip_tags( $value ) );
        } else {
          $all_data[$key] = sanitize_input( $value, $exclude );
        }
      } else {
        $all_data[$key] = $value;
      }
    }

    return $all_data;
  }
}

function get_input( $key = null, $data = [], $index = 0 ) {
  if ( is_null( $key ) )
    return;

  if ( empty( $data ) )
    $data = $_POST;

  $key_parts = sanitize_array( explode( ':', $key ) );
  $current_key = $key_parts[$index];

  if ( isset( $data[$current_key] ) ) {
    if ( is_array( $data[$current_key] ) ) {
      return get_input( $key, $data[$current_key], ( $index + 1 ) );
    } else {
      return $data[$current_key];
    }
  }
}

function generate_salt() {
	return substr( sha1( mt_rand() ), 0, 22 );
}

function create_password( $password ) {
	return crypt( $password, '$2a$10$' . generate_salt() );
}

function check_password( $hash, $password ) {
	$full_salt = substr( $hash, 0, 29 );
	$new_hash  = crypt( $password, $full_salt );

  return ( $hash === $new_hash ) ? true : false;
}
