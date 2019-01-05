<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_gallery' ) && ! current_user_can( 'admin_add_gallery' ) || is_route( 'admin_edit_gallery' ) && ! current_user_can( 'admin_edit_gallery' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_gallery' ) ) {
  $id = get_route_vars( 0 );
  $gallery = $aeo_db->get_row( "SELECT id,category_id,title,photo FROM galleries WHERE md5(id) = '$id'" );

  if ( ! $gallery ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $gallery = [
    'category_id' => '',
    'title' => '',
    'photo' => ''
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['gallery'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $gallery_input = sanitize_input( $_POST['gallery'], [ 'content' ] );

  if ( empty( $gallery_input['category_id'] ) )
    $error_msg[] = 'Silahkan pilih kategori galeri';

  if ( isset( $_FILES['photo']['name'] ) && $_FILES['photo']['name'] ) {
    $photo_file = $_FILES['photo'];
    if ( ! in_array( $photo_file['type'], [ 'image/jpeg', 'image/jpg', 'image/png', 'image/gif' ] ) ) {
      $error_msg[] = 'Foto harus menggunakan ekstensi berikut: .jpg, .jpeg, .png atau .gif';
    } elseif ( $photo_file['size'] > ( ( 1024 * 1024 ) * 10 ) ) {
      $error_msg[] = 'Ukuran foto maksimal adalah 10MB';
    } elseif ( $photo_file['error'] > 0 ) {
      $error_msg[] = 'File foto error';
    } else {
      $photo_file_tmp = $photo_file['tmp_name'];
      $photo = time() . '_' . $photo_file['name'];
    }
  }

  if ( $edit && isset( $gallery_input['photo'] ) && empty( $gallery_input['photo'] ) && $gallery['photo'] ) {
    $old_photo_file = BASE . '/uploads/galleries/' . $gallery['photo'];
    if ( file_exists( $old_photo_file ) )
      unlink( $old_photo_file );
  }

  if ( isset( $error_msg ) && isset( $_FILES['photo']['name'] ) && $_FILES['photo']['name'] )
    $gallery_input['photo'] = '';

  $gallery = array_merge( $gallery, $gallery_input );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $photo ) ) {
      if ( ! is_dir( BASE . '/uploads/galleries' ) )
        mkdir( BASE . '/uploads/galleries', 0775, true );

      move_uploaded_file( $photo_file_tmp, BASE . '/uploads/galleries/' . $photo );

      $gallery['photo'] = $photo;
    }

    if ( $edit ) {
      if ( isset( $gallery['id'] ) ) {
        $id = $gallery_id = $gallery['id'];
        unset( $gallery['id'] );
      }

      $gallery['updated_datetime'] = time();

      $aeo_db->update( 'galleries', $gallery, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Galeri berhasil diperbarui';
    } else {
      $gallery['id'] = $gallery_id = $aeo_db->next_id( 'galleries' );
      $gallery['added_datetime'] = $gallery['updated_datetime'] = time();

      $aeo_db->insert( 'galleries', $gallery );

      $id = md5( $gallery['id'] );
      $success_msg = 'Galeri berhasil ditambahkan';
    }

    create_session( 'admin_gallery_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_gallery' ) ) {
      redirect( admin_url() . '/galleries/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/galleries' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    $data_before = $aeo_db->get_row( "SELECT photo FROM galleries WHERE id = $gallery[id]" );

    if ( isset( $data_before['photo'] ) && $data_before['photo'] && is_file( BASE . '/uploads/galleries/' . $data_before['photo'] ) )
      unlink( BASE . '/uploads/galleries/' . $data_before['photo'] );

    $aeo_db->delete( 'galleries', [ 'id' => $gallery['id'] ] );

    create_session( 'admin_galleries_success_msg', 'Galeri berhasil dihapus' );

    redirect( admin_url() . '/galleries' );
  }

  redirect( canonical_url() );
}

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_gallery_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_gallery_edit_success_msg' );
  delete_session( 'admin_gallery_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_gallery' ) ? 'Tambah' : 'Edit' ) . ' Galeri';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_gallery' ) ? 'Tambah' : 'Edit' );

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.10.0/ckeditor.js';
