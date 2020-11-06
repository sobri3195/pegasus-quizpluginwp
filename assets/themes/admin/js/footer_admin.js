(function ($) {
  "use strict";

  var table;
  var csrfName = $("#csrf_hash").val();
  var csrf_token = $("#csrf_token").val();

  //datatables
  table = $("#table").DataTable({
    language: {
      info: table_showing +
        " _START_ " +
        table_to +
        " _END_ " +
        table_of +
        " _TOTAL_ " +
        table_entries,
      paginate: {
        previous: table_previous,
        next: table_next,
      },
      sLengthMenu: table_show + " _MENU_ " + table_entries,
      sSearch: table_search,
    },

    processing: true, //Feature control the processing indicator.
    serverSide: true, //Feature control DataTables' server-side processing mode.
    order: [],
    ajax: {
      url: BASE_URL + "admin/pages/page_list",
      type: "POST",
    },

    //Set column definition initialisation properties.
    columnDefs: [{
      targets: [0, 3], //first column / numbering column
      orderable: false, //set not orderable
    }, ],
  });

  //select2 tool jquery
  $(document).ready(function () {
    $(".select_dropdown").select2();

    $(function () {
      $(".body_of_field").sortable({
        revert: true,
        stop: function (event, ui) {
          var new_position = ui.item.index();
        },
      });

      $(".body_of_field").disableSelection();
    });
  });

  $(".popup").on("click", function () {
    $("#imagepreview").attr("src", $(this).attr("src"));
    $("#imagemodal").modal("show");
  });

  //product and variant delete with sweetalert
  $("body").on("click", ".common_delete", function (e) {
    var link = $(this).attr("href");

    e.preventDefault(false);
    swal({
        title: are_you_sure,
        text: permanently_deleted,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: yes_delere_it,
      },
      function (isConfirm) {
        if (isConfirm == true) {
          window.location.href = link;
        }
      }
    );
  });

  $(".add_more_footer_field").on("click", function ($btn) {
    var footer_section = $(".footer_section").val();
    var input_type = $(".input_type").val();

    if (footer_section && input_type) {
      if (footer_section == "first") {
        var html = get_input_html(1, input_type);
        $(".footer_section_b_1").append(html);
      } else if (footer_section == "second") {
        var html = get_input_html(2, input_type);
        $(".footer_section_b_2").append(html);
      } else if (footer_section == "third") {
        var html = get_input_html(3, input_type);
        $(".footer_section_b_3").append(html);
      } else if (footer_section == "fourth") {
        var html = get_input_html(4, input_type);
        $(".footer_section_b_4").append(html);
      } else {
        swal("Error", "Some Thing Went Wrong");
      }
    } else {
      swal("Sorry", "Plz Select Fotter Section Or Input Type First");
    }

    $(function () {
      $(".body_of_field").sortable({
        revert: true,
        stop: function (event, ui) {
          var new_position = ui.item.index();
        },
      });

      $(".body_of_field").disableSelection();
    });
  });

  function get_input_html(section, input_type) {
    if (input_type == "text") {
      var text =
        '<div class="form-group pb-3 border-bottom input_field_div"><label class="w-100">Text Title<span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label><input type="text" class="form-control" name="post_data[' +
        section +
        '][text][title][]">  <label class="w-100">Text</label><input type="text" class="form-control" name="post_data[' +
        section +
        '][text][value][]"></div>';
      return text;
    } else if (input_type == "link") {
      var link =
        '<div class="form-group pb-3 border-bottom input_field_div"> <label class="w-100">Link Title <span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label><input type="text" class="form-control" name="post_data[' +
        section +
        '][link][title][]">     <label class="w-100">Link</label><input type="text" class="form-control" name="post_data[' +
        section +
        '][link][value][]"></div>';
      return link;
    } else if (input_type == "editor") {
      var editor =
        '<div class="form-group pb-3 border-bottom input_field_div"> <label class="w-100">Editor Title <span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label><input type="text" class="form-control" name="post_data[' +
        section +
        '][editor][title][]">    <label class="w-100">Editor </label> <textarea id="p_desc" class="form-control editor" rows="5" name="post_data[' +
        section +
        '][editor][value][]"></textarea></div>';
      return editor;
    } else if (input_type == "image") {
      var editor =
        '<div class="form-group pb-3 border-bottom input_field_div"> <label class="w-100">Image Title <span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label><input  type="text" class="form-control" name="post_data[' +
        section +
        '][image][title][]">    <label class="w-100">Image </label> <input accept="image/*" type="file" class="form-control" name="post_data[' +
        section +
        '][image][value][]"></div>';
      return editor;
    } else {
      return false;
    }
  }

  $("body").on("click", ".remove_this_input", function (e) {
    var input_div = $(this);
    e.preventDefault(false);
    swal({
        title: are_you_sure,
        text: "Do You Want To remove This Input Field",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes Remove It",
      },
      function (isConfirm) {
        if (isConfirm == true) {
          var input_field_div = input_div.closest(".input_field_div");
          input_field_div.remove();
        }
      }
    );
  });
})(jQuery);