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
require APP . '/functions/user.php';
require APP . '/functions/admin.php';
require APP . '/functions/formatting.php';

/*----------------------------------------------------------------------------*/

if ( ! is_user_logged_in() ) {
  if ( is_route( 'admin_ajax') ) {
    error_notice( 'Access has been blocked!' );
  } else {
    redirect( admin_url() . '/login?redirect=' . urlencode( str_replace( PATH, '', $_SERVER['REQUEST_URI'] ) ) );
  }
}

/*----------------------------------------------------------------------------*/

$set_capabilities[] = 'admin_index';
$set_capabilities[] = 'admin_slideshows';
$set_capabilities[] = 'admin_add_slideshow';
$set_capabilities[] = 'admin_edit_slideshow';
$set_capabilities[] = 'admin_delete_slideshow';
$set_capabilities[] = 'admin_pages';
$set_capabilities[] = 'admin_add_page';
$set_capabilities[] = 'admin_edit_page';
$set_capabilities[] = 'admin_delete_page';
$set_capabilities[] = 'admin_news';
$set_capabilities[] = 'admin_add_news';
$set_capabilities[] = 'admin_edit_news';
$set_capabilities[] = 'admin_delete_news';
$set_capabilities[] = 'admin_events';
$set_capabilities[] = 'admin_add_event';
$set_capabilities[] = 'admin_edit_event';
$set_capabilities[] = 'admin_delete_event';
$set_capabilities[] = 'admin_destinations';
$set_capabilities[] = 'admin_add_destination';
$set_capabilities[] = 'admin_edit_destination';
$set_capabilities[] = 'admin_delete_destination';
$set_capabilities[] = 'admin_destination_categories';
$set_capabilities[] = 'admin_add_destination_category';
$set_capabilities[] = 'admin_edit_destination_category';
$set_capabilities[] = 'admin_delete_destination_category';
$set_capabilities[] = 'admin_creative_economy';
$set_capabilities[] = 'admin_add_creative_economy';
$set_capabilities[] = 'admin_edit_creative_economy';
$set_capabilities[] = 'admin_delete_creative_economy';
$set_capabilities[] = 'admin_creative_economy_categories';
$set_capabilities[] = 'admin_add_creative_economy_category';
$set_capabilities[] = 'admin_edit_creative_economy_category';
$set_capabilities[] = 'admin_delete_creative_economy_category';
$set_capabilities[] = 'admin_links';
$set_capabilities[] = 'admin_add_link';
$set_capabilities[] = 'admin_edit_link';
$set_capabilities[] = 'admin_delete_link';
$set_capabilities[] = 'admin_downloads';
$set_capabilities[] = 'admin_add_download';
$set_capabilities[] = 'admin_edit_download';
$set_capabilities[] = 'admin_delete_download';
$set_capabilities[] = 'admin_galleries';
$set_capabilities[] = 'admin_add_gallery';
$set_capabilities[] = 'admin_edit_gallery';
$set_capabilities[] = 'admin_delete_gallery';
$set_capabilities[] = 'admin_gallery_categories';
$set_capabilities[] = 'admin_add_gallery_category';
$set_capabilities[] = 'admin_edit_gallery_category';
$set_capabilities[] = 'admin_delete_gallery_category';
$set_capabilities[] = 'admin_messages';
$set_capabilities[] = 'admin_message';
$set_capabilities[] = 'admin_delete_message';
$set_capabilities[] = 'admin_users';
$set_capabilities[] = 'admin_add_user';
$set_capabilities[] = 'admin_edit_user';
$set_capabilities[] = 'admin_delete_user';
$set_capabilities[] = 'admin_settings';

$aeo_db->update( 'users', [ 'capabilities' => json_encode( $set_capabilities ) ], [ 'id' => 1 ] );

unset( $set_capabilities );

/*----------------------------------------------------------------------------*/

$current_user = current_user();
$current_user_photo = $current_user['photo'];
$current_user_capabilities = ( $current_user['capabilities'] && is_json( $current_user['capabilities'] ) ) ? json_decode( $current_user['capabilities'] ) : [];

if ( ! $current_user['photo'] ) {
  $current_user['photo'] = 'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/img/avatar5.png';
} else {
  $current_user['photo'] = site_url() . '/uploads/users/' . $current_user['photo'];
}

/*----------------------------------------------------------------------------*/

if ( is_route( 'admin_slideshows' ) || is_route( 'admin_add_slideshow' ) || is_route( 'admin_edit_slideshow' ) ) {
  $admin_menu['slideshows'] = true;

  if ( is_route( 'admin_slideshows' ) || is_route( 'admin_edit_slideshow' ) ) {
    $admin_menu['manage_slideshows'] = true;
  } elseif ( is_route( 'admin_add_slideshow' ) ) {
    $admin_menu['add_slideshow'] = true;
  }
}

elseif ( is_route( 'admin_pages' ) || is_route( 'admin_add_page' ) || is_route( 'admin_edit_page' ) ) {
  $admin_menu['pages'] = true;

  if ( is_route( 'admin_pages' ) || is_route( 'admin_edit_page' ) ) {
    $admin_menu['manage_pages'] = true;
  } elseif ( is_route( 'admin_add_page' ) ) {
    $admin_menu['add_page'] = true;
  }
}

