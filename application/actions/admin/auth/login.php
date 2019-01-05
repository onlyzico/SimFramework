<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['login'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $login = sanitize_input( $_POST['login'], [ 'password' ] );

  if ( ! isset( $login['id'] ) || isset( $login['id'] ) && empty( $login['id'] ) ) {
    $error_msg = 'Silahkan isi username atau email anda';
  } elseif ( $aeo_db->get_count( "SELECT id FROM users WHERE (username = '$login[id]' OR email = '$login[id]')" ) == 0 ) {
    $error_msg = 'Username atau email tidak ditemukan';
  } elseif ( ! isset( $login['password'] ) || isset( $login['password'] ) && empty( $login['password'] ) ) {
    $error_msg = 'Silahkan isi password anda';
  }

  if ( ! isset( $error_msg ) ) {
    $user = $aeo_db->get_row( "SELECT id,password,role,status FROM users WHERE role != 4 AND (username = '$login[id]' OR email = '$login[id]')" );
    if ( ! check_password( $user['password'], $login['password'] ) )
      $error_msg = 'ID login dan password tidak cocok';
  }

  if ( ! isset( $error_msg ) && isset( $user ) && $user['status'] > 1 )
    $error_msg = 'Maaf, akses di blokir untuk akun ini';

  if ( ! isset( $error_msg ) ) {
    $aeo_db->update( 'users', [ 'login_datetime' => time() ], [ 'id' => $user['id'] ] );

    create_session( user_session_key(), md5( $user['id'] ) );

    $redirect_url = site_url();
    $redirect_url.= ( isset( $_GET['redirect'] ) && $_GET['redirect'] ) ? stripslashes( strip_tags( urldecode( $_GET['redirect'] ) ) ) : '/' . $aeo_config['options']['admin_slug'];

    redirect( $redirect_url );
  }
}

/*----------------------------------------------------------------------------*/

if ( get_session( 'auth_login_success_msg' ) ) {
  $success_msg = get_session( 'auth_login_success_msg' );
  delete_session( 'auth_login_success_msg' );
}

/*----------------------------------------------------------------------------*/

$page['site_title'] = 'Admin &rsaquo; Login';
