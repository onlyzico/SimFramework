<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

function create_session( $key, $value ) {
  session_start();
  $_SESSION[$key] = $value;
  session_write_close();
}

function get_session( $key ) {
  session_start();

  if ( isset( $_SESSION[$key] ) )
    $session = $_SESSION[$key];

  session_write_close();

  return ( isset( $session ) ) ? $session : null;
}

function delete_session( $key ) {
  session_start();

  if ( isset( $_SESSION[$key] ) )
    unset( $_SESSION[$key] );

  session_write_close();
}
