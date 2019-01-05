<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_link' ) && ! current_user_can( 'admin_add_link' ) || is_route( 'admin_edit_link' ) && ! current_user_can( 'admin_edit_link' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_link' ) ) {
  $id = get_route_vars( 0 );
  $link = $aeo_db->get_row( "SELECT id,title,url,image FROM links WHERE md5(id) = '$id'" );

  if ( ! $link ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $link = [
    'title' => '',
    'url' => '',
    'image' => ''
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['link'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $link_input = sanitize_input( $_POST['link'] );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $_FILES['image']['name'] ) && $_FILES['image']['name'] ) {
      $image_file = $_FILES['image'];
      if ( ! in_array( $image_file['type'], [ 'image/jpeg', 'image/jpg', 'image/png', 'image/gif' ] ) ) {
        $error_msg[] = 'Gambar harus menggunakan ekstensi berikut: .jpg, .jpeg, .png atau .gif';
      } elseif ( $image_file['size'] > ( ( 1024 * 1024 ) * 2 ) ) {
        $error_msg[] = 'Ukuran gambar maksimal adalah 2MB';
      } elseif ( $image_file['error'] > 0 ) {
        $error_msg[] = 'File gambar error';
      } else {
        $image_file_tmp = $image_file['tmp_name'];
        $image = time() . '_' . $image_file['name'];
      }
    }
  }

  if ( $edit && isset( $link_input['image'] ) && empty( $link_input['image'] ) && $link['image'] ) {
    $old_image_file = BASE . '/uploads/links/' . $link['image'];
    if ( file_exists( $old_image_file ) )
      unlink( $old_image_file );
  }

  if ( isset( $error_msg ) && isset( $_FILES['image']['name'] ) && $_FILES['image']['name'] )
    $link_input['image'] = '';

  $link = array_merge( $link, $link_input );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $image ) ) {
      if ( ! is_dir( BASE . '/uploads/links' ) )
        mkdir( BASE . '/uploads/links', 0775, true );

      move_uploaded_file( $image_file_tmp, BASE . '/uploads/links/' . $image );

      $link['image'] = $image;
    }

    if ( $edit ) {
      if ( isset( $link['id'] ) ) {
        $id = $link_id = $link['id'];
        unset( $link['id'] );
      }

      $link['updated_datetime'] = time();

      $aeo_db->update( 'links', $link, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Tautan berhasil diperbarui';
    } else {
      $link['id'] = $link_id = $aeo_db->next_id( 'links' );
      $link['added_datetime'] = $link['updated_datetime'] = time();

      $aeo_db->insert( 'links', $link );

      $id = md5( $link['id'] );
      $success_msg = 'Tautan berhasil ditambahkan';
    }

    create_session( 'admin_link_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_link' ) ) {
      redirect( admin_url() . '/links/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/links' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    $data_before = $aeo_db->get_row( "SELECT image FROM links WHERE id = $link[id]" );

    if ( isset( $data_before['image'] ) && $data_before['image'] && is_file( BASE . '/uploads/links/' . $data_before['image'] ) )
      unlink( BASE . '/uploads/links/' . $data_before['image'] );

    $aeo_db->delete( 'links', [ 'id' => $link['id'] ] );

    create_session( 'admin_link_success_msg', 'Tautan barhasil dihapus' );

    redirect( admin_url() . '/links' );
  }

  redirect( canonical_url() );
}

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_link_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_link_edit_success_msg' );
  delete_session( 'admin_link_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_link' ) ? 'Tambah' : 'Edit' ) . ' Tautan';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_link' ) ? 'Tambah' : 'Edit' );

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.10.0/ckeditor.js';
