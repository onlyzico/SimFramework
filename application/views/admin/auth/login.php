<?php include 'includes/header.php'; ?>
  <div class="login-box-body">
    <h3 class="login-box-msg">Login</h3>

    <?php if ( isset( $error_msg ) ) { ?>
      <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php } elseif ( isset( $success_msg ) ) { ?>
      <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php } ?>

    <form action="" method="post">
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />

      <div class="form-group has-feedback">
        <input name="login[id]" type="text" class="form-control" placeholder="Username atau Email" value="<?php echo get_input( 'login:id' ); ?>" />
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input name="login[password]" type="password" class="form-control" placeholder="Password" />
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-6">
          <button type="submit" class="btn btn-primary btn-flat">Login</button>
        </div>

        <div class="col-xs-6">
          <a href="<?php echo admin_url() . '/password/reset'; ?>" class="pull-right btn btn-default btn-flat">Lupa Password?</a>
        </div>
      </div>
    </form>
  </div>
<?php include 'includes/footer.php'; ?>
