<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

function debug( $data, $exit = true ) {
  echo '<pre>';
  print_r( $data );
  echo '</pre>';

  if ( $exit )
    die();
}

function error_notice( $message = '', $title = 'Error!', $align = 'center' ) {
  $html = '<!DOCTYPE html><html><head>';
  $html.= '<title>' . $title . '</title>';
  $html.= '<style type="text/css">';
  $html.= 'body { padding: 0; margin: 0; overflow-y: scroll; text-align: ' . $align . '; font: normal 14px/20px -apple-system,BlinkMacSystemFont,Helvetica,Arial,sans-serif; color: #444; }';
  $html.= 'div { line-height: 26px; padding: 22px 30px 23px 30px; background: #ffd4d4; color: #e64b4b; }';
  $html.= 'strong { font-weight: 600; }';
  $html.= 'p { margin: 0; }';
  $html.= '</style>';
  $html.= '</head><body><div>' . $message . '</div></body></html>';
  die( $html );
}

function redirect( $url = '', $options = [] ) {
  $defaults = [
    'permanent' => false,
    'method'    => '',
    'timeout'   => 5
  ];
  $options = array_merge( $defaults, $options );

  if ( $options['method'] === 'refresh' ) {
    header( 'Refresh: ' . $options['timeout'] . '; url=' . $url );
  } else {
    if ( $options['permanent'] )
      header( 'HTTP/1.1 301 Moved Permanently' );

    header( 'Location: ' . $url );
    die();
  }
}

function sanitize_array( $data ) {
  return array_values( array_filter( array_map( 'trim', $data ) ) );
}

function base64__encode( $str ) {
	return rtrim( strtr( base64_encode( $str ), '+/', '-_' ), '=' );
}

function base64__decode( $str ) {
	return base64_decode( str_pad( strtr( $str, '-_', '+/' ), strlen( $str ) % 4, '=', STR_PAD_RIGHT ) );
}

function generate_random( $length = 15 ) {
  $chars      = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $chars_len  = strlen( $chars );
  $random     = '';

  for ( $i = 0; $i < $length; $i++ )
    $random.= $chars[rand( 0, $chars_len - 1 )];

  return $random;
}

function multi_explode( $delimiters, $string ) {
	$ready = str_replace( $delimiters, $delimiters[0], $string );
	$launch = explode( $delimiters[0], $ready );

  return $launch;
}

function rrmdir( $path ) {
  $i = new DirectoryIterator( $path );

  foreach ( $i as $f ) {
    if ( $f->isFile() ) {
      unlink( $f->getRealPath() );
    } elseif ( ! $f->isDot() && $f->isDir() ) {
      rrmdir( $f->getRealPath() );
    }
  }

  rmdir( $path );
}

function is_json( $json ) {
  json_decode( $json );
  return ( json_last_error() == JSON_ERROR_NONE );
}

function firebase( $message = '', $args = [], $server_key = null ) {
	$firebase_url = 'https://fcm.googleapis.com/fcm/send';

  $headers = [
    'Authorization: key=' . $server_key,
    'Content-Type: application/json'
  ];

  $ch = curl_init();

  $push['data']['is_background'] = true;
  $push['data']['image'] = "";
  $push['data']['message'] = $message;
  $push['data']['timestamp'] = date( 'Y-m-d G:i:s' );
  $push['priority'] = 'high';

  if ( isset( $args['payload'] ) )
    $push['data']['payload'] = $args['payload'];

  if ( isset( $args['title'] ) )
    $push['data']['title'] = $args['title'];
  if ( isset( $args['condition'] ) )
    $fields['condition'] = implode( $args['condition'], " || ");
  if ( isset( $args['to'] ) )
    $fields['to'] = $args['to'];

  if ( isset( $args['reg_ids'] ) ) {
    if ( count( $args['reg_ids'] ) > 1 ) {
      $fields['registration_ids'] = $args['reg_ids'];
    } else {
      $fields['to'] = $args['reg_ids'][0];
    }
  }

  $fields['data'] = $push;

  curl_setopt( $ch, CURLOPT_URL, $firebase_url );
  curl_setopt( $ch, CURLOPT_POST, true );
  curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
  curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

  $result = curl_exec( $ch );

  curl_close( $ch );

	return $result;
}
