<?php include APP . '/views/admin/includes/header.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1><?php echo $aeo['page_title']; ?></h1>

      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(); ?>"><i class="fa fa-home"></i> <?php echo $aeo['dashboard_breadcrumb_title']; ?></a></li>
        <li><a href="<?php echo admin_url() . '/news'; ?>">Berita</a></li>
        <li class="active"><?php echo $aeo['breadcrumb_title']; ?></li>
      </ol>
    </section>

    <section class="content">
      <?php if ( $edit && current_user_can( 'admin_add_news' ) ) { ?>
        <div class="row mb-15">
          <div class="col-md-12">
            <a class="btn btn-primary" href="<?php echo admin_url() . '/news/add'; ?>" style="margin-right: 15px;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Baru</a>
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
                  <input class="form-control" id="title" type="text" name="news[title]" value="<?php echo $news['title']; ?>" />
                </div>

                <div class="form-group">
                  <label for="content">Konten</label>
                  <textarea class="wysiwyg form-control" rows="6" id="content" name="news[content]"><?php echo $news['content']; ?></textarea>
                </div>

                <div class="form-group form-group--image-upload">
                  <label for="image">Gambar</label>

                  <div class="input-group">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-choose-image btn-default btn-flat"><i class="fa fa-upload"></i>&nbsp;&nbsp;Pilih Gambar</button>
                    </span>

                    <input class="form-control option-input input-file-name" readonly type="text" id="image" name="news[image]" value="<?php echo $news['image']; ?>" />
                    <input type="file" class="input-file-chooser hide" accept="image/*" type="file" name="image" />
                  </div>

                  <div class="placeholder<?php echo ( $edit && $news['image'] ) ? ' show' : ''; ?>">
                    <?php if ( $edit && $news['image'] ) { ?>
                      <img src="<?php echo site_url() . '/uploads/news/' . $news['image']; ?>" alt="" />
                      <a href="javascript:;"><i class="fa fa-times"></i></a>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary"><?php echo ( $edit ) ? 'Perbarui' : 'Tambah'; ?> Berita</button>

                <?php if ( $edit && current_user_can( 'admin_delete_news' ) ) { ?>
                  <a class="btn btn-danger delete pull-right" href="javascript:;"><i class="fa fa-trash"></i> Hapus Berita</a>
                <?php } ?>
              </div>
            </form>

            <?php if ( $edit && current_user_can( 'admin_delete_news' ) ) { ?>
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
    if ( $edit && current_user_can( 'admin_delete_news' ) ) {
      echo admin_create_delete_modal( [
        'title' => 'Hapus berita',
        'body' => 'Apakah anda yakin untuk menghapus berita ini? Semua data terkait berita ini akan dihapus permanen!'
      ] );
    }
  ?>
<?php include APP . '/views/admin/includes/footer.php'; ?>
