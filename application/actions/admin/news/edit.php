<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_news' ) && ! current_user_can( 'admin_add_news' ) || is_route( 'admin_edit_news' ) && ! current_user_can( 'admin_edit_news' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_news' ) ) {
  $id = get_route_vars( 0 );
  $news = $aeo_db->get_row( "SELECT id,title,content,image FROM news WHERE md5(id) = '$id'" );

  if ( ! $news ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $news = [
    'title' => '',
    'content' => '',
    'image' => ''
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['news'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $news_input = sanitize_input( $_POST['news'], [ 'content' ] );

  if ( empty( $news_input['title'] ) )
    $error_msg[] = 'Silahkan isi judul berita';

  if ( empty( $news_input['content'] ) )
    $error_msg[] = 'Silahkan isi kontent berita';

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

  if ( $edit && isset( $news_input['image'] ) && empty( $news_input['image'] ) && $news['image'] ) {
    $old_image_file = BASE . '/uploads/news/' . $news['image'];
    if ( file_exists( $old_image_file ) )
      unlink( $old_image_file );
  }

  if ( isset( $error_msg ) && isset( $_FILES['image']['name'] ) && $_FILES['image']['name'] )
    $news_input['image'] = '';

  $news = array_merge( $news, $news_input );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $image ) ) {
      if ( ! is_dir( BASE . '/uploads/news' ) )
        mkdir( BASE . '/uploads/news', 0775, true );

      move_uploaded_file( $image_file_tmp, BASE . '/uploads/news/' . $image );

      $news['image'] = $image;
    }

    if ( $edit ) {
      if ( isset( $news['id'] ) ) {
        $id = $news_id = $news['id'];
        unset( $news['id'] );
      }

      $news['updated_datetime'] = time();

      $aeo_db->update( 'news', $news, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Berita berhasil diperbarui';
    } else {
      $news['id'] = $news_id = $aeo_db->next_id( 'news' );
      $news['user_id'] = $current_user['id'];
      $news['added_datetime'] = $news['updated_datetime'] = time();

      $aeo_db->insert( 'news', $news );

      $id = md5( $news['id'] );
      $success_msg = 'Berita berhasil ditambahkan';

      $summary = strip_tags( trim( $news['content'] ) );
      $summary = ( strlen( $summary ) > 200 ) ? substr( $summary, 0, 200 ) . ' ...' : $summary;

      $fcm_push_notification_args = [
        'title' => 'Berita Terbaru',
        'condition' => [ "'global' in topics" ],
        'payload' => [
          'type' => 'news',
          'detail' => [
            'id' => $news['id'],
            'title' => $news['title'],
            'summary' => $summary,
            'content' => trim( autop( $news['content'] ) ),
            'date' => date( 'd F Y H:i:s', $news['added_datetime'] ),
            'image' => ( $news['image'] ) ? site_url() . '/uploads/news/' . $news['image'] : ''
          ]
        ]
      ];

      firebase( $news['title'], $fcm_push_notification_args, get_option( 'firebase_server_key' ) );
    }

    create_session( 'admin_news_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_news' ) ) {
      redirect( admin_url() . '/news/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/news' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    $data_before = $aeo_db->get_row( "SELECT image FROM news WHERE id = $news[id]" );

    if ( isset( $data_before['image'] ) && $data_before['image'] && is_file( BASE . '/uploads/news/' . $data_before['image'] ) )
      unlink( BASE . '/uploads/news/' . $data_before['image'] );

    $aeo_db->delete( 'news', [ 'id' => $news['id'] ] );

    create_session( 'admin_news_success_msg', 'Berita berhasil dihapus' );

    redirect( admin_url() . '/news' );
  }

  redirect( canonical_url() );
}

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_news_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_news_edit_success_msg' );
  delete_session( 'admin_news_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_news' ) ? 'Tambah' : 'Edit' ) . ' Berita';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_news' ) ? 'Tambah' : 'Edit' );

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.10.0/ckeditor.js';
