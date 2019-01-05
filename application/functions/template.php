<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

function site_title( $title, $sep = '&#8211;' ) {
  return $title . ' ' . $sep . ' ' . get_option( 'site_name' );
}

function register_localize_script( $key, $args ) {
  $output = '<script type="text/javascript">' . "\n";
	$output.= "/* <![CDATA[ */\n";
	$output.= 'var ' . $key . ' = ' . json_encode( $args ) . ';' . "\n";
	$output.= "/* ]]> */\n";
	$output.= '</script>' . "\n";

  echo $output;
}
