<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_event' ) && ! current_user_can( 'admin_add_event' ) || is_route( 'admin_edit_event' ) && ! current_user_can( 'admin_edit_event' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_event' ) ) {
  $id = get_route_vars( 0 );
  $event = $aeo_db->get_row( "SELECT id,title,content,image FROM events WHERE md5(id) = '$id'" );

  if ( ! $event ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $event = [
    'title' => '',
    'content' => '',
    'image' => ''
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['event'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $event_input = sanitize_input( $_POST['event'], [ 'content' ] );

  if ( empty( $event_input['title'] ) )
    $error_msg[] = 'Silahkan isi judul event';

  if ( empty( $event_input['content'] ) )
    $error_msg[] = 'Silahkan isi konten event';

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

  if ( $edit && isset( $event_input['image'] ) && empty( $event_input['image'] ) && $event['image'] ) {
    $old_image_file = BASE . '/uploads/events/' . $event['image'];
    if ( file_exists( $old_image_file ) )
      unlink( $old_image_file );
  }

  if ( isset( $error_msg ) && isset( $_FILES['image']['name'] ) && $_FILES['image']['name'] )
    $event_input['image'] = '';

  $event = array_merge( $event, $event_input );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $image ) ) {
      if ( ! is_dir( BASE . '/uploads/events' ) )
        mkdir( BASE . '/uploads/events', 0775, true );

      move_uploaded_file( $image_file_tmp, BASE . '/uploads/events/' . $image );

      $event['image'] = $image;
    }

    if ( $edit ) {
      if ( isset( $event['id'] ) ) {
        $id = $event_id = $event['id'];
        unset( $event['id'] );
      }

      $event['updated_datetime'] = time();

      $aeo_db->update( 'events', $event, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Event berhasil diperbarui';
    } else {
      $event['id'] = $event_id = $aeo_db->next_id( 'events' );
      $event['added_datetime'] = $event['updated_datetime'] = time();

      $aeo_db->insert( 'events', $event );

      $id = md5( $event['id'] );
      $success_msg = 'Event berhasil ditambahkan';

      $summary = strip_tags( trim( $event['content'] ) );
      $summary = ( strlen( $summary ) > 200 ) ? substr( $summary, 0, 200 ) . ' ...' : $summary;

      $fcm_push_notification_args = [
        'title' => 'Event Terbaru',
        'condition' => [ "'global' in topics" ],
        'payload' => [
          'type' => 'event',
          'detail' => [
            'id' => $event['id'],
            'title' => $event['title'],
            'summary' => $summary,
            'content' => trim( autop( $event['content'] ) ),
            'date' => date( 'd F Y H:i:s', $event['added_datetime'] ),
            'image' => ( $event['image'] ) ? site_url() . '/uploads/events/' . $news['image'] : ''
          ]
        ]
      ];

      firebase( $event['title'], $fcm_push_notification_args, get_option( 'firebase_server_key' ) );
    }

    create_session( 'admin_event_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_event' ) ) {
      redirect( admin_url() . '/events/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/events' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    $data_before = $aeo_db->get_row( "SELECT image FROM events WHERE id = $event[id]" );

    if ( isset( $data_before['image'] ) && $data_before['image'] && is_file( BASE . '/uploads/events/' . $data_before['image'] ) )
      unlink( BASE . '/uploads/events/' . $data_before['image'] );

    $aeo_db->delete( 'events', [ 'id' => $event['id'] ] );

    create_session( 'admin_events_success_msg', 'Event berhasil dihapus' );

    redirect( admin_url() . '/events' );
  }

  redirect( canonical_url() );
}

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_event_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_event_edit_success_msg' );
  delete_session( 'admin_event_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_event' ) ? 'Tambah' : 'Edit' ) . ' Event';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_event' ) ? 'Tambah' : 'Edit' );

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.10.0/ckeditor.js';
