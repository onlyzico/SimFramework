<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

class URI {
  private $request_uri_parts;
  private $clean_request_uri_parts;
  private $path;
  private $query;

  public function __construct() {
    $path = ( PATH === '' ) ? '/' : PATH;
    $request_uri = substr( $_SERVER['REQUEST_URI'], 1 );

    $this->request_uri_parts = array_map( array( $this, 'request_uri_path' ), explode( '/', $request_uri ) );
    $this->clean_request_uri_parts = sanitize_array( $this->request_uri_parts );

    $parse_uri = parse_url( '/' . ltrim( $_SERVER['REQUEST_URI'], '/' ) );
    $clean_path = strtr( $parse_uri['path'], [ PATH => '' ] );

    if ( $path === '/' ) {
      $this->path = ( $parse_uri['path'] !== '/' ) ? ltrim( $parse_uri['path'], '/' ) : '/';
    } else {
      $this->path = ( $clean_path !== '/' ) ? str_replace( PATH . '/', '', $parse_uri['path'] ) : '/';
    }

    $this->query = ( isset( $parse_uri['query'] ) ) ? $parse_uri['query'] : '';
  }

  public function handler() {
    if ( $this->path != '/' ) {
      $uri_path_parts = explode( '/', $this->path );
      $filter_uri_path = array_filter( $uri_path_parts );

      if ( count( $this->request_uri_parts ) > count( $this->clean_request_uri_parts ) ) {
        $redirect = implode( $filter_uri_path, '/' );
        $redirect.= ( ! empty( $this->query ) ) ? '?' . rtrim( $this->query, '/' ) : '';

        redirect( site_url() . '/' . $redirect, [ 'permanent' => true ] );
      }

      else {
        $uri_contain_slashes = $uri_path_parts;
        $uri_has_slashes = false;

        if ( count( $uri_contain_slashes ) > count( $filter_uri_path ) && count( $uri_contain_slashes ) > ( count( $filter_uri_path ) + 1 ) ) {
          $uri_has_slashes = true;
        } elseif ( count( $uri_contain_slashes ) > count( $filter_uri_path ) ) {
          $uri_has_slashes = true;
        }

        if ( uri_has_extension( $this->path ) )
          $uri_has_slashes = false;

        if ( $uri_has_slashes ) {
          $redirect = implode( array_filter( $this->clean_request_uri_parts ), '/' );
          $redirect.= ( ! empty( $this->query ) ) ? '?' . rtrim( $this->query ) : '';

          redirect( site_url() . '/' . $redirect, [ 'permanent' => true ] );
        }
      }
    }
  }

  public function get_path() {
    return $this->path;
  }

  public function get_query() {
    return $this->query;
  }

  private function request_uri_path( $uri ) {
    return parse_url( $uri, PHP_URL_PATH );
  }
}
