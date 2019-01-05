<?php

/**
 * @copyright Dicky Syaputra
 * @author Dicky Syaputra
 */

if ( ! defined( 'AEO' ) )
  die( 'No direct script access allowed!' );

/*----------------------------------------------------------------------------*/

if ( ! is_ajax() )
  error_notice( 'No ajax request detected!' );

/*----------------------------------------------------------------------------*/

$request = $_REQUEST;

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-slideshows' && current_user_can( 'admin_slideshows' ) ) {
  if ( current_user_can( 'admin_delete_slideshow' ) ) {
    $columns = [ 1 => 'id', 3 => 'title', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'title', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, title, image, updated_datetime FROM slideshows";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR title LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_slideshow' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['image'] = ( $data['image'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/slideshows/' . $data['image'] . '" alt="" />' : '';
      $item['title'] = $data['title'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_slideshow' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/slideshows/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-pages' && current_user_can( 'admin_pages' ) ) {
  if ( current_user_can( 'admin_delete_page' ) ) {
    $columns = [ 1 => 'id', 2 => 'title', 3 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 1 => 'title', 2 => 'updated_datetime' ];
  }

  $sql = "SELECT id, title, updated_datetime FROM pages";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR title LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_page' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['title'] = $data['title'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_page' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/pages/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-news' && current_user_can( 'admin_news' ) ) {
  if ( current_user_can( 'admin_delete_news' ) ) {
    $columns = [ 1 => 'id', 3 => 'title', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'title', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, title, image, updated_datetime FROM news";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR title LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_news' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['image'] = ( $data['image'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/news/' . $data['image'] . '" alt="" />' : '';
      $item['title'] = $data['title'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_news' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/news/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-events' && current_user_can( 'admin_events' ) ) {
  if ( current_user_can( 'admin_delete_event' ) ) {
    $columns = [ 1 => 'id', 3 => 'title', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'title', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, title, image, updated_datetime FROM events";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR title LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_event' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['image'] = ( $data['image'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/events/' . $data['image'] . '" alt="" />' : '';
      $item['title'] = $data['title'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_event' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/events/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-destinations' && current_user_can( 'admin_destinations' ) ) {
  if ( current_user_can( 'admin_delete_destination' ) ) {
    $columns = [ 1 => 'id', 3 => 'name', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'name', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, name, image, updated_datetime FROM destinations";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR name LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_destination' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['image'] = ( $data['image'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/destinations/' . $data['image'] . '" alt="" />' : '';
      $item['name'] = $data['name'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_destination' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/destinations/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-destination-categories' && current_user_can( 'admin_destination_categories' ) ) {
  if ( current_user_can( 'admin_delete_destination_category' ) ) {
    $columns = [ 1 => 'id', 3 => 'name', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'name', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, name, image, updated_datetime FROM destination_categories";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR name LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_destination_category' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['image'] = ( $data['image'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/destination-categories/' . $data['image'] . '" alt="" />' : '';
      $item['name'] = $data['name'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_destination_category' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/destinations/categories/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-creative-economy' && current_user_can( 'admin_creative_economy' ) ) {
  if ( current_user_can( 'admin_delete_creative_economy' ) ) {
    $columns = [ 1 => 'id', 3 => 'name', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'name', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, name, image, updated_datetime FROM creative_economy";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR name LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_creative_economy' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['image'] = ( $data['image'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/creative-economy/' . $data['image'] . '" alt="" />' : '';
      $item['name'] = $data['name'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_creative_economy' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/creative-economy/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-creative-economy-categories' && current_user_can( 'admin_creative_economy_categories' ) ) {
  if ( current_user_can( 'admin_delete_creative_economy_category' ) ) {
    $columns = [ 1 => 'id', 3 => 'name', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'name', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, name, image, updated_datetime FROM creative_economy_categories";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR name LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_creative_economy_category' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['image'] = ( $data['image'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/creative-economy-categories/' . $data['image'] . '" alt="" />' : '';
      $item['name'] = $data['name'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_creative_economy_category' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/creative-economy/categories/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-links' && current_user_can( 'admin_links' ) ) {
  if ( current_user_can( 'admin_delete_link' ) ) {
    $columns = [ 1 => 'id', 3 => 'title', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'title', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, title, image, updated_datetime FROM links";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR title LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR url LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_link' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['image'] = ( $data['image'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/links/' . $data['image'] . '" alt="" />' : '';
      $item['title'] = $data['title'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_link' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/links/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-downloads' && current_user_can( 'admin_downloads' ) ) {
  if ( current_user_can( 'admin_delete_download' ) ) {
    $columns = [ 1 => 'id', 3 => 'title', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'title', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, title, image, updated_datetime FROM downloads";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR title LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR file_url LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_download' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['image'] = ( $data['image'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/downloads/' . $data['image'] . '" alt="" />' : '';
      $item['title'] = $data['title'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_download' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/downloads/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-galleries' && current_user_can( 'admin_galleries' ) ) {
  if ( current_user_can( 'admin_delete_gallery' ) ) {
    $columns = [ 1 => 'id', 3 => 'title', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'title', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, title, photo, updated_datetime FROM galleries";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR title LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_gallery' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['photo'] = ( $data['photo'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/galleries/' . $data['photo'] . '" alt="" />' : '';
      $item['title'] = $data['title'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_gallery' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/galleries/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-gallery-categories' && current_user_can( 'admin_gallery_categories' ) ) {
  if ( current_user_can( 'admin_delete_gallery_category' ) ) {
    $columns = [ 1 => 'id', 3 => 'name', 4 => 'updated_datetime' ];
  } else {
    $columns = [ 0 => 'id', 2 => 'name', 3 => 'updated_datetime' ];
  }

  $sql = "SELECT id, name, image, updated_datetime FROM gallery_categories";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR name LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR content LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_gallery_category' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['image'] = ( $data['image'] ) ? '<img style="width: 48px; height: 48px;" src="' . site_url() . '/uploads/gallery-categories/' . $data['image'] . '" alt="" />' : '';
      $item['name'] = $data['name'];
      $item['updated_datetime'] = ( $data['updated_datetime'] ) ? date( 'd M Y H:i:s', $data['updated_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_gallery_category' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/galleries/categories/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-messages' && current_user_can( 'admin_messages' ) ) {
  if ( current_user_can( 'admin_delete_gallery' ) ) {
    $columns = [ 1 => 'id', 2 => 'name', 3 => 'email', 4 => 'subject', 5 => 'added_datetime' ];
  } else {
    $columns = [ 0 => 'id', 1 => 'name', 2 => 'email', 3 => 'subject', 4 => 'added_datetime' ];
  }

  $sql = "SELECT id, name, email, subject, added_datetime FROM messages";
  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= " WHERE (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR name LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR email LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR subject LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR message LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      $item['checkbox'] = ( current_user_can( 'admin_delete_message' ) ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['name'] = $data['name'];
      $item['email'] = $data['email'];
      $item['subject'] = $data['subject'];
      $item['added_datetime'] = date( 'd M Y H:i:s', $data['added_datetime'] );

      if ( current_user_can( 'admin_message' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/messages/' . md5( $data['id'] ) . '">View</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-users' && current_user_can( 'admin_users' ) ) {
  $role = ( isset( $_GET['role'] ) && $_GET['role'] ) ? (int) $_GET['role'] : 0;

  if ( current_user_can( 'admin_delete_user' ) ) {
    $columns = [ 1 => 'id', 2 => 'name', 3 => 'username', 4 => 'role', 5 => 'status', 6 => 'login_datetime' ];
  } else {
    $columns = [ 0 => 'id', 1 => 'name', 2 => 'username', 3 => 'role', 4 => 'status', 5 => 'login_datetime' ];
  }

  $sql = "SELECT id, name, username, role, status, login_datetime FROM users";

  if ( $role !== 0 ) {
    $role = ( $current_user['role'] > 1 && $role === 1 ) ? -1 : $role;
    if ( $role === 2 ) {
      $sql.= " WHERE role " . ( $current_user['role'] > 1 ? "=" : "<=" ) . " '$role'";
    } else {
      $sql.= " WHERE role = '$role'";
    }
  } else {
    $sql.= ( $current_user['role'] > 1 ) ? " WHERE role > 1" : '';
  }

  $count = $aeo_db->get_count( $sql );

  if ( ! empty( $request['search']['value'] ) ) {
    $sql.= ( $current_user['tipe'] > 1 && is_null( $role ) || ! is_null( $role ) ) ? " AND" : " WHERE";
    $sql.= " (id LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR name LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR username LIKE '%" . $request['search']['value'] . "%'";
    $sql.= " OR email LIKE '%" . $request['search']['value'] . "%')";
  }

  $filtered_count = $aeo_db->get_count( $sql );

  $sql.= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . "," . $request['length'];

  $result = $aeo_db->get_results( $sql );

  if ( $result ) {
    foreach ( $result as $key => $data ) {
      if ( (int) $data['role'] === 2 ) {
        $role_label = '<span class="label label-primary">Admin</span>';
      } else {
        $role_label = '<span class="label label-success">Admin</span>';
      }

      if ( (int) $data['status'] === 2 ) {
        $status_label = '<span class="label label-danger">Tidak Aktif</span>';
      } else {
        $status_label = '<span class="label label-success">Aktif</span>';
      }

      $item['checkbox'] = ( current_user_can( 'admin_delete_user' ) && $current_user['id'] != $data['id'] ) ? '<input type="checkbox" class="ids" value="' . $data['id'] . '" name="ids[]" id="id-' . $data['id'] . '" />' : '';
      $item['id'] = '<strong>' . $data['id'] . '</strong>';
      $item['name'] = $data['name'];
      $item['username'] = $data['username'];
      $item['role'] = $role_label;
      $item['status'] = $status_label;
      $item['login_datetime'] = ( $data['login_datetime'] ) ? date( 'd M Y H:i:s', $data['login_datetime'] ) : '-';

      if ( current_user_can( 'admin_edit_user' ) )
        $item['actions'] = '<a class="btn btn-sm btn-primary" href="' . admin_url() . '/users/' . md5( $data['id'] ) . '/edit">Edit</a>';

      $items[$key] = $item;
    }
  } else {
    $items = [];
  }

  $json_data = [
    "draw"            => intval( $request['draw'] ),
    "recordsTotal"    => intval( $count ),
    "recordsFiltered" => intval( $filtered_count ),
    "data"            => $items
  ];

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-news-fs' ) {
  $q = ( isset( $_GET['q'] ) && $_GET['q'] ) ? trim( strip_tags( $_GET['q'] ) ) : '';

  $json_data = [];

  if ( $q ) {
    $news = $aeo_db->get_results( "SELECT id,title FROM news WHERE id LIKE '%$q%' OR title LIKE '%$q%' LIMIT 10" );
    if ( $news ) {
      foreach ( $news as $item ) {
        $json_data[] = [
          'id' => $item['id'],
          'text' => $item['title']
        ];
      }
    }
  } else {
    $json_data[] = [
      'id' => 0,
      'text' => 'Tanpa Berita'
    ];
  }

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-destination-categories-fs' ) {
  $q = ( isset( $_GET['q'] ) && $_GET['q'] ) ? trim( strip_tags( $_GET['q'] ) ) : '';

  $json_data = [];

  $categories = $aeo_db->get_results( "SELECT id,name FROM destination_categories WHERE id LIKE '%$q%' OR name LIKE '%$q%' LIMIT 10" );

  if ( $categories ) {
    foreach ( $categories as $item ) {
      $json_data[] = [
        'id' => $item['id'],
        'text' => $item['name']
      ];
    }
  }

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-creative-economy-categories-fs' ) {
  $q = ( isset( $_GET['q'] ) && $_GET['q'] ) ? trim( strip_tags( $_GET['q'] ) ) : '';

  $json_data = [];

  $categories = $aeo_db->get_results( "SELECT id,name FROM creative_economy_categories WHERE id LIKE '%$q%' OR name LIKE '%$q%' LIMIT 10" );

  if ( $categories ) {
    foreach ( $categories as $item ) {
      $json_data[] = [
        'id' => $item['id'],
        'text' => $item['name']
      ];
    }
  }

  echo json_encode( $json_data );
  die();
}

/*----------------------------------------------------------------------------*/

if ( get_route_vars( 0 ) === 'load-gallery-categories-fs' ) {
  $q = ( isset( $_GET['q'] ) && $_GET['q'] ) ? trim( strip_tags( $_GET['q'] ) ) : '';

  $json_data = [];

  $categories = $aeo_db->get_results( "SELECT id,name FROM gallery_categories WHERE id LIKE '%$q%' OR name LIKE '%$q%' LIMIT 10" );

  if ( $categories ) {
    foreach ( $categories as $item ) {
      $json_data[] = [
        'id' => $item['id'],
        'text' => $item['name']
      ];
    }
  }

  echo json_encode( $json_data );
  die();
}
