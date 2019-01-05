<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

function site_url() {
  if (
    isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ||
    ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ||
    ! empty( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on'
  ) {
		$https = true;
	} else {
    $https = false;
  }

  if ( ! $https && isset( $_SERVER['HTTP_CF_VISITOR'] ) ) {
    $cloudflare = json_decode( $_SERVER['HTTP_CF_VISITOR'] );
    if ( isset( $cloudflare->scheme ) && $cloudflare->scheme === 'https' )
      $https = true;
  }

	$protocol  = $https ? 'https' : 'http';
	$host      = '://' . HOST;
	$path      = PATH;
	$url       = $protocol . $host . $path;

	return $url;
}

function assets_url() {
  return site_url() . '/' . basename( APP ) . '/assets';
}

function canonical_url( $query_string = true ) {
  $path         = ( PATH === '' ) ? '/' : PATH;
  $parse_uri    = parse_url( $_SERVER['REQUEST_URI'] );
  $clean_path   = str_replace( PATH, '', $parse_uri['path'] );

  if ( $path === '/' ) {
    $uri = ( $parse_uri['path'] != '/' ) ? '/' . ltrim( $parse_uri['path'], '/' ) : '';
  } else {
    $uri = ( $clean_path !== '/' ) ? '/' . str_replace( PATH . '/', '', $parse_uri['path'] ) : '';
  }

  if ( $query_string && isset( $parse_uri['query'] ) && $parse_uri['query'] )
    $uri.= '?' . $parse_uri['query'];

	return site_url() . $uri;
}

function uri_has_extension( $uri ) {
	$exp_uri       = explode( '/', $uri );
	$last_item     = end( $exp_uri );
	$exp_last_item = explode( '.', $last_item, 2 );

  return ( count( $exp_last_item ) > 1 ) ? end( $exp_last_item ) : false;
}
