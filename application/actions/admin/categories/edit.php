<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_category' ) && ! current_user_can( 'admin_add_category' ) || is_route( 'admin_edit_category' ) && ! current_user_can( 'admin_edit_category' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_category' ) ) {
  $id = get_route_vars( 0 );
  $category = $aeo_db->get_row( "SELECT id,name,image FROM categories WHERE md5(id) = '$id'" );

  if ( ! $category ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $category = [
    'name' => '',
    'image' => ''
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['category'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $category_input = sanitize_input( $_POST['category'] );

  if ( empty( $category_input['name'] ) )
    $error_msg[] = 'Silahkan isi nama kategori';

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

  if ( $edit && isset( $category_input['image'] ) && empty( $category_input['image'] ) && $category['image'] ) {
    $old_image_file = BASE . '/uploads/categories/' . $category['image'];
    if ( file_exists( $old_image_file ) )
      unlink( $old_image_file );
  }

  if ( isset( $error_msg ) && isset( $_FILES['image']['name'] ) && $_FILES['image']['name'] )
    $category_input['image'] = '';

  $category = array_merge( $category, $category_input );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $image ) ) {
      if ( ! is_dir( BASE . '/uploads/categories' ) )
        mkdir( BASE . '/uploads/categories', 0775, true );

      move_uploaded_file( $image_file_tmp, BASE . '/uploads/categories/' . $image );

      $category['image'] = $image;
    }

    if ( $edit ) {
      if ( isset( $category['id'] ) ) {
        $id = $category_id = $category['id'];
        unset( $category['id'] );
      }

      $category['updated_datetime'] = time();

      $aeo_db->update( 'categories', $category, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Kategori berhasil diperbarui';
    } else {
      $category['id'] = $category_id = $aeo_db->next_id( 'categories' );
      $category['added_datetime'] = $category['updated_datetime'] = time();

      $aeo_db->insert( 'categories', $category );

      $id = md5( $category['id'] );
      $success_msg = 'Kategori berhasil ditambahkan';
    }

    create_session( 'admin_category_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_category' ) ) {
      redirect( admin_url() . '/categories/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/categories' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    $data_before = $aeo_db->get_row( "SELECT image FROM categories WHERE id = $category[id]" );

    if ( isset( $data_before['image'] ) && $data_before['image'] && is_file( BASE . '/uploads/categories/' . $data_before['image'] ) )
      unlink( BASE . '/uploads/categories/' . $data_before['image'] );

    $aeo_db->delete( 'categories', [ 'id' => $category['id'] ] );

    create_session( 'admin_category_success_msg', 'Kategori barhasil dihapus' );

    redirect( admin_url() . '/categories' );
  }

  redirect( canonical_url() );
}

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_category_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_category_edit_success_msg' );
  delete_session( 'admin_category_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_category' ) ? 'Tambah' : 'Edit' ) . ' Kategori';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_category' ) ? 'Tambah' : 'Edit' );

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.10.0/ckeditor.js';
