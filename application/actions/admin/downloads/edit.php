<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_download' ) && ! current_user_can( 'admin_add_download' ) || is_route( 'admin_edit_download' ) && ! current_user_can( 'admin_edit_download' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_download' ) ) {
  $id = get_route_vars( 0 );
  $download = $aeo_db->get_row( "SELECT id,title,content,file_url,image FROM downloads WHERE md5(id) = '$id'" );

  if ( ! $download ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $download = [
    'title' => '',
    'content' => '',
    'file_url' => '',
    'image' => ''
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['download'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $download_input = sanitize_input( $_POST['download'] );

  if ( empty( $download_input['title'] ) )
    $error_msg[] = 'Silahkan isi judul download';

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

  if ( $edit && isset( $download_input['image'] ) && empty( $download_input['image'] ) && $download['image'] ) {
    $old_image_file = BASE . '/uploads/downloads/' . $download['image'];
    if ( file_exists( $old_image_file ) )
      unlink( $old_image_file );
  }

  if ( isset( $error_msg ) && isset( $_FILES['image']['name'] ) && $_FILES['image']['name'] )
    $download_input['image'] = '';

  $download = array_merge( $download, $download_input );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $image ) ) {
      if ( ! is_dir( BASE . '/uploads/downloads' ) )
        mkdir( BASE . '/uploads/downloads', 0775, true );

      move_uploaded_file( $image_file_tmp, BASE . '/uploads/downloads/' . $image );

      $download['image'] = $image;
    }

    if ( $edit ) {
      if ( isset( $download['id'] ) ) {
        $id = $download_id = $download['id'];
        unset( $download['id'] );
      }

      $download['updated_datetime'] = time();

      $aeo_db->update( 'downloads', $download, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Download berhasil diperbarui';
    } else {
      $download['id'] = $download_id = $aeo_db->next_id( 'downloads' );
      $download['added_datetime'] = $download['updated_datetime'] = time();

      $aeo_db->insert( 'downloads', $download );

      $id = md5( $download['id'] );
      $success_msg = 'Download berhasil ditambahkan';
    }

    create_session( 'admin_download_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_download' ) ) {
      redirect( admin_url() . '/downloads/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/downloads' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    $data_before = $aeo_db->get_row( "SELECT image FROM downloads WHERE id = $download[id]" );

    if ( isset( $data_before['image'] ) && $data_before['image'] && is_file( BASE . '/uploads/downloads/' . $data_before['image'] ) )
      unlink( BASE . '/uploads/downloads/' . $data_before['image'] );

    $aeo_db->delete( 'downloads', [ 'id' => $download['id'] ] );

    create_session( 'admin_download_success_msg', 'Download barhasil dihapus' );

    redirect( admin_url() . '/downloads' );
  }

  redirect( canonical_url() );
}

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_download_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_download_edit_success_msg' );
  delete_session( 'admin_download_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_download' ) ? 'Tambah' : 'Edit' ) . ' Download';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_download' ) ? 'Tambah' : 'Edit' );

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.10.0/ckeditor.js';
