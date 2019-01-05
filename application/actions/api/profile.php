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

$pages = $aeo_db->get_results( "SELECT id,title,content FROM pages WHERE type = 1 ORDER BY title ASC" );

if ( $pages ) {
  foreach ( $pages as $page ) {
    $response['items'][] = [
      'id' => $page['id'],
      'title' => $page['title'],
      'content' => autop( $page['content'] )
    ];
  }
} else {
  $response['message'] = 'No profile pages found';
}

header( "Content-Type: application/json" );

echo json_encode( $response );
