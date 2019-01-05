<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

return [
  'default_section' => 'public',
  'default_action' => 'global',
  'admin_slug' => 'admin',
  'user_session_key' => '__AEO_USER__',
  'csrf_token_key' => '__@#DSF#@__',
  'minify' => true
];
