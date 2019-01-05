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

$user = $current_user;
$user['photo'] = $current_user_photo;

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['user'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $user_input = sanitize_input( $_POST['user'], [ 'password', 'capabilities' ] );

  if ( empty( $user_input['name'] ) )
    $error_msg[] = 'Silahkan isi nama anda';

  if ( empty( $user_input['username'] ) ) {
    $error_msg[] = 'Silahkan isi username anda';
  } elseif ( strlen( $user_input['username'] ) < 5 ) {
    $error_msg[] = 'Username terlalu singkat. Minimal 5 karakter';
  } elseif ( strlen( $user_input['username'] ) > 20 ) {
    $error_msg[] = 'Username terlalu panjang. Maksimal 20 karakter';
  } elseif ( ! preg_match( '/^[a-zA-Z0-9_]+$/', $user_input['username'] ) ) {
    $error_msg[] = 'Username hanya diizinkan menggunakan kombinasi huruf, angka dan garis bawah (_)';
  } elseif ( strtolower( $user['username'] ) != strtolower( $user_input['username'] ) && $aeo_db->get_count( "SELECT id FROM users WHERE username = '$user_input[username]'" ) == 1 ) {
    $error_msg[] = 'Username sudah digunakan';
  }

  if ( ! empty( $user_input['email'] ) ) {
    if ( ! filter_var( $user_input['email'], FILTER_VALIDATE_EMAIL ) ) {
      $error_msg[] = 'Email tidak valid';
    } elseif ( $user['email'] != $user_input['email'] && $aeo_db->get_count( "SELECT id FROM users WHERE email = '$user_input[email]'" ) == 1 ) {
      $error_msg[] = 'Email sudah digunakan';
    }
  }

  if ( $user_input['password'] && strlen( $user_input['password'] ) < 5 )
    $error_msg[] = 'Password terlalu singkat. Minimal 5 karakter';

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

  if ( isset( $user_input['photo'] ) && empty( $user_input['photo'] ) && $user['photo'] ) {
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
        @mkdir( BASE . '/uploads/users', 0775, true );

      move_uploaded_file( $photo_file_tmp, BASE . '/uploads/users/' . $photo );

      $resize_photo = new resize( BASE . '/uploads/users/' . $photo );
      $resize_photo->resizeImage( 160, 160, 'crop' );
      unlink( BASE . '/uploads/users/' . $photo );
      $resize_photo->saveImage( BASE . '/uploads/users/' . $photo, 100 );

      $user['photo'] = $photo;
    }

    if ( $user['password'] ) {
      $user['password'] = create_password( $user['password'] );
    } else {
      unset( $user['password'] );
    }

    $aeo_db->update( 'users', $user, [ 'id' => $current_user['id'] ] );

    create_session( 'admin_profile_success_msg', 'Profil berhasil diperbarui' );

    redirect( admin_url() . '/profile' );
  }
}

/*----------------------------------------------------------------------------*/

if ( isset( $error_msg ) && is_array( $error_msg ) )
  $error_msg = implode( $error_msg, '<br />' );

if ( get_session( 'admin_profile_success_msg' ) ) {
  $success_msg = get_session( 'admin_profile_success_msg' );
  delete_session( 'admin_profile_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = $aeo['breadcrumb_title'] = 'Edit Profil';
