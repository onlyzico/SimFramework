<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

function add_option( $args = [], $update = true ) {
  global $aeo_db;

  $defaults = [
    'name' => '',
    'content' => '',
    'label' => '',
    'description' => '',
    'attr' => '',
    'public' => 1
  ];

  if ( $args ) {
    $args = array_merge( $defaults, $args );

    if ( is_array( $args['content'] ) )
      $args['content'] = json_encode( $args['content'] );

    if ( is_array( $args['attr'] ) )
      $args['attr'] = json_encode( $args['attr'] );

    if ( ! has_option( $args['name'] ) ) {
      $aeo_db->insert( 'options', $args );
    } else {
      if ( $update ) {
        $key = $args['name'];
        unset( $args['name'] );
        update_option( $key, $args );
      }
    }
  }
}

function update_option( $key = null, $args = [], $add = true ) {
  global $aeo_db;

  if ( $key && $args ) {
    if ( is_array( $args['content'] ) )
      $args['content'] = json_encode( $args['content'] );

    if ( has_option( $key ) ) {
      $aeo_db->update( 'options', $args, [ 'name' => $key ] );
    } else {
      if ( $add ) {
        $args['name'] = $key;
        add_option( $args );
      }
    }
  }
}

function has_option( $key ) {
  global $aeo_db;
  $option = $aeo_db->get_count( "SELECT id FROM options WHERE name = '$key'" );
  return ( $option == 1 ) ? true : false;
}

function get_option( $key, $all_column = false ) {
  global $aeo_db;

  $option = $aeo_db->get_row( "SELECT id,content,label,description,public FROM options WHERE name = '$key'" );

  if ( $option ) {
    if ( $all_column ) {
      return $option;
    } else {
      return ( is_json( $option['content'] ) ) ? json_decode( $option['content'], true ) : $option['content'];
    }
  }

  return false;
}
