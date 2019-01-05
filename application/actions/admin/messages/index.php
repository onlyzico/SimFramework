<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( ! current_user_can( 'admin_messages' ) )
  error_notice( 'You don\'t have permissions to accessing this page' );

/*----------------------------------------------------------------------------*/

if ( isset( $_POST['action'] ) && $_POST['action'] && isset( $_POST['csrf_token'] ) && $_POST['csrf_token'] == csrf_token() ) {
  if ( $_POST['action'] == 'delete_selected' && isset( $_POST['ids'] ) && $_POST['ids'] ) {
    foreach ( $_POST['ids'] as $id )
      $aeo_db->delete( 'messages', [ 'id' => $id ] );

    create_session( 'admin_messages_success_msg', count( $ids ) . ' Pesan berhasil dihapus' );
  }

  redirect( admin_url() . '/messages' );
}

/*----------------------------------------------------------------------------*/

if ( get_session( 'admin_messages_success_msg' ) ) {
  $success_msg = get_session( 'admin_messages_success_msg' );
  delete_session( 'admin_messages_success_msg' );
}

/*----------------------------------------------------------------------------*/

$aeo['site_title'].= $aeo['breadcrumb_title'] = $aeo['page_title'] = 'Pesan';

$aeo['load_styles'][] = 'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/css/dataTables.bootstrap.min.css';

$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js';
$aeo['load_scripts'][] = 'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/dataTables.bootstrap.min.js';

$aeo['localize_script']['datatable_data_name'] = 'pesan';
$aeo['localize_script']['datatables_load_slug'] = 'load-messages';
$aeo['localize_script']['datatables_columns'] = [
  [ 'data' => 'checkbox', 'className' => 'text-center', 'orderable' => false ],
  [ 'data' => 'id' ],
  [ 'data' => 'name' ],
  [ 'data' => 'subject' ],
  [ 'data' => 'email' ],
  [ 'data' => 'added_datetime' ],
  [ 'data' => 'actions', 'className' => 'text-center', 'orderable' => false ],
];
$aeo['localize_script']['datatables_order_index'] = 1;
$aeo['localize_script']['datatables_sort'] = 'desc';

if ( ! current_user_can( 'admin_delete_message' ) ) {
  unset( $aeo['localize_script']['datatables_columns'][0] );
  $aeo['localize_script']['datatables_order_index'] = 0;
  $aeo['localize_script']['datatables_columns'] = array_values( $aeo['localize_script']['datatables_columns'] );
}

if ( ! current_user_can( 'admin_message' ) ) {
  array_pop( $aeo['localize_script']['datatables_columns'] );
  $aeo['localize_script']['datatables_columns'] = array_values( $aeo['localize_script']['datatables_columns'] );
}
