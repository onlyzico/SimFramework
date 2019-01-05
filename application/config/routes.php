<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

return [

  /**
   * Public Routes
   */

  'index' => [
    'uri'       => '/',
    'actions'   => [ 'global', 'index' ],
    'template'  => 'index'
  ],

  /**
   * API Routes
   */

  'api_home' => [
    'uri'       => 'api/home',
    'section'   => 'api',
    'actions'   => [ 'global', 'home' ]
  ],
  'api_profile' => [
    'uri'       => 'api/profile',
    'section'   => 'api',
    'actions'   => [ 'global', 'profile' ]
  ],
  'api_news' => [
    'uri'       => 'api/news',
    'section'   => 'api',
    'actions'   => [ 'global', 'news' ]
  ],
  'api_events' => [
    'uri'       => 'api/events',
    'section'   => 'api',
    'actions'   => [ 'global', 'events' ]
  ],
  'api_destination_categories' => [
    'uri'       => 'api/destination-categories',
    'section'   => 'api',
    'actions'   => [ 'global', 'destination-categories' ]
  ],
  'api_destinations' => [
    'uri'       => 'api/destinations',
    'section'   => 'api',
    'actions'   => [ 'global', 'destinations' ]
  ],
  'api_creative_economy_categories' => [
    'uri'       => 'api/creative-economy-categories',
    'section'   => 'api',
    'actions'   => [ 'global', 'creative-economy-categories' ]
  ],
  'api_creative_economy' => [
    'uri'       => 'api/creative-economy',
    'section'   => 'api',
    'actions'   => [ 'global', 'creative-economy' ]
  ],
  'api_links' => [
    'uri'       => 'api/links',
    'section'   => 'api',
    'actions'   => [ 'global', 'links' ]
  ],
  'api_downloads' => [
    'uri'       => 'api/downloads',
    'section'   => 'api',
    'actions'   => [ 'global', 'downloads' ]
  ],
  'api_gallery_categories' => [
    'uri'       => 'api/gallery-categories',
    'section'   => 'api',
    'actions'   => [ 'global', 'gallery-categories' ]
  ],
  'api_galleries' => [
    'uri'       => 'api/galleries',
    'section'   => 'api',
    'actions'   => [ 'global', 'galleries' ]
  ],
  'api_contact' => [
    'uri'       => 'api/contact',
    'section'   => 'api',
    'actions'   => [ 'global', 'contact' ]
  ],
  'api_send_message' => [
    'uri'       => 'api/send-message',
    'section'   => 'api',
    'actions'   => [ 'global', 'send-message' ]
  ],

  /**
   * Admin Routes
   */

  'admin_index' => [
    'uri'       => $aeo_config['options']['admin_slug'],
    'section'   => 'admin',
    'actions'   => [ 'global', 'index' ],
    'template'  => 'index'
  ],
  'admin_slideshows' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/slideshows',
    'section'   => 'admin',
    'actions'   => [ 'global', 'slideshows/index' ],
    'template'  => 'slideshows/index'
  ],
  'admin_add_slideshow' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/slideshows/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'slideshows/edit' ],
    'template'  => 'slideshows/edit'
  ],
  'admin_edit_slideshow' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/slideshows/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'slideshows/edit' ],
    'template'  => 'slideshows/edit'
  ],
  'admin_pages' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/pages',
    'section'   => 'admin',
    'actions'   => [ 'global', 'pages/index' ],
    'template'  => 'pages/index'
  ],
  'admin_add_page' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/pages/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'pages/edit' ],
    'template'  => 'pages/edit'
  ],
  'admin_edit_page' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/pages/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'pages/edit' ],
    'template'  => 'pages/edit'
  ],
  'admin_news' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/news',
    'section'   => 'admin',
    'actions'   => [ 'global', 'news/index' ],
    'template'  => 'news/index'
  ],
  'admin_add_news' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/news/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'news/edit' ],
    'template'  => 'news/edit'
  ],
  'admin_edit_news' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/news/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'news/edit' ],
    'template'  => 'news/edit'
  ],
  'admin_events' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/events',
    'section'   => 'admin',
    'actions'   => [ 'global', 'events/index' ],
    'template'  => 'events/index'
  ],
  'admin_add_event' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/events/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'events/edit' ],
    'template'  => 'events/edit'
  ],
  'admin_edit_event' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/events/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'events/edit' ],
    'template'  => 'events/edit'
  ],
  'admin_destinations' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/destinations',
    'section'   => 'admin',
    'actions'   => [ 'global', 'destinations/index' ],
    'template'  => 'destinations/index'
  ],
  'admin_add_destination' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/destinations/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'destinations/edit' ],
    'template'  => 'destinations/edit'
  ],
  'admin_edit_destination' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/destinations/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'destinations/edit' ],
    'template'  => 'destinations/edit'
  ],
  'admin_destination_categories' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/destinations/categories',
    'section'   => 'admin',
    'actions'   => [ 'global', 'destination-categories/index' ],
    'template'  => 'destination-categories/index'
  ],
  'admin_add_destination_category' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/destinations/categories/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'destination-categories/edit' ],
    'template'  => 'destination-categories/edit'
  ],
  'admin_edit_destination_category' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/destinations/categories/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'destination-categories/edit' ],
    'template'  => 'destination-categories/edit'
  ],
  'admin_creative_economy' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/creative-economy',
    'section'   => 'admin',
    'actions'   => [ 'global', 'creative-economy/index' ],
    'template'  => 'creative-economy/index'
  ],
  'admin_add_creative_economy' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/creative-economy/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'creative-economy/edit' ],
    'template'  => 'creative-economy/edit'
  ],
  'admin_edit_creative_economy' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/creative-economy/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'creative-economy/edit' ],
    'template'  => 'creative-economy/edit'
  ],
  'admin_creative_economy_categories' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/creative-economy/categories',
    'section'   => 'admin',
    'actions'   => [ 'global', 'creative-economy-categories/index' ],
    'template'  => 'creative-economy-categories/index'
  ],
  'admin_add_creative_economy_category' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/creative-economy/categories/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'creative-economy-categories/edit' ],
    'template'  => 'creative-economy-categories/edit'
  ],
  'admin_edit_creative_economy_category' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/creative-economy/categories/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'creative-economy-categories/edit' ],
    'template'  => 'creative-economy-categories/edit'
  ],
  'admin_links' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/links',
    'section'   => 'admin',
    'actions'   => [ 'global', 'links/index' ],
    'template'  => 'links/index'
  ],
  'admin_add_link' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/links/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'links/edit' ],
    'template'  => 'links/edit'
  ],
  'admin_edit_link' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/links/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'links/edit' ],
    'template'  => 'links/edit'
  ],
  'admin_downloads' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/downloads',
    'section'   => 'admin',
    'actions'   => [ 'global', 'downloads/index' ],
    'template'  => 'downloads/index'
  ],
  'admin_add_download' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/downloads/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'downloads/edit' ],
    'template'  => 'downloads/edit'
  ],
  'admin_edit_download' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/downloads/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'downloads/edit' ],
    'template'  => 'downloads/edit'
  ],
  'admin_galleries' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/galleries',
    'section'   => 'admin',
    'actions'   => [ 'global', 'galleries/index' ],
    'template'  => 'galleries/index'
  ],
  'admin_add_gallery' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/galleries/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'galleries/edit' ],
    'template'  => 'galleries/edit'
  ],
  'admin_edit_gallery' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/galleries/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'galleries/edit' ],
    'template'  => 'galleries/edit'
  ],
  'admin_gallery_categories' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/galleries/categories',
    'section'   => 'admin',
    'actions'   => [ 'global', 'gallery-categories/index' ],
    'template'  => 'gallery-categories/index'
  ],
  'admin_add_gallery_category' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/galleries/categories/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'gallery-categories/edit' ],
    'template'  => 'gallery-categories/edit'
  ],
  'admin_edit_gallery_category' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/galleries/categories/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'gallery-categories/edit' ],
    'template'  => 'gallery-categories/edit'
  ],
  'admin_messages' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/messages',
    'section'   => 'admin',
    'actions'   => [ 'global', 'messages/index' ],
    'template'  => 'messages/index'
  ],
  'admin_message' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/messages/([a-z-0-9]+)',
    'section'   => 'admin',
    'actions'   => [ 'global', 'messages/detail' ],
    'template'  => 'messages/detail'
  ],
  'admin_users' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/users',
    'section'   => 'admin',
    'actions'   => [ 'global', 'users/index' ],
    'template'  => 'users/index'
  ],
  'admin_add_user' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/users/add',
    'section'   => 'admin',
    'actions'   => [ 'global', 'users/edit' ],
    'template'  => 'users/edit'
  ],
  'admin_edit_user' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/users/([a-z-0-9]+)/edit',
    'section'   => 'admin',
    'actions'   => [ 'global', 'users/edit' ],
    'template'  => 'users/edit'
  ],
  'admin_settings' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/settings',
    'section'   => 'admin',
    'actions'   => [ 'global', 'settings/index' ],
    'template'  => 'settings/index'
  ],
  'admin_profile' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/profile',
    'section'   => 'admin',
    'actions'   => [ 'global', 'profile' ],
    'template'  => 'profile'
  ],
  'admin_ajax' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/ajax/([a-z-0-9]+)',
    'section'   => 'admin',
    'actions'   => [ 'global', 'ajax' ]
  ],
  'admin_login' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/login',
    'section'   => 'admin',
    'actions'   => [ 'auth/global', 'auth/login' ],
    'template'  => 'auth/login'
  ],
  'admin_reset_password' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/password/reset',
    'section'   => 'admin',
    'actions'   => [ 'auth/global', 'auth/reset-password' ],
    'template'  => 'auth/reset-password'
  ],
  'admin_create_password' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/password/create',
    'section'   => 'admin',
    'actions'   => [ 'auth/global', 'auth/create-password' ],
    'template'  => 'auth/create-password'
  ],
  'admin_logout' => [
    'uri'       => $aeo_config['options']['admin_slug'] . '/logout',
    'section'   => 'admin',
    'actions'   => [ 'auth/logout' ]
  ],
];
