<?php include 'includes/header.php'; ?>
  <div class="login-box-body">
    <h3 class="login-box-msg">Buat Password</h3>

    <?php if ( isset( $error_msg ) ) { ?>
      <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php } ?>

    <form action="" method="post">
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />

      <div class="form-group has-feedback">
        <input name="create_password[password]" type="password" class="form-control" placeholder="Password" />
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input name="create_password[confirm_password]" type="password" class="form-control" placeholder="Konfirmasi Password" />
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-6">
          <a href="<?php echo admin_url() . '/login'; ?>" class="btn btn-default btn-flat">&larr; Login</a>
        </div>

        <div class="col-xs-6">
          <button type="submit" class="pull-right btn btn-primary btn-flat">Simpan Password</button>
        </div>
      </div>
    </form>
  </div>
<?php include 'includes/footer.php'; ?>
