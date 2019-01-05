<?php include APP . '/views/admin/includes/header.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1><?php echo $aeo['page_title']; ?></h1>

      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(); ?>"><i class="fa fa-home"></i> <?php echo $aeo['dashboard_breadcrumb_title']; ?></a></li>
        <li><a href="<?php echo admin_url() . '/pages'; ?>">Halaman</a></li>
        <li class="active"><?php echo $aeo['breadcrumb_title']; ?></li>
      </ol>
    </section>

    <section class="content">
      <?php if ( $edit && current_user_can( 'admin_add_page' ) ) { ?>
        <div class="row mb-15">
          <div class="col-md-12">
            <a class="btn btn-primary" href="<?php echo admin_url() . '/pages/add'; ?>" style="margin-right: 15px;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Baru</a>
          </div>
        </div>
      <?php } ?>

      <?php if ( isset( $error_msg ) ) { ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
      <?php } elseif ( isset( $success_msg ) ) { ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
      <?php } ?>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-default mb-5">
            <form class="edit-form" role="form" action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />

              <div class="box-body box-form-groups">
                <div class="form-group">
                  <label for="title">Judul</label>
                  <input class="form-control" id="title" type="text" name="page[title]" value="<?php echo $page['title']; ?>" />
                </div>

                <div class="form-group">
                  <label for="type">Tipe</label>
                  <select class="select2 form-control" id="type" name="page[type]">
                    <option <?php echo ( (int) $page['type'] === 0 ) ? 'selected ' : ''; ?>value="0">Pilih Tipe</option>
                    <option <?php echo ( (int) $page['type'] === 1 ) ? 'selected ' : ''; ?>value="1">Profil</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="content">Konten</label>
                  <textarea class="wysiwyg form-control" rows="6" id="content" name="page[content]"><?php echo $page['content']; ?></textarea>
                </div>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary"><?php echo ( $edit ) ? 'Perbarui' : 'Tambah'; ?> Halaman</button>

                <?php if ( $edit && current_user_can( 'admin_delete_page' ) ) { ?>
                  <a class="btn btn-danger delete pull-right" href="javascript:;"><i class="fa fa-trash"></i> Hapus Halaman</a>
                <?php } ?>
              </div>
            </form>

            <?php if ( $edit && current_user_can( 'admin_delete_page' ) ) { ?>
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
    if ( $edit && current_user_can( 'admin_delete_page' ) ) {
      echo admin_create_delete_modal( [
        'title' => 'Hapus halaman',
        'body' => 'Apakah anda yakin untuk menghapus halaman ini? Semua data terkait halaman ini akan dihapus permanen!'
      ] );
    }
  ?>
<?php include APP . '/views/admin/includes/footer.php'; ?>
