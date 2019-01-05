<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

require APP . '/libraries/class.resize.php';

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_add_user' ) && ! current_user_can( 'admin_add_user' ) || is_route( 'admin_edit_user' ) && ! current_user_can( 'admin_edit_user' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_edit_user' ) ) {
  $id = get_route_vars( 0 );
  $user = $aeo_db->get_row( "SELECT id,name,username,email,photo,role,capabilities,status FROM users WHERE md5(id) = '$id'" );

  if ( ! $user ) {
    $aeo_router->set_found( false );
    return false;
  } else {
    $edit = true;
    $user['capabilities'] = ( $user['capabilities'] && is_json( $user['capabilities'] ) ) ? json_decode( $user['capabilities'], true ) : [];
  }
}

/*----------------------------------------------------------------------------*/

else {
  $edit = false;
  $user = [
    'name' => '',
    'username' => '',
    'email' => '',
    'photo' => '',
    'role' => 2,
    'capabilities' => [],
    'status' => 1
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['user'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $user_input = sanitize_input( $_POST['user'], [ 'password', 'capabilities' ] );

  if ( empty( $user_input['name'] ) )
    $error_msg[] = 'Silahkan isi nama';

  if ( empty( $user_input['username'] ) ) {
    $error_msg[] = 'Silahkan isi username';
  } elseif ( strlen( $user_input['username'] ) < 5 ) {
    $error_msg[] = 'Username terlalu singkat. Minimal 5 karakter';
  } elseif ( strlen( $user_input['username'] ) > 20 ) {
    $error_msg[] = 'Username terlalu panjang. Maksimal 20 karakter';
  } elseif ( ! preg_match( '/^[a-zA-Z0-9_]+$/', $user_input['username'] ) ) {
    $error_msg[] = 'Username hanya diizinkan menggunakan kombinasi huruf, angka dan garis bawah (_)';
  } elseif ( strtolower( $user['username'] ) !== strtolower( $user_input['username'] ) && $aeo_db->get_count( "SELECT id FROM users WHERE username = '$user_input[username]'" ) === 1 ) {
    $error_msg[] = 'Username sudah digunakan';
  }

  if ( ! empty( $user_input['email'] ) ) {
    if ( ! filter_var( $user_input['email'], FILTER_VALIDATE_EMAIL ) ) {
      $error_msg[] = 'Email tidak valid';
    } elseif ( $user['email'] != $user_input['email'] && $aeo_db->get_count( "SELECT id FROM users WHERE email = '$user_input[email]'" ) === 1 ) {
      $error_msg[] = 'Email sudah digunakan';
    }
  }

  if ( ! $edit && empty( $user_input['password'] ) ) {
    $error_msg[] = 'Silahkan isi password';
  } elseif ( $user_input['password'] && strlen( $user_input['password'] ) < 5 ) {
    $error_msg[] = 'Password terlalu singkat. Minimal 5 karakter';
  }

  if ( ! isset( $error_msg ) ) {
    if ( isset( $_FILES['photo']['name'] ) && $_FILES['photo']['name'] ) {
      $photo_file = $_FILES['photo'];
      if ( ! in_array( $photo_file['type'], [ 'image/jpeg', 'image/jpg', 'image/png', 'image/gif' ] ) ) {
        $error_msg[] = 'Foto harus menggunakan ekstensi berikut: .jpg, .jpeg, .png atau .gif';
      } elseif ( $photo_file['size'] > ( 1024 * 1024 ) ) {
        $error_msg[] = 'Ukuran foto maksimal adalah 1MB';
      } elseif ( $photo_file['error'] > 0 ) {
        $error_msg[] = 'File foto error';
      } else {
        $photo_file_tmp = $photo_file['tmp_name'];
        $photo_file_name_parts = explode( '.', $photo_file['name'] );
        $photo = time() . '_' . strtolower( $user_input['username'] ) . '.' . @end( $photo_file_name_parts );
      }
    }
  }

  if ( $edit && isset( $user_input['photo'] ) && empty( $user_input['photo'] ) && $user['photo'] ) {
    $old_photo_file = BASE . '/uploads/users/' . $user['photo'];
    if ( file_exists( $old_photo_file ) )
      unlink( $old_photo_file );
  }

  if ( isset( $error_msg ) && isset( $_FILES['photo']['name'] ) && $_FILES['photo']['name'] )
    $user_input['photo'] = '';

  $user = array_merge( $user, $user_input );

  if ( ! isset( $error_msg ) ) {
    if ( isset( $photo ) ) {
      if ( ! is_dir( BASE . '/uploads/users' ) )
        mkdir( BASE . '/uploads/users', 0775, true );

      move_uploaded_file( $photo_file_tmp, BASE . '/uploads/users/' . $photo );

      $resize_photo = new resize( BASE . '/uploads/users/' . $photo );
      $resize_photo->resizeImage( 160, 160, 'crop' );
      unlink( BASE . '/uploads/users/' . $photo );
      $resize_photo->saveImage( BASE . '/uploads/users/' . $photo, 100 );

      $user['photo'] = $photo;
    }

    $user['capabilities'] = ( $user['capabilities'] && is_array( $user['capabilities'] ) ) ? json_encode( $user['capabilities'] ) : '';

    if ( $edit ) {
      if ( $user['password'] ) {
        $user['password'] = create_password( $user['password'] );
      } else {
        unset( $user['password'] );
      }

      if ( isset( $user['id'] ) ) {
        $id = $user_id = $user['id'];
        unset( $user['id'] );
      }

      $user['updated_datetime'] = time();

      $aeo_db->update( 'users', $user, [ 'id' => $id ] );

      $id = md5( $id );
      $success_msg = 'Pengguna berhasil diperbarui';
    } else {
      $user['id'] = $user_id = $aeo_db->next_id( 'users' );
      $user['password'] = create_password( $user['password'] );
      $user['added_datetime'] = $user['updated_datetime'] = time();

      $aeo_db->insert( 'users', $user );

      $id = md5( $user['id'] );
      $success_msg = 'Pengguna berhasil ditambahkan';
    }

    create_session( 'admin_user_edit_success_msg', $success_msg );

    if ( current_user_can( 'admin_edit_user' ) ) {
      redirect( admin_url() . '/users/' . $id . '/edit' );
    } else {
      redirect( admin_url() . '/users' );
    }
  }
}

elseif ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete' ) {
    if ( $user['id'] > 1 ) {
      $data_before = $aeo_db->get_row( "SELECT photo FROM users WHERE id = $user[id]" );

      if ( isset( $data_before['photo'] ) && $data_before['photo'] && is_file( BASE . '/uploads/users/' . $data_before['photo'] ) )
        unlink( BASE . '/uploads/users/' . $data_before['photo'] );

      $aeo_db->delete( 'users', [ 'id' => $user['id'] ] );

      create_session( 'admin_users_success_msg', 'Pengguna berhasil dihapus' );

      redirect( admin_url() . '/users' );
    }
  }

  redirect( canonical_url() );
}

