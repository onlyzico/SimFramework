<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

class Router {
  private $routes;
  private $route = [];
  private $found = false;

  public function __construct() {
    global $aeo_config;
    $this->routes = $aeo_config['routes'];
  }

  public function dispatch() {
    global $aeo_config, $aeo_uri, $aeo_found;

    foreach ( $this->routes as $name => $options ) {
      $pattern = '/^' . str_replace( '/', '\/', $options['uri'] ) . '$/';

      if ( preg_match( $pattern, $aeo_uri->get_path(), $vars ) ) {
        array_shift( $vars );

        $this->route['section'] = ( isset( $options['section'] ) && $options['section'] ) ? $options['section'] : $aeo_config['options']['default_section'];
        $this->route['name'] = $name;
        $this->route['vars'] = $vars;
        $this->route['actions'] = ( isset( $options['actions'] ) && $options['actions'] ) ? $options['actions'] : false;
        $this->route['template'] = ( isset( $options['template'] ) && $options['template'] ) ? $options['template'] : false;

        $this->found = true;

        break;
      }
    }

    if ( ! $this->found )
      $this->set_404();
  }

  public function is_found() {
    return $this->found;
  }

  public function set_found( $bool ) {
    $this->found = $bool;
  }

  public function set_404() {
    global $aeo_config;

    header( 'HTTP/1.0 404 Not Found' );

    $this->route['section']  = $aeo_config['options']['default_section'];
    $this->route['name']     = '404';
    $this->route['vars']     = [];
    $this->route['actions']  = [ $aeo_config['options']['default_action'], '404' ];
    $this->route['template'] = '404';
  }

  public function active() {
    return $this->route;
  }
}
