<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

$response['status'] = 'success';

$links = $aeo_db->get_results( "SELECT id,title,url,image FROM links ORDER BY added_datetime DESC" );

if ( $links ) {
  foreach ( $links as $item ) {
    $response['items'][] = [
      'id' => $item['id'],
      'title' => $item['title'],
      'url' => $item['url'],
      'image' => ( $item['image'] ) ? site_url() . '/uploads/links/' . $item['image'] : ''
    ];
  }
}

header( "Content-Type: application/json" );

echo json_encode( $response );
