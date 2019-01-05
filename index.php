<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

/*----------------------------------------------------------------------------*/

/**
 * Timezone
 * @see http://php.net/manual/en/timezones.php
 */

date_default_timezone_set( 'Asia/Jakarta' );

/*----------------------------------------------------------------------------*/

/**
 * Main constants
 */

define( 'IS_LOCAL', ( isset( $_SERVER['HTTP_HOST'] ) && $_SERVER['HTTP_HOST'] === 'localhost' ) ? true : false );

define( 'AEO', dirname( __FILE__ ), true );
define( 'HOST', $_SERVER['HTTP_HOST'], true );
define( 'BASE', str_replace( "\\", '/', AEO ), true );
define( 'PATH', strtr( BASE, [ rtrim( $_SERVER['DOCUMENT_ROOT'], '/' ) => '' ] ), true );
define( 'APP', BASE . '/application', true );

/*----------------------------------------------------------------------------*/

/**
 * Check if directory is writable
 */

if ( ! @is_writable( BASE ) )
  die( 'Directory is not writable.<br />Please check your directory ownership!' );

/*----------------------------------------------------------------------------*/

/**
 * Loader
 */

require APP . '/core/loader.php';
