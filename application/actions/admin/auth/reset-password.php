<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['reset_password'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $reset_password = sanitize_input( $_POST['reset_password'] );

  if ( ! isset( $reset_password['id'] ) || isset( $reset_password['id'] ) && empty( $reset_password['id'] ) ) {
    $error_msg = 'Silahkan isi username atau email anda';
  } elseif ( $aeo_db->get_count( "SELECT id FROM users WHERE role != 4 AND (username = '$reset_password[id]' OR email = '$reset_password[id]')" ) == 0 ) {
    $error_msg = 'Username atau email tidak ditemukan';
  }

  if ( ! isset( $error_msg ) ) {
    $user = $aeo_db->get_row( "SELECT id,username,email FROM users WHERE role != 4 AND (username = '$reset_password[id]' OR email = '$reset_password[id]')" );
    if ( $user['email'] == '' )
      $error_msg = 'Maaf, tidak ada email ditemukan atas akun ini';
  }

  if ( ! isset( $error_msg ) ) {
    $reset_password_key = md5( generate_random( 30 ) );

    $aeo_db->update( 'users', [ 'reset_password_key' => $reset_password_key ], [ 'id' => $user['id'] ] );

    $mail_to = $user['email'];
    $mail_subject = '[' . get_option( 'site_name' ) . '] Konfirmasi Reset Password';
    $mail_message = 'Seseorang baru saja melakukan permintaan reset password atas akun ini:' . "\r\n\r\n";
  	$mail_message.= 'Username: ' . $user['username'] . "\r\n\r\n";
  	$mail_message.= 'Jika ini adalah sebuah kesalahan dan anda tidak pernah merasa melakukan permintaan ini, cukup abaikan saja email ini.'  . "\r\n\r\n";
  	$mail_message.= 'Untuk membuat password baru, silahkan kunjungi tautan dibawah:' . "\r\n\r\n";
  	$mail_message.= '<' . admin_url() . '/password/create?key=' . $reset_password_key . '>' . "\r\n";
    $mail_headers = 'From: ' . get_option( 'admin_email' ) . "\r\n";
    $mail_headers.= 'X-Mailer: PHP/' . phpversion();

    @mail( $mail_to, $mail_subject, $mail_message, $mail_headers );

    create_session( 'auth_login_success_msg', 'Link konfirmasi reset password telah dikirim ke email anda' );

    redirect( admin_url() . '/login' );
  }
}

/*----------------------------------------------------------------------------*/

if ( get_session( 'auth_reset_password_error_msg' ) ) {
  $error_msg = get_session( 'auth_reset_password_error_msg' );
  delete_session( 'auth_reset_password_error_msg' );
}

/*----------------------------------------------------------------------------*/

$page['site_title'] = 'Admin &rsaquo; Reset Password';
