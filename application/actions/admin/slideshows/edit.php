<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_slideshow' ) && ! current_user_can( 'admin_add_slideshow' ) || is_route( 'admin_edit_slideshow' ) && ! current_user_can( 'admin_edit_slideshow' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_slideshow' ) ) {
  $id = get_route_vars( 0 );
  $slideshow = $aeo_db->get_row( "SELECT id,title,content,image FROM slideshows WHERE md5(id) = '$id'" );

  if ( ! $slideshow ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $slideshow = [
    'title' => '',
    'content' => '',
    'image' => ''
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['slideshow'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $slideshow_input = sanitize_input( $_POST['slideshow'] );

  if ( empty( $slideshow_input['title'] ) )
    $error_msg[] = 'Silahkan isi judul slideshow';

  if ( ! isset( $error_msg ) ) {
    if ( isset( $_FILES['image']['name'] ) && $_FILES['image']['name'] ) {
      $image_file = $_FILES['image'];
      if ( ! in_array( $image_file['type'], [ 'image/jpeg', 'image/jpg', 'image/png', 'image/gif' ] ) ) {
        $error_msg[] = 'Gambar harus menggunakan ekstensi berikut: .jpg, .jpeg, .png atau .gif';
      } elseif ( $image_file['size'] > ( ( 1024 * 1024 ) * 5 ) ) {
        $error_msg[] = 'Ukuran gambar maksimal adalah 5MB';
      } elseif ( $image_file['error'] > 0 ) {
        $error_msg[] = 'File gambar error';
      } else {
        $image_file_tmp = $image_file['tmp_name'];
        $image = time() . '_' . $image_file['name'];
      }
    }
  }

  if ( $edit && isset( $slideshow_input['image'] ) && empty( $slideshow_input['image'] ) && $slideshow['image'] ) {
    $old_image_file = BASE . '/uploads/slideshows/' . $slideshow['image'];
    if ( file_exists( $old_image_file ) )
      unlink( $old_image_file );
  }

  if ( isset( $error_msg ) && isset( $_FILES['image']['name'] ) && $_FILES['image']['name'] )
    $slideshow_input['image'] = '';

  $slideshow = array_merge( $slideshow, $slideshow_input );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $image ) ) {
      if ( ! is_dir( BASE . '/uploads/slideshows' ) )
        mkdir( BASE . '/uploads/slideshows', 0775, true );

      move_uploaded_file( $image_file_tmp, BASE . '/uploads/slideshows/' . $image );

      $slideshow['image'] = $image;
    }

    if ( $edit ) {
      if ( isset( $slideshow['id'] ) ) {
        $id = $slideshow_id = $slideshow['id'];
        unset( $slideshow['id'] );
      }

      $slideshow['updated_datetime'] = time();

      $aeo_db->update( 'slideshows', $slideshow, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Slideshow berhasil diperbarui';
    } else {
      $slideshow['id'] = $slideshow_id = $aeo_db->next_id( 'slideshows' );
      $slideshow['added_datetime'] = $slideshow['updated_datetime'] = time();

      $aeo_db->insert( 'slideshows', $slideshow );

      $id = md5( $slideshow['id'] );
      $success_msg = 'Slideshow berhasil ditambahkan';
    }

    create_session( 'admin_slideshow_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_slideshow' ) ) {
      redirect( admin_url() . '/slideshows/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/slideshows' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    $data_before = $aeo_db->get_row( "SELECT image FROM slideshows WHERE id = $slideshow[id]" );

    if ( isset( $data_before['image'] ) && $data_before['image'] && is_file( BASE . '/uploads/slideshows/' . $data_before['image'] ) )
      unlink( BASE . '/uploads/slideshows/' . $data_before['image'] );

    $aeo_db->delete( 'slideshows', [ 'id' => $slideshow['id'] ] );

    create_session( 'admin_slideshow_success_msg', 'Slideshow berhasil dihapus' );

    redirect( admin_url() . '/slideshows' );
  }

  redirect( canonical_url() );
}

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_slideshow_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_slideshow_edit_success_msg' );
  delete_session( 'admin_slideshow_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_slideshow' ) ? 'Tambah' : 'Edit' ) . ' Slideshow';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_slideshow' ) ? 'Tambah' : 'Edit' );

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.10.0/ckeditor.js';
