<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_destination' ) && ! current_user_can( 'admin_add_destination' ) || is_route( 'admin_edit_destination' ) && ! current_user_can( 'admin_edit_destination' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_destination' ) ) {
  $id = get_route_vars( 0 );
  $destination = $aeo_db->get_row( "SELECT id,category_id,name,content,address,image,latitude,longitude FROM destinations WHERE md5(id) = '$id'" );

  if ( ! $destination ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $destination = [
    'category_id' => '',
    'name' => '',
    'content' => '',
    'address' => '',
    'image' => '',
    'latitude' => '',
    'longitude' => ''
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['destination'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $destination_input = sanitize_input( $_POST['destination'], [ 'content' ] );

  if ( empty( $destination_input['name'] ) )
    $error_msg[] = 'Silahkan isi nama destinasi';

  if ( empty( $destination_input['category_id'] ) )
    $error_msg[] = 'Silahkan pilih kategori destinasi';

  if ( empty( $destination_input['content'] ) )
    $error_msg[] = 'Silahkan isi konten destinasi';

  if ( empty( $destination_input['address'] ) )
    $error_msg[] = 'Silahkan isi alamat destinasi';

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

  if ( $edit && isset( $destination_input['image'] ) && empty( $destination_input['image'] ) && $destination['image'] ) {
    $old_image_file = BASE . '/uploads/destinations/' . $destination['image'];
    if ( file_exists( $old_image_file ) )
      unlink( $old_image_file );
  }

  if ( isset( $error_msg ) && isset( $_FILES['image']['name'] ) && $_FILES['image']['name'] )
    $destination_input['image'] = '';

  $destination = array_merge( $destination, $destination_input );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $image ) ) {
      if ( ! is_dir( BASE . '/uploads/destinations' ) )
        mkdir( BASE . '/uploads/destinations', 0775, true );

      move_uploaded_file( $image_file_tmp, BASE . '/uploads/destinations/' . $image );

      $destination['image'] = $image;
    }

    if ( $edit ) {
      if ( isset( $destination['id'] ) ) {
        $id = $destination_id = $destination['id'];
        unset( $destination['id'] );
      }

      $destination['updated_datetime'] = time();

      $aeo_db->update( 'destinations', $destination, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Destinasi berhasil diperbarui';
    } else {
      $destination['id'] = $destination_id = $aeo_db->next_id( 'destinations' );
      $destination['added_datetime'] = $destination['updated_datetime'] = time();

      $aeo_db->insert( 'destinations', $destination );

      $id = md5( $destination['id'] );
      $success_msg = 'Destinasi berhasil ditambahkan';

      $summary = strip_tags( trim( $destination['content'] ) );
      $summary = ( strlen( $summary ) > 200 ) ? substr( $summary, 0, 200 ) . ' ...' : $summary;

      if ( $destination['latitude'] && $destination['longitude'] )
        $distance = $aeo_db->get_row( "SELECT ( 6373 * acos( cos( radians( $destination[latitude] ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( $destination[longitude] ) ) + sin( radians( $destination[latitude] ) ) * sin( radians( latitude ) ) ) ) AS distance FROM destinations WHERE id = $destination[id]" );

      $fcm_push_notification_args = [
        'title' => 'Destinasi Terbaru',
        'condition' => [ "'global' in topics" ],
        'payload' => [
          'type' => 'destination',
          'detail' => [
            'id' => $destination['id'],
            'name' => $destination['name'],
            'summary' => $summary,
            'content' => trim( autop( $destination['content'] ) ),
            'address' => trim( $destination['address'] ),
            'distance' => ( isset( $distance ) ) ? number_format( $distance['distance'], 2 ) : '',
            'latitude' => $destination['latitude'],
            'longitude' => $destination['longitude'],
            'image' => ( $destination['image'] ) ? site_url() . '/uploads/destinations/' . $destination['image'] : ''
          ]
        ]
      ];

      firebase( $destination['name'], $fcm_push_notification_args, get_option( 'firebase_server_key' ) );
    }

    create_session( 'admin_destination_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_destination' ) ) {
      redirect( admin_url() . '/destinations/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/destinations' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    $data_before = $aeo_db->get_row( "SELECT image FROM destinations WHERE id = $destination[id]" );

    if ( isset( $data_before['image'] ) && $data_before['image'] && is_file( BASE . '/uploads/destinations/' . $data_before['image'] ) )
      unlink( BASE . '/uploads/destinations/' . $data_before['image'] );

    $aeo_db->delete( 'destinations', [ 'id' => $destination['id'] ] );

    create_session( 'admin_destinations_success_msg', 'Destinasi berhasil dihapus' );

    redirect( admin_url() . '/destinations' );
  }

  redirect( canonical_url() );
}

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_destination_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_destination_edit_success_msg' );
  delete_session( 'admin_destination_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_destination' ) ? 'Tambah' : 'Edit' ) . ' Destinasi';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_destination' ) ? 'Tambah' : 'Edit' );

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.10.0/ckeditor.js';
