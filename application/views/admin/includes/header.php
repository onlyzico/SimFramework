<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />

    <title><?php echo site_title( $aeo['site_title'] ); ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />

    <?php
      if ( isset( $aeo['load_styles'] ) )
        foreach ( $aeo['load_styles'] as $style )
          echo '<link href="' . $style . '" rel="stylesheet" />' . "\n";
    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/AdminLTE.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/skins/_all-skins.min.css" />
    <link rel="stylesheet" href="<?php echo assets_url() . '/admin/css/admin.css'; ?>" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" />
  </head>

  <body class="hold-transition skin-black-light sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <a href="<?php echo admin_url(); ?>" class="logo">
          <span class="logo-mini"><b>M</b>T</span>
          <span class="logo-lg"><b>Medan</b> Tourism</span>
        </a>

        <nav class="navbar navbar-static-top">
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
        </nav>
      </header>

      <aside class="main-sidebar">
        <section class="sidebar">
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo $current_user['photo']; ?>" class="img-circle" alt="<?php echo $current_user['name']; ?>" />
            </div>

            <div class="pull-left info">
              <p><?php echo $current_user['name']; ?></p>
              <a href="<?php echo admin_url() . '/profile'; ?>"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>

          <?php include APP . '/views/admin/includes/menu.php'; ?>
        </section>
      </aside>
