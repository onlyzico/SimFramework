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

if ( isset( $_POST ) && $_POST ) {
  $response['status'] = 'success';

  $limit = 8;
  $category_id = ( isset( $_POST['category_id'] ) && (int) $_POST['category_id'] ) ? (int) $_POST['category_id'] : 0;
  $page = ( isset( $_POST['page'] ) && (int) $_POST['page'] > 1 ) ? (int) $_POST['page'] : 1;
  $offset = ( $page - 1 ) * $limit;
  $galleries = $aeo_db->get_results( "SELECT id,title,photo FROM galleries WHERE category_id = $category_id ORDER BY added_datetime DESC LIMIT $offset, $limit" );
  $galleries_total = $aeo_db->get_count( "SELECT id FROM galleries WHERE category_id = $category_id" );

  if ( $galleries ) {
    foreach ( $galleries as $item ) {
      $response['items'][] = [
        'id' => $item['id'],
        'title' => $item['title'],
        'photo' => ( $item['photo'] ) ? site_url() . '/uploads/galleries/' . $item['photo'] : ''
      ];

      $response['total_pages'] = ceil( $galleries_total / $limit );
    }
  } else {
    $response['message'] = 'No galleries found';
  }
} else {
  $response['status'] = 'error';
  $response['message'] = 'No paramaters found';
}

header( "Content-Type: application/json" );

echo json_encode( $response );
