<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

require APP . '/functions/security.php';
require APP . '/functions/session.php';
require APP . '/functions/admin.php';
require APP . '/functions/user.php';

/*----------------------------------------------------------------------------*/

if ( is_user_logged_in() )
  redirect( admin_url() );
