<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

function admin_url() {
  global $aeo_config;
  return site_url() . '/' . $aeo_config['options']['admin_slug'];
}

function admin_create_delete_modal( $options ) {
  $defaults = [
    'title'   => '',
    'body'    => '',
    'button'  => 'Lanjutkan'
  ];
  $options = array_merge( $defaults, $options );

  $modal = '<div class="modal fade" id="delete-modal">';
  $modal.= '<div class="modal-dialog">';
  $modal.= '<div class="modal-content">';
  $modal.= '<div class="modal-header">';
  $modal.= '<button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>';
  $modal.= '<h4 class="modal-title">' . $options['title'] . '</h4>';
  $modal.= '</div>';
  $modal.= '<div class="modal-body"><div class="alert alert-danger mb-0">' . $options['body'] . '</div></div>';
  $modal.= '<div class="modal-footer">';
  $modal.= '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>';
  $modal.= '<button type="button" class="btn btn-primary continue">' . $options['button'] . '</button>';
  $modal.= '</div>';
  $modal.= '</div>';
  $modal.= '</div>';
  $modal.= '</div>';

  return $modal;
}
