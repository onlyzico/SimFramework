<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/


function user_session_key() {
  global $aeo_config;
  return $aeo_config['options']['user_session_key'];
}

function is_user_logged_in() {
  global $aeo_db, $aeo_config;

  if ( $id = get_session( user_session_key() ) ) {
    $check_user = $aeo_db->get_count( "SELECT id FROM users WHERE md5(id) = '$id'" );
    if ( $check_user == 1 )
      return true;
  }

  return false;
}

function current_user( $field = '*' ) {
  global $aeo_db;

  if ( is_user_logged_in() ) {
    $id = get_session( user_session_key() );
    $user = $aeo_db->get_row( "SELECT $field FROM users WHERE md5(id) = '$id'" );

    return ( isset( $user[$field] ) ) ? $user[$field] : $user;
  }

  return [];
}

function current_user_can( $key = null ) {
  global $current_user_capabilities;

  if ( ! is_null( $key ) && $current_user_capabilities && is_array( $current_user_capabilities ) && in_array( $key, $current_user_capabilities ) )
    return true;

  return false;
}
