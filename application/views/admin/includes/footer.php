      <footer class="main-footer">
        &copy; 2018 <strong><?php echo get_option( 'site_name' ); ?></strong>.
      </footer>

      <div class="control-sidebar-bg"></div>
    </div>

    <?php
      $localize_script['url'] = admin_url();
      $localize_script['route'] = get_route( 'name' );
      $localize_script['ajax_url'] = admin_url() . '/ajax';
      $localize_script['ajax_query'] = ( isset( $aeo['ajax_query'] ) && $aeo['ajax_query'] ) ? $aeo['ajax_query'] : '';

      if ( isset( $aeo['localize_script'] ) && is_array( $aeo['localize_script'] ) && $aeo['localize_script'] )
        $localize_script += $aeo['localize_script'];

      register_localize_script( 'aeo', $localize_script );
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.6/fastclick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

    <?php
      if ( isset( $aeo['load_scripts'] ) )
        foreach ( $aeo['load_scripts'] as $script )
          echo '<script type="text/javascript" src="' . $script . '"></script>' . "\n";
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/js/adminlte.min.js"></script>
    <script src="<?php echo assets_url() . '/admin/js/admin.js'; ?>"></script>
  </body>
</html>