/*----------------------------------------------------------------------------*/

$aeo['user_capabilities'][] = [
  'group' => 'Slideshow',
  'items' => [
    [ 'value' => 'admin_slideshows', 'label' => 'Kelola Slideshow' ],
    [ 'value' => 'admin_add_slideshow', 'label' => 'Tambah Slideshow' ],
    [ 'value' => 'admin_edit_slideshow', 'label' => 'Edit Slideshow' ],
    [ 'value' => 'admin_delete_slideshow', 'label' => 'Hapus Slideshow' ]
  ]
];

$aeo['user_capabilities'][] = [
  'group' => 'Halaman',
  'items' => [
    [ 'value' => 'admin_pages', 'label' => 'Kelola Halaman' ],
    [ 'value' => 'admin_add_page', 'label' => 'Tambah Halaman' ],
    [ 'value' => 'admin_edit_page', 'label' => 'Edit Halaman' ],
    [ 'value' => 'admin_delete_page', 'label' => 'Hapus Halaman' ]
  ]
];

$aeo['user_capabilities'][] = [
  'group' => 'Berita',
  'items' => [
    [ 'value' => 'admin_news', 'label' => 'Kelola Berita' ],
    [ 'value' => 'admin_add_news', 'label' => 'Tambah Berita' ],
    [ 'value' => 'admin_edit_news', 'label' => 'Edit Berita' ],
    [ 'value' => 'admin_delete_news', 'label' => 'Hapus Berita' ]
  ]
];

