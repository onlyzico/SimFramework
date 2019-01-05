<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_page' ) && ! current_user_can( 'admin_add_page' ) || is_route( 'admin_edit_page' ) && ! current_user_can( 'admin_edit_page' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_page' ) ) {
  $id = get_route_vars( 0 );
  $page = $aeo_db->get_row( "SELECT id,title,type,content FROM pages WHERE md5(id) = '$id'" );

  if ( ! $page ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $page = [
    'title' => '',
    'type' => 0,
    'content' => ''
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['page'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $page_input = sanitize_input( $_POST['page'], [ 'content' ] );

  if ( empty( $page_input['title'] ) )
    $error_msg[] = 'Silahkan isi judul halaman';

  $page = array_merge( $page, $page_input );

  if ( ! isset( $error_msg ) ) {
    if ( $edit ) {
      if ( isset( $page['id'] ) ) {
        $id = $page_id = $page['id'];
        unset( $page['id'] );
      }

      $page['updated_datetime'] = time();

      $aeo_db->update( 'pages', $page, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Halaman berhasil diperbarui';
    } else {
      $page['id'] = $page_id = $aeo_db->next_id( 'pages' );
      $page['added_datetime'] = $page['updated_datetime'] = time();

      $aeo_db->insert( 'pages', $page );

      $id = md5( $page['id'] );
      $success_msg = 'Halaman berhasil ditambahkan';
    }

    create_session( 'admin_page_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_page' ) ) {
      redirect( admin_url() . '/pages/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/pages' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    $aeo_db->delete( 'pages', [ 'id' => $page['id'] ] );
    create_session( 'admin_pages_success_msg', 'Halaman berhasil dihapus' );
    redirect( admin_url() . '/pages' );
  }

  redirect( canonical_url() );
}

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_page_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_page_edit_success_msg' );
  delete_session( 'admin_page_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_page' ) ? 'Tambah' : 'Edit' ) . ' Halaman';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_page' ) ? 'Tambah' : 'Edit' );

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.10.0/ckeditor.js';
