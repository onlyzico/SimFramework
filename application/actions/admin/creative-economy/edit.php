<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_creative_economy' ) && ! current_user_can( 'admin_add_creative_economy' ) || is_route( 'admin_edit_creative_economy' ) && ! current_user_can( 'admin_edit_creative_economy' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_creative_economy' ) ) {
  $id = get_route_vars( 0 );
  $creative_economy = $aeo_db->get_row( "SELECT id,category_id,name,content,address,image,latitude,longitude FROM creative_economy WHERE md5(id) = '$id'" );

  if ( ! $creative_economy ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $creative_economy = [
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

if ( isset( $_POST['creative_economy'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $creative_economy_input = sanitize_input( $_POST['creative_economy'], [ 'content' ] );

  if ( empty( $creative_economy_input['name'] ) )
    $error_msg[] = 'Silahkan isi nama ekraf';

  if ( empty( $creative_economy_input['category_id'] ) )
    $error_msg[] = 'Silahkan pilih kategori ekraf';

  if ( empty( $creative_economy_input['content'] ) )
    $error_msg[] = 'Silahkan isi konten ekraf';

  if ( empty( $creative_economy_input['address'] ) )
    $error_msg[] = 'Silahkan isi alamat ekraf';

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

  if ( $edit && isset( $creative_economy_input['image'] ) && empty( $creative_economy_input['image'] ) && $creative_economy['image'] ) {
    $old_image_file = BASE . '/uploads/creative-economy/' . $creative_economy['image'];
    if ( file_exists( $old_image_file ) )
      unlink( $old_image_file );
  }

  if ( isset( $error_msg ) && isset( $_FILES['image']['name'] ) && $_FILES['image']['name'] )
    $creative_economy_input['image'] = '';

  $creative_economy = array_merge( $creative_economy, $creative_economy_input );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $image ) ) {
      if ( ! is_dir( BASE . '/uploads/creative-economy' ) )
        mkdir( BASE . '/uploads/creative-economy', 0775, true );

      move_uploaded_file( $image_file_tmp, BASE . '/uploads/creative-economy/' . $image );

      $creative_economy['image'] = $image;
    }

    if ( $edit ) {
      if ( isset( $creative_economy['id'] ) ) {
        $id = $creative_economy_id = $creative_economy['id'];
        unset( $creative_economy['id'] );
      }

      $creative_economy['updated_datetime'] = time();

      $aeo_db->update( 'creative_economy', $creative_economy, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Ekraf berhasil diperbarui';
    } else {
      $creative_economy['id'] = $creative_economy_id = $aeo_db->next_id( 'creative_economy' );
      $creative_economy['added_datetime'] = $creative_economy['updated_datetime'] = time();

      $aeo_db->insert( 'creative_economy', $creative_economy );

      $id = md5( $creative_economy['id'] );
      $success_msg = 'Ekraf berhasil ditambahkan';

      $summary = strip_tags( trim( $creative_economy['content'] ) );
      $summary = ( strlen( $summary ) > 200 ) ? substr( $summary, 0, 200 ) . ' ...' : $summary;

      if ( $creative_economy['latitude'] && $creative_economy['longitude'] )
        $distance = $aeo_db->get_row( "SELECT ( 6373 * acos( cos( radians( $creative_economy[latitude] ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( $creative_economy[longitude] ) ) + sin( radians( $creative_economy[latitude] ) ) * sin( radians( latitude ) ) ) ) AS distance FROM creative_economy WHERE id = $creative_economy[id]" );

      $fcm_push_notification_args = [
        'title' => 'Ekonomi Kreatif Terbaru',
        'condition' => [ "'global' in topics" ],
        'payload' => [
          'type' => 'creative_economy',
          'detail' => [
            'id' => $creative_economy['id'],
            'name' => $creative_economy['name'],
            'summary' => $summary,
            'content' => trim( autop( $creative_economy['content'] ) ),
            'address' => trim( $creative_economy['address'] ),
            'distance' => ( isset( $distance ) ) ? number_format( $distance['distance'], 2 ) : '',
            'latitude' => $creative_economy['latitude'],
            'longitude' => $creative_economy['longitude'],
            'image' => ( $creative_economy['image'] ) ? site_url() . '/uploads/creative-economy/' . $creative_economy['image'] : ''
          ]
        ]
      ];

      firebase( $creative_economy['name'], $fcm_push_notification_args, get_option( 'firebase_server_key' ) );
    }

    create_session( 'admin_creative_economy_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_creative_economy' ) ) {
      redirect( admin_url() . '/creative-economy/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/creative-economy' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    $data_before = $aeo_db->get_row( "SELECT image FROM creative_economy WHERE id = $creative_economy[id]" );

    if ( isset( $data_before['image'] ) && $data_before['image'] && is_file( BASE . '/uploads/creative-economy/' . $data_before['image'] ) )
      unlink( BASE . '/uploads/creative-economy/' . $data_before['image'] );

    $aeo_db->delete( 'creative_economy', [ 'id' => $creative_economy['id'] ] );

    create_session( 'admin_creative_economy_success_msg', 'Ekraf berhasil dihapus' );

    redirect( admin_url() . '/creative-economy' );
  }

  redirect( canonical_url() );
}

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_creative_economy_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_creative_economy_edit_success_msg' );
  delete_session( 'admin_creative_economy_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_creative_economy' ) ? 'Tambah' : 'Edit' ) . ' Ekraf';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_creative_economy' ) ? 'Tambah' : 'Edit' );

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.10.0/ckeditor.js';
