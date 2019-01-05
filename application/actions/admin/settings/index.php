<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( ! current_user_can( 'admin_settings' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

$options = $aeo_db->get_results( "SELECT * FROM options WHERE public = 1" );

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['option'] ) && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  $options = sanitize_input( $_POST['option'] );

  foreach ( $options as $key => $value )
    update_option( $key, [ 'content' => $value ] );

  create_session( 'admin_settings_success_msg', 'Pengaturan berhasil disimpan' );

  redirect( admin_url() . '/settings' );
}

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_settings_success_msg' ) ) {
  $success_msg = get_session( 'admin_settings_success_msg' );
  delete_session( 'admin_settings_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['page_title'] = $aeo['breadcrumb_title'] = 'Pengaturan';

$aeo['load_styles'][] = 'bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css';

$aeo['load_scripts'][] = 'bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js';
