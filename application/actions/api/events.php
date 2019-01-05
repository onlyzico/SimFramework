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
  $query = ( isset( $_POST['query'] ) && $_POST['query'] ) ? addslashes( trim( strip_tags( $_POST['query'] ) ) ) : null;
  $offset = ( $page - 1 ) * $limit;
  $sql = "SELECT id,title,content,image FROM events";
  $sql.= ( ! is_null( $query ) ) ? " WHERE title LIKE '%$query%' OR content LIKE '%$query%'" : '';
  $events = $aeo_db->get_results( "$sql ORDER BY added_datetime DESC LIMIT $offset, $limit" );
  $events_total = $aeo_db->get_count( $sql );

  if ( $events ) {
    foreach ( $events as $item ) {
      $summary = strip_tags( trim( $item['content'] ) );
      $summary = ( strlen( $summary ) > 200 ) ? substr( $summary, 0, 200 ) . ' ...' : $summary;

      $response['items'][] = [
        'id' => $item['id'],
        'title' => $item['title'],
        'summary' => $summary,
        'content' => trim( autop( $item['content'] ) ),
        'image' => ( $item['image'] ) ? site_url() . '/uploads/events/' . $item['image'] : ''
      ];

      $response['total_pages'] = ceil( $events_total / $limit );
    }
  } else {
    $response['message'] = 'No events found';
  }
} else {
  $response['status'] = 'error';
  $response['message'] = 'No paramaters found';
}

header( "Content-Type: application/json" );

echo json_encode( $response );