$aeo['user_capabilities'][] = [
  'group' => 'Event',
  'items' => [
    [ 'value' => 'admin_events', 'label' => 'Kelola Event' ],
    [ 'value' => 'admin_add_event', 'label' => 'Event Event' ],
    [ 'value' => 'admin_edit_event', 'label' => 'Edit Event' ],
    [ 'value' => 'admin_delete_event', 'label' => 'Hapus Event' ]
  ]
];

$aeo['user_capabilities'][] = [
  'group' => 'Destinasi',
  'items' => [
    [ 'value' => 'admin_destinations', 'label' => 'Kelola Destinasi' ],
    [ 'value' => 'admin_add_destination', 'label' => 'Tambah Destinasi' ],
    [ 'value' => 'admin_edit_destination', 'label' => 'Edit Destinasi' ],
    [ 'value' => 'admin_delete_destination', 'label' => 'Hapus Destinasi' ],
    [ 'value' => 'admin_destination_categories', 'label' => 'Kelola Kategori Destinasi' ],
    [ 'value' => 'admin_add_destination_category', 'label' => 'Tambah Kategori Destinasi' ],
    [ 'value' => 'admin_edit_destination_category', 'label' => 'Edit Kategori Destinasi' ],
    [ 'value' => 'admin_delete_destination_category', 'label' => 'Hapus Kategori Destinasi' ]
  ]
];

$aeo['user_capabilities'][] = [
  'group' => 'Tautan',
  'items' => [
    [ 'value' => 'admin_links', 'label' => 'Kelola Tautan' ],
    [ 'value' => 'admin_add_link', 'label' => 'Tambah Tautan' ],
    [ 'value' => 'admin_edit_link', 'label' => 'Edit Tautan' ],
    [ 'value' => 'admin_delete_link', 'label' => 'Hapus Tautan' ]
  ]
];

$aeo['user_capabilities'][] = [
  'group' => 'Download',
  'items' => [
    [ 'value' => 'admin_downloads', 'label' => 'Kelola Download' ],
    [ 'value' => 'admin_add_download', 'label' => 'Tambah Download' ],
    [ 'value' => 'admin_edit_download', 'label' => 'Edit Download' ],
    [ 'value' => 'admin_delete_download', 'label' => 'Hapus Download' ]
  ]
];

$aeo['user_capabilities'][] = [
  'group' => 'Galeri',
  'items' => [
    [ 'value' => 'admin_galleries', 'label' => 'Kelola Galeri' ],
    [ 'value' => 'admin_add_gallery', 'label' => 'Tambah Galeri' ],
    [ 'value' => 'admin_edit_gallery', 'label' => 'Edit Galeri' ],
    [ 'value' => 'admin_delete_gallery', 'label' => 'Hapus Galeri' ],
    [ 'value' => 'admin_gallery_categories', 'label' => 'Kelola Kategori Galeri' ],
    [ 'value' => 'admin_add_gallery_category', 'label' => 'Tambah Kategori Galeri' ],
    [ 'value' => 'admin_edit_gallery_category', 'label' => 'Edit Kategori Galeri' ],
    [ 'value' => 'admin_delete_gallery_category', 'label' => 'Hapus Kategori Galeri' ]
  ]
];

$aeo['user_capabilities'][] = [
  'group' => 'Pengguna',
  'items' => [
    [ 'value' => 'admin_users', 'label' => 'Kelola Pengguna' ],
    [ 'value' => 'admin_add_user', 'label' => 'Tambah Pengguna' ],
    [ 'value' => 'admin_edit_user', 'label' => 'Edit Pengguna' ],
    [ 'value' => 'admin_delete_user', 'label' => 'Hapus Pengguna' ]
  ]
];

if ( (int) $current_user['role'] === 1 ) {
  $aeo['user_capabilities'][] = [
    'group' => 'Pengaturan',
    'items' => [
      [ 'value' => 'admin-settings', 'label' => 'Pengaturan Umum' ]
    ]
  ];
}

/*----------------------------------------------------------------------------*/

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_user_edit_success_msg' ) ) {
  $success_msg = get_session( 'admin_user_edit_success_msg' );
  delete_session( 'admin_user_edit_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = ( is_route( 'admin_add_user' ) ? 'Tambah' : 'Edit' ) . ' Pengguna';
$aeo['breadcrumb_title'] = ( is_route( 'admin_add_user' ) ? 'Tambah' : 'Edit' );
