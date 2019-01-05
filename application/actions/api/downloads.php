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

  $limit = 10;
  $page = ( isset( $_POST['page'] ) && (int) $_POST['page'] > 1 ) ? (int) $_POST['page'] : 1;
  $offset = ( $page - 1 ) * $limit;
  $downloads = $aeo_db->get_results( "SELECT id,title,content,file_url,image FROM downloads WHERE file_url != '' ORDER BY added_datetime DESC LIMIT $offset, $limit" );
  $downloads_total = $aeo_db->get_count( "SELECT id FROM downloads WHERE file_url != ''" );

  if ( $downloads ) {
    foreach ( $downloads as $item ) {
      $summary = strip_tags( trim( $item['content'] ) );
      $summary = ( strlen( $summary ) > 200 ) ? substr( $summary, 0, 200 ) . ' ...' : $summary;

      $response['items'][] = [
        'id' => $item['id'],
        'title' => $item['title'],
        'summary' => $summary,
        'content' => trim( autop( $item['content'] ) ),
        'file_url' => $item['file_url'],
        'image' => ( $item['image'] ) ? site_url() . '/uploads/downloads/' . $item['image'] : ''
      ];

      $response['total_pages'] = ceil( $downloads_total / $limit );
    }
  } else {
    $response['message'] = 'No downloads found';
  }
} else {
  $response['status'] = 'error';
  $response['message'] = 'No paramaters found';
}

header( "Content-Type: application/json" );

echo json_encode( $response );
