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
      <?php if ( isset( $error_msg ) ) { ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
      <?php } elseif ( isset( $success_msg ) ) { ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
      <?php } ?>

      <div class="row">
        <div class="col-md-6">
          <div class="box box-primary mb-5">
            <form class="edit-form" role="form" action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />

              <div class="box-body box-form-groups">
                <div class="form-group">
                  <label for="name">Nama</label>
                  <input class="form-control" id="name" type="text" name="user[name]" value="<?php echo stripslashes( $user['name'] ); ?>" />
                </div>

                <div class="form-group">
                  <label for="username">Username</label>
                  <input class="form-control" id="username" type="text" name="user[username]" value="<?php echo stripslashes( $user['username'] ); ?>" />
                </div>

                <div class="form-group">
                  <label for="email">Email</label>
                  <input class="form-control" id="email" type="email" name="user[email]" value="<?php echo stripslashes( $user['email'] ); ?>" />
                </div>

                <div class="form-group">
                  <label for="password">Password</label>
                  <input class="form-control" id="password" type="password" name="user[password]" />
                  <p class="help-block" style="margin-bottom: 0;">Biarkan kosong jika anda tidak ingin mengganti password</p>
                </div>

                <div class="form-group form-group--image-upload">
                  <label for="photo">Foto</label>

                  <div class="input-group">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-choose-image btn-default btn-flat"><i class="fa fa-upload"></i>&nbsp;&nbsp;Pilih Foto</button>
                    </span>

                    <input class="form-control option-input input-file-name" readonly type="text" id="photo" name="user[photo]" value="<?php echo $user['photo']; ?>" />
                    <input type="file" class="input-file-chooser hide" accept="image/*" type="file" name="photo" />
                  </div>

                  <div class="placeholder<?php echo ( $user['photo'] ) ? ' show' : ''; ?>">
                    <?php if ( $user['photo'] ) { ?>
                      <img src="<?php echo site_url() . '/uploads/users/' . $user['photo']; ?>" alt="" />
                      <a href="javascript:;"><i class="fa fa-times"></i></a>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Perbarui Profil</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
<?php include APP . '/views/admin/includes/footer.php'; ?>
