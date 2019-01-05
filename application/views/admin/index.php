<?php include APP . '/views/admin/includes/header.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1><?php echo $aeo['page_title']; ?></h1>

      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(); ?>"><i class="fa fa-home"></i> <?php echo $aeo['dashboard_breadcrumb_title']; ?></a></li>
      </ol>
    </section>

    <section class="content">
      <div class="callout callout-info mb-15">Selamat datang kembali, <strong><?php echo $current_user['username']; ?></strong></div>
    </section>
  </div>
<?php include APP . '/views/admin/includes/footer.php'; ?>
