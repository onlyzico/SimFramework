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

$slideshows = $aeo_db->get_results( "SELECT title,content,image FROM slideshows WHERE image != '' ORDER BY added_datetime DESC" );
$news = $aeo_db->get_results( "SELECT id,title,content,image,added_datetime FROM news ORDER BY added_datetime DESC LIMIT 3" );

$response['status'] = 'success';

if ( $slideshows ) {
  foreach ( $slideshows as $slideshow ) {
    $response['slideshows'][] = [
      'title' => $slideshow['title'],
      'content' => trim( autop( $slideshow['content'] ) ),
      'image' => site_url() . '/uploads/slideshows/' . $slideshow['image']
    ];
  }
}

if ( $news ) {
  foreach ( $news as $item ) {
    $summary = strip_tags( trim( $item['content'] ) );
    $summary = ( strlen( $summary ) > 200 ) ? substr( $summary, 0, 200 ) . ' ...' : $summary;

    $response['news'][] = [
      'id' => $item['id'],
      'title' => $item['title'],
      'summary' => $summary,
      'content' => trim( autop( $item['content'] ) ),
      'image' => site_url() . '/uploads/news/' . $item['image'],
      'date' => date( 'd F Y H:i:s', $item['added_datetime'] )
    ];

    unset( $item, $summary );
  }
}

$response['latitude'] = get_option( 'latitude' );
$response['longitude'] = get_option( 'longitude' );
$response['facebook'] = get_option( 'facebook_username' );
$response['instagram'] = get_option( 'instagram_username' );
$response['youtube'] = get_option( 'youtube_channel_id' );
$response['twitter'] = get_option( 'twitter_username' );

header( "Content-Type: application/json" );

echo json_encode( $response );
