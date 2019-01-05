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

      <div class="row data-actions mb-15">
        <div class="col-md-12">
          <?php if ( current_user_can( 'admin_add_creative_economy' ) ) { ?>
            <a class="btn btn-primary" href="<?php echo admin_url() . '/creative-economy/add'; ?>" style="margin-right: 15px;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Baru</a>
          <?php } ?>

          <select class="action select2" style="width: 150px;">
            <option value="">Aksi Masal</option>
            <?php if ( current_user_can( 'admin_delete_creative_economy' ) ) { ?>
              <option value="delete_selected">Hapus Terpilih</option>
            <?php } ?>
          </select>

          <button type="button" class="btn btn-primary submit" disabled style="margin-left: 15px;">Terapkan</button>
        </div>
      </div>

      <form method="post" action="" id="datatable-form">
        <input type="hidden" name="action" />
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />

        <div class="box mb-0">
          <div class="box-body">
            <div class="table-responsive">
              <table id="datatable" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <?php if ( current_user_can( 'admin_delete_creative_economy' ) ) { ?>
                      <th width="4%"><input id="check-all" type="checkbox" /></th>
                    <?php } ?>

                    <th width="8%">ID</th>
                    <th width="8%"></th>
                    <th>Nama</th>
                    <th width="15%">Tgl. Perbarui</th>

                    <?php if ( current_user_can( 'admin_edit_creative_economy' ) ) { ?>
                      <th width="7%"></th>
                    <?php } ?>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </form>
    </section>
  </div>

  <?php
    if ( current_user_can( 'admin_delete_creative_economy' ) ) {
      echo admin_create_delete_modal( [
        'title' => 'Hapus ekraf terpilih',
        'body' => 'Apakah anda yakin untuk menghapus ekraf terpilih?<br />Semua data terkait ekraf terpilih akan dihapus permanen!'
      ] );
    }
  ?>
<?php include APP . '/views/admin/includes/footer.php'; ?>