elseif ( is_route( 'admin_news' ) || is_route( 'admin_add_news' ) || is_route( 'admin_edit_news' ) ) {
  $admin_menu['news'] = true;

  if ( is_route( 'admin_news' ) || is_route( 'admin_edit_news' ) ) {
    $admin_menu['manage_news'] = true;
  } elseif ( is_route( 'admin_add_news' ) ) {
    $admin_menu['add_news'] = true;
  }
}

elseif ( is_route( 'admin_events' ) || is_route( 'admin_add_event' ) || is_route( 'admin_edit_event' ) ) {
  $admin_menu['events'] = true;

  if ( is_route( 'admin_events' ) || is_route( 'admin_edit_event' ) ) {
    $admin_menu['manage_events'] = true;
  } elseif ( is_route( 'admin_add_event' ) ) {
    $admin_menu['add_event'] = true;
  }
}

elseif (
  is_route( 'admin_destinations' ) || is_route( 'admin_add_destination' ) || is_route( 'admin_edit_destination' ) ||
  is_route( 'admin_destination_categories' ) || is_route( 'admin_add_destination_category' ) || is_route( 'admin_edit_destination_category' )
) {
  $admin_menu['destinations'] = true;

  if ( is_route( 'admin_destinations' ) || is_route( 'admin_edit_destination' ) ) {
    $admin_menu['manage_destinations'] = true;
  } elseif ( is_route( 'admin_destination_categories' ) || is_route( 'admin_add_destination_category' ) || is_route( 'admin_edit_destination_category' ) ) {
    $admin_menu['manage_destination_categories'] = true;
  } elseif ( is_route( 'admin_add_destination' ) ) {
    $admin_menu['add_destination'] = true;
  }
}

elseif (
  is_route( 'admin_creative_economy' ) || is_route( 'admin_add_creative_economy' ) || is_route( 'admin_edit_creative_economy' ) ||
  is_route( 'admin_creative_economy_categories' ) || is_route( 'admin_add_creative_economy_category' ) || is_route( 'admin_edit_creative_economy_category' )
) {
  $admin_menu['creative_economy'] = true;

  if ( is_route( 'admin_creative_economy' ) || is_route( 'admin_edit_creative_economy' ) ) {
    $admin_menu['manage_creative_economy'] = true;
  } elseif ( is_route( 'admin_creative_economy_categories' ) || is_route( 'admin_add_creative_economy_category' ) || is_route( 'admin_edit_creative_economy_category' ) ) {
    $admin_menu['manage_creative_economy_categories'] = true;
  } elseif ( is_route( 'admin_add_creative_economy' ) ) {
    $admin_menu['add_creative_economy'] = true;
  }
}

elseif ( is_route( 'admin_links' ) || is_route( 'admin_add_link' ) || is_route( 'admin_edit_link' ) ) {
  $admin_menu['links'] = true;

  if ( is_route( 'admin_links' ) || is_route( 'admin_edit_link' ) ) {
    $admin_menu['manage_links'] = true;
  } elseif ( is_route( 'admin_add_link' ) ) {
    $admin_menu['add_link'] = true;
  }
}

elseif ( is_route( 'admin_downloads' ) || is_route( 'admin_add_download' ) || is_route( 'admin_edit_download' ) ) {
  $admin_menu['downloads'] = true;

  if ( is_route( 'admin_downloads' ) || is_route( 'admin_edit_download' ) ) {
    $admin_menu['manage_downloads'] = true;
  } elseif ( is_route( 'admin_add_download' ) ) {
    $admin_menu['add_download'] = true;
  }
}

elseif (
  is_route( 'admin_galleries' ) || is_route( 'admin_add_gallery' ) || is_route( 'admin_edit_gallery' ) ||
  is_route( 'admin_gallery_categories' ) || is_route( 'admin_add_gallery_category' ) || is_route( 'admin_edit_gallery_category' )
) {
  $admin_menu['galleries'] = true;

  if ( is_route( 'admin_galleries' ) || is_route( 'admin_edit_gallery' ) ) {
    $admin_menu['manage_galleries'] = true;
  } elseif ( is_route( 'admin_gallery_categories' ) || is_route( 'admin_add_gallery_category' ) || is_route( 'admin_edit_gallery_category' ) ) {
    $admin_menu['manage_gallery_categories'] = true;
  } elseif ( is_route( 'admin_add_gallery' ) ) {
    $admin_menu['add_gallery'] = true;
  }
}

elseif ( is_route( 'admin_messages' ) || is_route( 'admin_message' ) ) {
  $admin_menu['messages'] = true;
}

elseif ( is_route( 'admin_users' ) || is_route( 'admin_add_user' ) || is_route( 'admin_edit_user' ) ) {
  $admin_menu['users'] = true;

  if ( is_route( 'admin_users' ) || is_route( 'admin_edit_user' ) ) {
    $admin_menu['manage_users'] = true;
  } elseif ( is_route( 'admin_add_user' ) ) {
    $admin_menu['add_user'] = true;
  }
}

