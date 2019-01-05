<?php include 'includes/header.php'; ?>
  <div class="login-box-body">
    <h3 class="login-box-msg">Reset password</h3>

    <?php if ( isset( $error_msg ) ) { ?>
      <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php } elseif ( isset( $success_msg ) ) { ?>
      <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php } ?>

    <form action="" method="post">
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />

      <div class="form-group has-feedback">
        <input name="reset_password[id]" type="text" class="form-control" placeholder="Username atau Email" value="<?php echo get_input( 'reset_password:id' ); ?>" />
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-6">
          <a href="<?php echo admin_url() . '/login'; ?>" class="btn btn-default btn-flat">&larr; Kembali</a>
        </div>

        <div class="col-xs-6">
          <button type="submit" class="pull-right btn btn-primary btn-flat">Lanjutkan</button>
        </div>
      </div>
    </form>
  </div>
<?php include 'includes/footer.php'; ?>
