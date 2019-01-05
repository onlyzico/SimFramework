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

$categories = $aeo_db->get_results( "SELECT id,name,image FROM gallery_categories ORDER BY name ASC" );

if ( $categories ) {
  foreach ( $categories as $item ) {
    $count = $aeo_db->get_count( "SELECT id FROM galleries WHERE category_id = $item[id]" );
    $response['items'][] = [
      'id' => $item['id'],
      'name' => $item['name'],
      'count' => $count,
      'image' => ( $item['image'] ) ? site_url() . '/uploads/gallery-categories/1000-500-' . $item['image'] : ''
    ];
  }
} else {
  $response['message'] = 'No gallery categories found';
}

header( "Content-Type: application/json" );

echo json_encode( $response );
