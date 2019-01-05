<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

require APP . '/functions/session.php';
require APP . '/functions/admin.php';
require APP . '/functions/user.php';

/*----------------------------------------------------------------------------*/

if ( is_user_logged_in() ) {
  delete_session( user_session_key() );
  create_session( 'auth_login_success_msg', 'Anda telah log out' );
}

/*----------------------------------------------------------------------------*/

redirect( admin_url() . '/login' );
