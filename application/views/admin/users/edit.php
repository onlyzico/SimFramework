<?php include APP . '/views/admin/includes/header.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1><?php echo $aeo['page_title']; ?></h1>

      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(); ?>"><i class="fa fa-home"></i> <?php echo $aeo['dashboard_breadcrumb_title']; ?></a></li>
        <li><a href="<?php echo admin_url() . '/users'; ?>">Users</a></li>
        <li class="active"><?php echo $aeo['breadcrumb_title']; ?></li>
      </ol>
    </section>

    <section class="content">
      <?php if ( $edit && current_user_can( 'admin_add_user' ) ) { ?>
        <div class="row mb-15">
          <div class="col-md-12">
            <a class="btn btn-primary" href="<?php echo admin_url() . '/users/add'; ?>" style="margin-right: 15px;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Baru</a>
          </div>
        </div>
      <?php } ?>

      <?php if ( isset( $error_msg ) ) { ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
      <?php } elseif ( isset( $success_msg ) ) { ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
      <?php } ?>

      <div class="row">
        <div class="col-md-6">
          <div class="box box-default mb-5">
            <form class="edit-form" role="form" action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />

              <div class="box-body box-form-groups">
                <div class="form-group">
                  <label for="name">Nama</label>
                  <input class="form-control" id="name" type="text" name="user[name]" value="<?php echo $user['name']; ?>" />
                </div>

                <div class="form-group">
                  <label for="username">Username</label>
                  <input class="form-control" id="username" type="text" name="user[username]" value="<?php echo $user['username']; ?>" />
                </div>

                <div class="form-group">
                  <label for="email">Email</label>
                  <input class="form-control" id="email" type="email" name="user[email]" value="<?php echo $user['email']; ?>" />
                </div>

                <div class="form-group">
                  <label for="password">Password</label>
                  <input class="form-control" id="password" type="password" name="user[password]" />

                  <?php if ( $edit ) { ?>
                    <p class="help-block">Biarkan kosong jika tidak ingin mengganti password</p>
                  <?php } ?>
                </div>

                <div class="form-group form-group--image-upload">
                  <label for="photo">Foto</label>

                  <div class="input-group">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-choose-image btn-default btn-flat"><i class="fa fa-upload"></i>&nbsp;&nbsp;Pilih Gambar</button>
                    </span>

                    <input class="form-control option-input input-file-name" readonly type="text" id="photo" name="user[photo]" value="<?php echo $user['photo']; ?>" />
                    <input type="file" class="input-file-chooser hide" accept="image/*" type="file" name="photo" />
                  </div>

                  <div class="placeholder<?php echo ( $edit && $user['photo'] ) ? ' show' : ''; ?>">
                    <?php if ( $edit && $user['photo'] ) { ?>
                      <img src="<?php echo site_url() . '/uploads/users/' . $user['photo']; ?>" alt="" />
                      <a href="javascript:;"><i class="fa fa-times"></i></a>
                    <?php } ?>
                  </div>
                </div>

                <?php if ( $user['role'] > 1 ) { ?>
                  <div class="form-group form-group--user--role">
                    <label for="role">Role</label>

                    <select class="select2" id="role" name="user[role]" style="width: 100%;">
                      <option value="2"<?php echo ( (int) $user['role'] === 2 ) ? ' selected' : ''; ?>>Admin</option>
                    </select>
                  </div>

                  <?php if ( $aeo['user_capabilities'] ) { ?>
                    <div class="form-group form-group--user--capabilities <?php echo ( $user['role'] > 2 ) ? ' hide' : ''; ?>">
                      <label id="capabilities">Hak Akses</label>
                      <select class="select2" id="capabilities" name="user[capabilities][]" multiple placeholder="Select Capabilities" style="width: 100%;">
                        <?php foreach ( $aeo['user_capabilities'] as $user_capability ) { ?>
                          <?php if ( isset( $user_capability['group'] ) ) { ?>
                            <optgroup label="<?php echo $user_capability['group']; ?>">
                              <?php foreach ( $user_capability['items'] as $capability ) { ?>
                                <option <?php echo ( isset( $user['capabilities'] ) && in_array( $capability['value'], $user['capabilities'] ) ) ? 'selected ' : ''; ?>value="<?php echo $capability['value']; ?>"><?php echo $capability['label']; ?></option>
                              <?php } ?>
                            </optgroup>
                          <?php } else { ?>
                            <option <?php echo ( isset( $user['capabilities'] ) && in_array( $user_capability['value'], $user['capabilities'] ) ) ? 'selected ' : ''; ?>value="<?php echo $user_capability['value']; ?>"><?php echo $user_capability['label']; ?></option>
                          <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  <?php } ?>

                  <div class="form-group">
                    <label for="status">Status</label>

                    <select class="select2" id="status" name="user[status]" style="width: 100%;">
                      <option value="1"<?php echo ( (int) $user['status'] === 1 ) ? ' selected' : ''; ?>>Active</option>
                      <option value="2"<?php echo ( (int) $user['status'] === 2 ) ? ' selected' : ''; ?>>Tidak Aktif</option>
                    </select>
                  </div>
                <?php } ?>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary"><?php echo ( $edit ) ? 'Perbarui' : 'Tambah'; ?> Pengguna</button>

                <?php if ( $edit && $user['id'] > 1 && current_user_can( 'admin_delete_user' ) ) { ?>
                  <a class="btn btn-danger delete pull-right" href="javascript:;"><i class="fa fa-trash"></i> Hapus Pengguna</a>
                <?php } ?>
              </div>
            </form>

            <?php if ( $edit && $user['id'] > 1 && current_user_can( 'admin_delete_user' ) ) { ?>
              <form class="action-form" method="post" action="">
                <input type="hidden" name="action" value="" />
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />
              </form>
            <?php } ?>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php
    if ( $edit && $user['id'] > 1 && current_user_can( 'admin_delete_user' ) ) {
      echo admin_create_delete_modal( [
        'title' => 'Hapus pengguna',
        'body' => 'Apakah anda yakin untuk menghapus pengguna ini? Semua data terkait pengguna ini akan dihapus permanen!'
      ] );
    }
  ?>
<?php include APP . '/views/admin/includes/footer.php'; ?>
