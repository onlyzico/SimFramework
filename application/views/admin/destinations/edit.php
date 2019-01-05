<?php include APP . '/views/admin/includes/header.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1><?php echo $aeo['page_title']; ?></h1>

      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(); ?>"><i class="fa fa-home"></i> <?php echo $aeo['dashboard_breadcrumb_title']; ?></a></li>
        <li><a href="<?php echo admin_url() . '/destinations'; ?>">Destinasi</a></li>
        <li class="active"><?php echo $aeo['breadcrumb_title']; ?></li>
      </ol>
    </section>

    <section class="content">
      <?php if ( $edit && current_user_can( 'admin_add_destination' ) ) { ?>
        <div class="row mb-15">
          <div class="col-md-12">
            <a class="btn btn-primary" href="<?php echo admin_url() . '/destinations/add'; ?>" style="margin-right: 15px;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Baru</a>
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
                  <label for="name">Nama</label>
                  <input class="form-control" id="name" type="text" name="destination[name]" value="<?php echo $destination['name']; ?>" />
                </div>

                <div class="form-group">
                  <?php
                    if ( $destination['category_id'] )
                      $category = $aeo_db->get_row( "SELECT id,name FROM destination_categories WHERE id = $destination[category_id]" );

                    if ( ! isset( $category ) ) {
                      $category = [
                        'id' => '',
                        'name' => 'Pilih Kategori'
                      ];
                    }
                  ?>

                  <label for="category">Kategori</label>
                  <select class="form-control select2-remote" id="category" name="destination[category_id]" data-action="load-destination-categories-fs" data-text="<?php echo $category['name']; ?>">
                    <option value="<?php echo $category['id']; ?>"><?php echo stripslashes( $category['name'] ); ?></option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="content">Konten</label>
                  <textarea class="wysiwyg form-control" rows="6" id="content" name="destination[content]"><?php echo $destination['content']; ?></textarea>
                </div>

                <div class="form-group">
                  <label for="address">Alamat</label>
                  <textarea class="form-control" rows="3" id="address" name="destination[address]"><?php echo $destination['address']; ?></textarea>
                </div>

                <div class="form-group form-group--image-upload">
                  <label for="image">Gambar</label>

                  <div class="input-group">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-choose-image btn-default btn-flat"><i class="fa fa-upload"></i>&nbsp;&nbsp;Pilih Gambar</button>
                    </span>

                    <input class="form-control option-input input-file-name" readonly type="text" id="image" name="destination[image]" value="<?php echo $destination['image']; ?>" />
                    <input type="file" class="input-file-chooser hide" accept="image/*" type="file" name="image" />
                  </div>

                  <div class="placeholder<?php echo ( $edit && $destination['image'] ) ? ' show' : ''; ?>">
                    <?php if ( $edit && $destination['image'] ) { ?>
                      <img src="<?php echo site_url() . '/uploads/destinations/' . $destination['image']; ?>" alt="" />
                      <a href="javascript:;"><i class="fa fa-times"></i></a>
                    <?php } ?>
                  </div>
                </div>

                <div class="form-group">
                  <label for="latitude">Latitude</label>
                  <input class="form-control" id="latitude" type="text" name="destination[latitude]" value="<?php echo $destination['latitude']; ?>" />
                </div>

                <div class="form-group">
                  <label for="longitude">Longitude</label>
                  <input class="form-control" id="longitude" type="text" name="destination[longitude]" value="<?php echo $destination['longitude']; ?>" />
                </div>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary"><?php echo ( $edit ) ? 'Perbarui' : 'Tambah'; ?> Destinasi</button>

                <?php if ( $edit && current_user_can( 'admin_delete_destination' ) ) { ?>
                  <a class="btn btn-danger delete pull-right" href="javascript:;"><i class="fa fa-trash"></i> Hapus Destinasi</a>
                <?php } ?>
              </div>
            </form>

            <?php if ( $edit && current_user_can( 'admin_delete_destination' ) ) { ?>
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
    if ( $edit && current_user_can( 'admin_delete_destination' ) ) {
      echo admin_create_delete_modal( [
        'title' => 'Hapus destinasi',
        'body' => 'Apakah anda yakin untuk menghapus destinasi ini? Semua data terkait destinasi ini akan dihapus permanen!'
      ] );
    }
  ?>
<?php include APP . '/views/admin/includes/footer.php'; ?>
