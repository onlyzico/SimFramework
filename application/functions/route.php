<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

function get_route( $key ) {
  global $aeo_router;
  $route = $aeo_router->active();
  return ( isset( $route[$key] ) && $route[$key] ) ? $route[$key] : '';
}

function is_route( $value = '' ) {
	global $aeo_router;
	if ( $value && get_route( 'name' ) === $value ) {
		return true;
	} elseif ( $value && get_route( 'name' ) != $value ) {
		return false;
	} else {
		return get_route( 'name' );
	}
}

function is_route_section( $value = '' ) {
	global $aeo_router;
	if ( $value && get_route( 'section' ) === $value ) {
		return true;
	} elseif ( $value && get_route( 'section' ) != $value ) {
		return false;
	} else {
		return get_route( 'section' );
	}
}

function is_ajax() {
  if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' ) {
    return true;
  } else {
    return false;
  }
}

function get_route_vars( $index = -1 ) {
	global $aeo_router;
  $vars = ( get_route( 'vars' ) ) ? array_map( 'urldecode', get_route( 'vars' ) ) : get_route( 'vars' );
	return ( $index >= 0 && isset( $vars[$index] ) ) ? $vars[$index] : $vars;
}