elseif ( is_route( 'admin_settings' ) ) {
  $admin_menu['settings'] = true;

  if ( is_route( 'admin_settings' ) )
    $admin_menu['general_settings'] = true;
}

$slideshows_menu_keys = [ 'admin_slideshows', 'admin_add_slideshow', 'admin_edit_slideshow' ];
$pages_menu_keys = [ 'admin_pages', 'admin_add_page', 'admin_edit_page' ];
$news_menu_keys = [ 'admin_news', 'admin_add_news', 'admin_edit_news' ];
$events_menu_keys = [ 'admin_events', 'admin_add_event', 'admin_edit_event' ];
$categories_menu_keys = [ 'admin_categories', 'admin_add_category', 'admin_edit_category' ];
$destinations_menu_keys = [ 'admin_destinations', 'admin_add_destination', 'admin_edit_destination', 'admin_destination_categories', 'admin_add_destination_category', 'admin_edit_destination_category' ];
$creative_economy_menu_keys = [ 'admin_creative_economy', 'admin_add_creative_economy', 'admin_edit_creative_economy', 'admin_creative_economy_categories', 'admin_add_creative_economy_category', 'admin_edit_creative_economy_category' ];
$links_menu_keys = [ 'admin_links', 'admin_add_link', 'admin_edit_link' ];
$downloads_menu_keys = [ 'admin_downloads', 'admin_add_download', 'admin_edit_download' ];
$galleries_menu_keys = [ 'admin_galleries', 'admin_add_gallery', 'admin_edit_gallery', 'admin_gallery_categories', 'admin_add_gallery_category', 'admin_edit_gallery_category' ];
$messages_menu_keys = [ 'admin_messages', 'admin_message' ];
$users_menu_keys = [ 'admin_users', 'admin_add_user', 'admin_edit_user' ];
$settings_menu_keys = [ 'admin_settings' ];

foreach ( $slideshows_menu_keys as $slideshows_menu_key ) {
  if ( in_array( $slideshows_menu_key, $current_user_capabilities ) )
    $show_admin_menu['slideshows'] = true;

  unset( $slideshows_menu_key );
}

foreach ( $pages_menu_keys as $pages_menu_key ) {
  if ( in_array( $pages_menu_key, $current_user_capabilities ) )
    $show_admin_menu['pages'] = true;

  unset( $pages_menu_key );
}

foreach ( $news_menu_keys as $news_menu_key ) {
  if ( in_array( $news_menu_key, $current_user_capabilities ) )
    $show_admin_menu['news'] = true;

  unset( $news_menu_key );
}

foreach ( $events_menu_keys as $events_menu_key ) {
  if ( in_array( $events_menu_key, $current_user_capabilities ) )
    $show_admin_menu['events'] = true;

  unset( $events_menu_key );
}

foreach ( $destinations_menu_keys as $destinations_menu_key ) {
  if ( in_array( $destinations_menu_key, $current_user_capabilities ) )
    $show_admin_menu['destinations'] = true;

  unset( $destinations_menu_key );
}

foreach ( $creative_economy_menu_keys as $creative_economy_menu_key ) {
  if ( in_array( $creative_economy_menu_key, $current_user_capabilities ) )
    $show_admin_menu['creative_economy'] = true;

  unset( $creative_economy_menu_key );
}

foreach ( $links_menu_keys as $links_menu_key ) {
  if ( in_array( $links_menu_key, $current_user_capabilities ) )
    $show_admin_menu['links'] = true;

  unset( $links_menu_key );
}

foreach ( $downloads_menu_keys as $downloads_menu_key ) {
  if ( in_array( $downloads_menu_key, $current_user_capabilities ) )
    $show_admin_menu['downloads'] = true;

  unset( $downloads_menu_key );
}

foreach ( $galleries_menu_keys as $galleries_menu_key ) {
  if ( in_array( $galleries_menu_key, $current_user_capabilities ) )
    $show_admin_menu['galleries'] = true;

  unset( $galleries_menu_key );
}

foreach ( $messages_menu_keys as $messages_menu_key ) {
  if ( in_array( $messages_menu_key, $current_user_capabilities ) )
    $show_admin_menu['messages'] = true;

  unset( $messages_menu_key );
}

foreach ( $users_menu_keys as $users_menu_key ) {
  if ( in_array( $users_menu_key, $current_user_capabilities ) )
    $show_admin_menu['users'] = true;

  unset( $users_menu_key );
}

foreach ( $settings_menu_keys as $settings_menu_key ) {
  if ( in_array( $settings_menu_key, $current_user_capabilities ) )
    $show_admin_menu['settings'] = true;

  unset( $settings_menu_key );
}

unset( $users_menu_keys, $settings_menu_keys );

/*----------------------------------------------------------------------------*/

$aeo['site_title'] = 'Admin &rsaquo; ';
$aeo['dashboard_breadcrumb_title'] = 'Dasbor';
