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

$categories = $aeo_db->get_results( "SELECT id,name,image FROM destination_categories ORDER BY name ASC" );

if ( $categories ) {
  foreach ( $categories as $item ) {
    $response['items'][] = [
      'id' => $item['id'],
      'name' => $item['name'],
      'image' => ( $item['image'] ) ? site_url() . '/uploads/destination-categories/' . $item['image'] : ''
    ];
  }
} else {
  $response['message'] = 'No destination categories found';
}

header( "Content-Type: application/json" );

echo json_encode( $response );
