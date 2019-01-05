<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

$reset_password_key = ( isset( $_GET['key'] ) && $_GET['key'] ) ? stripslashes( strip_tags( $_GET['key'] ) ) : null;

/*----------------------------------------------------------------------------*/

if ( is_null( $reset_password_key ) )
  redirect( admin_url() . '/password/reset' );

$check_reset_password_key = $aeo_db->get_count( "SELECT id FROM users WHERE reset_password_key = '$reset_password_key' AND role <= 2" );

if ( $check_reset_password_key == 0 ) {
  create_session( 'auth_reset_password_error_msg', 'Kode verifikasi reset password tidak valid' );
  redirect( admin_url() . '/password/reset' );
}

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['create_password'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $create_password = $_POST['create_password'];

  if ( ! isset( $create_password['password'] ) || isset( $create_password['password'] ) && empty( $create_password['password'] ) ) {
    $error_msg = 'Silahkan isi password baru anda';
  } elseif ( strlen( $create_password['password'] ) < 5 ) {
    $error_msg = 'Password terlalu singkat. Minimal 5 karakter';
  } elseif ( ! isset( $create_password['confirm_password'] ) || isset( $create_password['confirm_password'] ) && empty( $create_password['confirm_password'] ) ) {
    $error_msg = 'Silahkan konfirmasi password baru anda';
  } elseif ( $create_password['password'] != $create_password['confirm_password'] ) {
    $error_msg = 'Password tidak cocok';
  }

  if ( ! isset( $error_msg ) ) {
    $password = create_password( $create_password['password'] );
    $aeo_db->update( 'users', [ 'password' => $password, 'reset_password_key' => '' ], [ 'reset_password_key' => $reset_password_key ] );
    create_session( 'auth_login_success_msg', 'Password berhasil diperbarui. Silahkan login' );
    redirect( admin_url() . '/login' );
  }
}

/*----------------------------------------------------------------------------*/

$page['site_title'] = 'Admin &rsaquo; Buat Password';
