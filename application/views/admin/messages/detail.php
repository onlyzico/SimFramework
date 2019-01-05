<?php include APP . '/views/admin/includes/header.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1><?php echo $aeo['page_title']; ?></h1>

      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(); ?>"><i class="fa fa-home"></i> <?php echo $aeo['dashboard_breadcrumb_title']; ?></a></li>
        <li><a href="<?php echo admin_url() . '/messages'; ?>">Pesan</a></li>
        <li class="active"><?php echo $aeo['breadcrumb_title']; ?></li>
      </ol>
    </section>

    <section class="content">
      <?php if ( isset( $success_msg ) ) { ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
      <?php } ?>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-default">
            <div class="box-header with-border">
              <div class="pd-10">
                <p>Nama: <strong><?php echo $message['name']; ?></strong></p>
                <p>Subject: <strong><?php echo $message['subject']; ?></strong></p>
                <p style="margin-bottom: 0;">Email: <strong><?php echo $message['email']; ?></strong></p>
              </div>
            </div>

            <div class="box-body">
              <div class="pd-10">
                <?php echo $message['message']; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<?php include APP . '/views/admin/includes/footer.php'; ?>
