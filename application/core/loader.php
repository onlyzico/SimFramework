<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

$aeo_config['database'] = require_once APP . '/config/database.php';
$aeo_config['options'] = require_once APP . '/config/options.php';
$aeo_config['routes'] = require_once APP . '/config/routes.php';

/*----------------------------------------------------------------------------*/

if ( $aeo_config['database'] ) {
  require_once APP . '/core/database.php';
  $aeo_db = new Database( $aeo_config['database'] );
}

/*----------------------------------------------------------------------------*/

require_once APP . '/functions/helper.php';
require_once APP . '/functions/link.php';
require_once APP . '/functions/route.php';
require_once APP . '/functions/options.php';

/*----------------------------------------------------------------------------*/

/* Add Option */
/*
$option['name'] = 'site_name';
$option['content'] = 'AEOFramework';
$option['label'] = 'Site Name';

add_option( $option );
*/

/* Add User */
/*
require_once APP . '/functions/security.php';

$user['id'] = $aeo_db->next_id( 'users' );
$user['name'] = 'Super Admin';
$user['username'] = 'superadmin';
$user['password'] = create_password( 'superadmin' );
$user['role'] = 1;
$user['added_datetime'] = $user['updated_datetime'] = time();

$aeo_db->insert( 'users', $user );
*/

/*----------------------------------------------------------------------------*/

require_once APP . '/core/uri.php';

$aeo_uri = new URI;
$aeo_uri->handler();

/*----------------------------------------------------------------------------*/

require_once APP . '/core/router.php';

$aeo_router = new Router;
$aeo_router->dispatch();

/*----------------------------------------------------------------------------*/

if ( get_route( 'actions' ) && is_array( get_route( 'actions' ) ) ) {
  foreach ( get_route( 'actions' ) as $action ) {
    $action_file = APP . '/actions/' . get_route( 'section' ) . '/' . $action . '.php';

    if ( file_exists( $action_file ) )
      require_once $action_file;

    unset( $action, $action_file );
  }
}

/*----------------------------------------------------------------------------*/

if ( ! $aeo_router->is_found() ) {
  $aeo_router->set_404();

  foreach ( get_route( 'actions' ) as $action ) {
    $action_file = APP . '/actions/' . get_route( 'section' ) . '/' . $action . '.php';

    if ( file_exists( $action_file ) )
      require_once $action_file;

    unset( $action, $action_file );
  }
}

/*----------------------------------------------------------------------------*/

if ( get_route( 'template' ) ) {
  require_once APP . '/functions/template.php';

  $_views_dir = APP . '/views/' . get_route( 'section' );
  $_views_path = '/' . basename( APP ) . '/views/' . get_route( 'section' );
  $_template_file = $_views_dir . '/' . get_route( 'template' ) . '.php';

  if ( file_exists( $_template_file ) ) {
    if ( $aeo_config['options']['minify'] ) {
      require_once APP . '/libraries/minifier.php';
      ob_start();
      include_once $_template_file;
      $_output = ob_get_clean();
      echo minify_html( $_output );
      unset( $_output );
    } else {
      include_once $_template_file;
    }
  } else {
    error_notice( '<p>This template file is not found:</p><p><strong>' . $_views_path . '/' . get_route( 'template' ) . '.php</strong></p>' );
  }

  unset( $_views_dir, $_views_path, $_template_file );
}
