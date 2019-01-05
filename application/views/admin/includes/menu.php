<ul class="sidebar-menu" data-widget="tree">
  <li class="header">NAVIGATION</li>

  <li<?php echo ( is_route( 'admin_index' ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url(); ?>"><i class="fa fa-dashboard"></i>&nbsp;&nbsp;<span>Dasbor</span></a></li>

  <?php if ( isset( $show_admin_menu['slideshows'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['slideshows'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/slideshows'; ?>">
        <i class="fa fa-image"></i>&nbsp;&nbsp;<span>Slideshow</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_slideshows' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_slideshows'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/slideshows'; ?>">Semua Slideshow</a></li>
        <?php } if ( current_user_can( 'admin_add_slideshow' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['add_slideshow'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/slideshows/add'; ?>">Tambah Baru</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['pages'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['pages'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/pages'; ?>">
        <i class="fa fa-file"></i>&nbsp;&nbsp;<span>Halaman</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_pages' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_pages'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/pages'; ?>">Semua Halaman</a></li>
        <?php } if ( current_user_can( 'admin_add_page' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['add_page'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/pages/add'; ?>">Tambah Baru</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['news'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['news'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/news'; ?>">
        <i class="fa fa-newspaper-o"></i>&nbsp;&nbsp;<span>Berita</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_news' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_news'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/news'; ?>">Semua Berita</a></li>
        <?php } if ( current_user_can( 'admin_add_news' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['add_news'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/news/add'; ?>">Tambah Baru</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['events'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['events'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/events'; ?>">
        <i class="fa fa-calendar"></i>&nbsp;&nbsp;<span>Event</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_events' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_events'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/events'; ?>">Semua Event</a></li>
        <?php } if ( current_user_can( 'admin_add_event' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['add_event'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/events/add'; ?>">Tambah Baru</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['destinations'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['destinations'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/destinations'; ?>">
        <i class="fa fa-map"></i>&nbsp;&nbsp;<span>Destinasi</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_destinations' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_destinations'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/destinations'; ?>">Semua Destinasi</a></li>
        <?php } if ( current_user_can( 'admin_add_destination' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['add_destination'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/destinations/add'; ?>">Tambah Baru</a></li>
        <?php } if ( current_user_can( 'admin_destination_categories' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_destination_categories'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/destinations/categories'; ?>">Kategori</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['creative_economy'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['creative_economy'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/creative-economy'; ?>">
        <i class="fa fa-lightbulb-o"></i>&nbsp;&nbsp;<span>Ekraf</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_creative_economy' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_creative_economy'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/creative-economy'; ?>">Semua Ekraf</a></li>
        <?php } if ( current_user_can( 'admin_add_creative_economy' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['add_creative_economy'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/creative-economy/add'; ?>">Tambah Baru</a></li>
        <?php } if ( current_user_can( 'admin_creative_economy_categories' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_creative_economy_categories'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/creative-economy/categories'; ?>">Kategori</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['links'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['links'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/links'; ?>">
        <i class="fa fa-link"></i>&nbsp;&nbsp;<span>Tautan</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_links' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_links'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/links'; ?>">Semua Tautan</a></li>
        <?php } if ( current_user_can( 'admin_add_link' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['add_link'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/links/add'; ?>">Tambah Baru</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['downloads'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['downloads'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/downloads'; ?>">
        <i class="fa fa-download"></i>&nbsp;&nbsp;<span>Download</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_downloads' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_downloads'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/downloads'; ?>">Semua Download</a></li>
        <?php } if ( current_user_can( 'admin_add_download' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['add_download'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/downloads/add'; ?>">Tambah Baru</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['galleries'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['galleries'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/galleries'; ?>">
        <i class="fa fa-image"></i>&nbsp;&nbsp;<span>Galeri</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_galleries' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_galleries'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/galleries'; ?>">Semua Galeri</a></li>
        <?php } if ( current_user_can( 'admin_add_gallery' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['add_gallery'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/galleries/add'; ?>">Tambah Baru</a></li>
        <?php } if ( current_user_can( 'admin_gallery_categories' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_gallery_categories'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/galleries/categories'; ?>">Kategori</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['messages'] ) ) { ?>
    <li class="<?php echo ( isset( $admin_menu['messages'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/messages'; ?>">
        <i class="fa fa-envelope"></i>&nbsp;&nbsp;<span>Pesan</span>
      </a>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['users'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['users'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/users'; ?>">
        <i class="fa fa-users"></i>&nbsp;&nbsp;<span>Pengguna</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_users' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['manage_users'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/users'; ?>">Semua Pengguna</a></li>
        <?php } if ( current_user_can( 'admin_add_user' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['add_user'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/users/add'; ?>">Tambah Baru</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <?php if ( isset( $show_admin_menu['settings'] ) ) { ?>
    <li class="treeview<?php echo ( isset( $admin_menu['settings'] ) ) ? ' active' : ''; ?>">
      <a href="<?php echo admin_url() . '/settings'; ?>">
        <i class="fa fa-cog"></i>&nbsp;&nbsp;<span>Pengaturan</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>

      <ul class="treeview-menu">
        <?php if ( current_user_can( 'admin_settings' ) ) { ?>
          <li<?php echo ( isset( $admin_menu['general_settings'] ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/settings'; ?>">Umum</a></li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>

  <li<?php echo ( is_route( 'admin_profile' ) ) ? ' class="active"' : ''; ?>><a href="<?php echo admin_url() . '/profile'; ?>"><i class="fa fa-user"></i>&nbsp;&nbsp;<span>Edit Profil</span></a></li>

  <li><a href="<?php echo admin_url() . '/logout'; ?>"><i class="fa fa-sign-out"></i>&nbsp;&nbsp;<span>Log Out</span></a></li>
</ul>
