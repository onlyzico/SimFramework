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
  $category_id = ( isset( $_POST['category_id'] ) && (int) $_POST['category_id'] ) ? (int) $_POST['category_id'] : 0;
  $latitude = ( isset( $_POST['latitude'] ) && $_POST['latitude'] ) ? $_POST['latitude'] : '';
  $longitude = ( isset( $_POST['longitude'] ) && $_POST['longitude'] ) ? $_POST['longitude'] : '';
  $page = ( isset( $_POST['page'] ) && (int) $_POST['page'] > 1 ) ? (int) $_POST['page'] : 1;
  $query = ( isset( $_POST['query'] ) && $_POST['query'] ) ? addslashes( trim( strip_tags( $_POST['query'] ) ) ) : null;
  $offset = ( $page - 1 ) * $limit;
  $sql = "SELECT id,name,content,address,image,latitude,longitude FROM destinations WHERE";
  $sql.= ( ! is_null( $query ) ) ? " name LIKE '%$query%' OR content LIKE '%$query%' OR address LIKE '%$query%'" : " category_id = $category_id";
  $destinations = $aeo_db->get_results( "$sql ORDER BY added_datetime DESC LIMIT $offset, $limit" );
  $destinations_total = $aeo_db->get_count( $sql );

  if ( $destinations ) {
    foreach ( $destinations as $item ) {
      $summary = strip_tags( trim( $item['content'] ) );
      $summary = ( strlen( $summary ) > 200 ) ? substr( $summary, 0, 200 ) . ' ...' : $summary;

      if ( $latitude && $longitude )
        $distance = $aeo_db->get_row( "SELECT ( 6373 * acos( cos( radians( $latitude ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( $longitude ) ) + sin( radians( $latitude ) ) * sin( radians( latitude ) ) ) ) AS distance FROM destinations WHERE id = $item[id]" );

      $response['items'][] = [
        'id' => $item['id'],
        'name' => $item['name'],
        'summary' => $summary,
        'content' => trim( autop( $item['content'] ) ),
        'address' => trim( $item['address'] ),
        'distance' => ( isset( $distance ) ) ? number_format( $distance['distance'], 2 ) : '',
        'latitude' => $item['latitude'],
        'longitude' => $item['longitude'],
        'image' => ( $item['image'] ) ? site_url() . '/uploads/destinations/' . $item['image'] : ''
      ];

      $response['total_pages'] = ceil( $destinations_total / $limit );
    }
  }
} else {
  $response['status'] = 'error';
  $response['message'] = 'No paramaters found';
}

header( "Content-Type: application/json" );

echo json_encode( $response );
