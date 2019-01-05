$.widget.bridge('uibutton', $.ui.button);

$(function () {
  if ( $('.select2').length ) {
    $('.select2').select2({
      minimumResultsForSearch: -1
    });
  }

  if ( $('.select2-remote') ) {
    $('.select2-remote').each(function(){
      var action = $(this).data('action');
      $(this).select2({
        ajax: {
          url: aeo.ajax_url + "/" + action,
          dataType: 'json',
          quietMillis: 250,
          data: function (params) {
            var query = {
              q: params.term
            }

            return query;
          },
          processResults: function (data) {
            return { results: data };
          },
          cache: true
        },
        initSelection: function(element, callback) {
          var text = $(element).data('text');
          callback({ 'text': text });
        },
        formatSelection: select2remoteformatselection,
        width: '100%'
      });
    });

    function select2remoteformatselection(item) {
      return item.text;
    }
  }

  if ( $('#datatable').length ) {
    var datatable_order = false,
        datatable_length = 10;

    $.extend( $.fn.dataTable.defaults, {
      autoWidth: false,
      language: {
        search: '<span>Cari:</span> _INPUT_',
        emptyTable: 'Tidak ada ' + aeo.datatable_data_name,
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ " + aeo.datatable_data_name,
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 " + aeo.datatable_data_name,
        infoFiltered: "(difilter dari _MAX_ " + aeo.datatable_data_name + ")",
        zeroRecords: 'Tidak ada ' + aeo.datatable_data_name,
        lengthMenu: '<span>Tampil:</span> _MENU_',
        paginate: { 'first': 'Awal', 'last': 'Akhir', 'next': '&rarr;', 'previous': '&larr;' }
      }
    });

    if ( aeo.datatables_order_index >= 0 && aeo.datatables_sort )
      datatable_order = [ [ aeo.datatables_order_index, aeo.datatables_sort ] ];

    if ( aeo.datatables_length )
      datatable_length = aeo.datatables_length;

    var datatable = $('#datatable').DataTable({
      "searching": ( aeo.datatables_disable_search ) ? false : true,
      "order": datatable_order,
      "processing": true,
      "serverSide": true,
      "pageLength": datatable_length,
      "ajax": {
        url : aeo.ajax_url + "/" + aeo.datatables_load_slug + aeo.ajax_query,
        type: "post"
      },
      "columns": aeo.datatables_columns,
    });

    $('#datatable #check-all').click(function() {
      if ( $('#datatable .ids').length ) {
        if ( ! $(this).is(':checked') ) {
          $('#datatable .ids').prop('checked',false);
        } else {
          $('#datatable .ids').prop('checked',true);
        }

        enable_apply_actions_btn($('.data-actions .action'));
      } else {
        return false;
      }
    });

    $('#datatable').on('click', '.ids', function(){
      var all_checkbox = $('#datatable .ids').length,
          all_checked = $('#datatable .ids:checked').length;

      if ( all_checked >= all_checkbox ) {
        $('#datatable #check-all').prop('checked',true);
      } else if ( all_checked < all_checkbox ) {
        $('#datatable #check-all').prop('checked',false);
      }

      enable_apply_actions_btn($('.data-actions .action'));
    });
  }

  $('.data-actions .submit').on( 'click', function() {
    if ( $('.data-actions .action').val() == 'delete_selected' ) {
      $('#delete-modal').modal();
    } else {
      $('#datatable-form').find('input[name=action]').val($('.data-actions .action').val());
      $('#datatable-form').submit();
    }
  });

  $('#delete-modal .continue').on( 'click', function() {
    $('#datatable-form').find('input[name=action]').val('delete_selected');
    $('#datatable-form').submit();
  });

  $('.data-actions .action').on( 'change', function() {
    enable_apply_actions_btn($(this));
  });

  $('.report-action-form #action').on( 'change', function() {
    if ( $(this).val() == 3 ) {
      $('.report-action-form').find('.form-group--is-disposition').removeClass('hide');
    } else {
      $('.report-action-form').find('.form-group--is-disposition').addClass('hide');
    }
  });

  $('.report-action-form .remove-copy').on( 'click', function() {
    var parent = $(this).parents('tr');

    parent.remove();
    parent.find('input[type=checkbox]').prop('checked',true).appendTo($('.report-action-form'));

    if ( ! $('.report-action-form table tr').length )
      $('.report-action-form table').remove();
  });

  if ( $('.edit-form').length ) {
    $('.edit-form .delete').on( 'click', function() {
      $('#delete-modal').modal();
    });

    $('#delete-modal .continue').on( 'click', function() {
      $('.action-form').find('input[name=action]').val('delete');
      $('.action-form').submit();
    });
  }

  var current_input_file = null;

  $('.form-group--image-upload .btn-choose-image').on( 'click', function() {
    var parent = $(this).parents('.input-group');
    parent.find('.input-file-chooser').click();
  });

  $('.form-group--image-upload .input-file-chooser').on( 'change', function(e) {
    var parent = $(this).parents('.input-group'),
        reader = new FileReader();

    parent.find('.input-file-name').val(e.target.files[0].name);
    reader.onload = function(e) {
      var html = '<img src="' + e.target.result + '" alt="" />';
          html+= '<a href="javascript:;"><i class="fa fa-times"></i></a>';

      parent.next().addClass('show').html(html);
    }
    reader.readAsDataURL(e.target.files[0]);
  });

  $('.form-group--image-upload .placeholder').on( 'click', 'a', function() {
    var parent = $(this).parent().parent();
    parent.find('.placeholder').removeClass('show').html('');
    parent.find('.input-file-name').val('');
  });

  show_hide_user_form_fiels($('.form-group--user--role select'));

  $('.form-group--user--role select').on( 'change', function() {
    show_hide_user_form_fiels($(this));
  });

  function enable_apply_actions_btn(element) {
    if ( element.val() ) {
      if ( $('.ids:checked').length > 0 ) {
        $('.data-actions .submit').removeAttr('disabled');
      } else {
        $('.data-actions .submit').attr('disabled','disabled');
      }
    } else if ( element.val() == '' ) {
      $('.data-actions .submit').attr('disabled','disabled');
    }
  }

  function show_hide_user_form_fiels(element) {
    if ( element.val() > 2 ) {
      $('.form-group--user--capabilities').addClass('hide');
    } else {
      $('.form-group--user--capabilities').removeClass('hide');
    }

    if ( element.val() == 3 ) {
      $('.form-group--user--instance').removeClass('hide');
    } else {
      $('.form-group--user--instance').addClass('hide');
    }
  }
});

function confirm_deletion(url,message) {
  if ( confirm(message) )
    window.location = url;
}

$(function () {
  if ( $('.wysiwyg').length ) {
    $('.wysiwyg').each(function() {
      var id = $(this).attr('id');
      CKEDITOR.replace(id, {
        allowedContent: true,
        entities: false,
        enterMode: CKEDITOR.ENTER_BR
      });
    });
  }
});
