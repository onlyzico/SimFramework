<?php include APP . '/views/admin/includes/header.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1><?php echo $aeo['page_title']; ?></h1>

      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(); ?>"><i class="fa fa-home"></i> <?php echo $aeo['dashboard_breadcrumb_title']; ?></a></li>
        <li class="active"><?php echo $aeo['breadcrumb_title']; ?></li>
      </ol>
    </section>

    <section class="content">
      <?php if ( isset( $success_msg ) ) { ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
      <?php } ?>

      <div class="row">
        <div class="col-md-6">
          <div class="box box-primary mb-5">
            <form class="edit-form" role="form" action="" method="post">
              <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />

              <div class="box-body box-form-groups">
                <?php foreach ( $options as $key => $option ) { ?>
                  <div class="form-group">
                    <label><?php echo $option['label']; ?></label>

                    <input class="form-control" type="text" name="option[<?php echo $option['name']; ?>]" value="<?php echo $option['content']; ?>" />

                    <?php if ( $option['description'] ) { ?>
                      <p class="help-block" style="margin-bottom: 0;"><?php echo $option['description']; ?></p>
                    <?php } ?>
                  </div>
                <?php } ?>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Perbarui Pengaturan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
<?php include APP . '/views/admin/includes/footer.php'; ?>
