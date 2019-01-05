<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

require_once APP . '/functions/formatting.php';

/*----------------------------------------------------------------------------*/

$response['status'] = 'success';
$response['address'] = get_option( 'address' );
$response['primary_phone'] = get_option( 'primary_phone' );
$response['secondary_phone'] = get_option( 'secondary_phone' );
$response['fax'] = get_option( 'fax' );
$response['primary_email'] = get_option( 'primary_email' );
$response['secondary_email'] = get_option( 'secondary_email' );

header( "Content-Type: application/json" );

echo json_encode( $response );
