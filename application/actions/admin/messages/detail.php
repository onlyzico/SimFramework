<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_message' ) && ! current_user_can( 'admin_message' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

$id = get_route_vars( 0 );
$message = $aeo_db->get_row( "SELECT id,name,email,subject,message,added_datetime FROM messages WHERE md5(id) = '$id'" );

if ( ! $message ) {
  $aeo_router->set_found( false );
  return false;
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = 'Detail Pesan';
$aeo['breadcrumb_title'] = 'Detail';
