(function ($) {
  "use strict";

  var table;

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
      sLengthMenu: table_show + " _MENU_ " + table_entries,
      sSearch: table_search,
      paginate: {
        previous: table_previous,
        next: table_next,
      },
    },

    processing: true, //Feature control the processing indicator.
    serverSide: true, //Feature control DataTables' server-side processing mode.
    order: [], //Initial no order.
    ajax: {
      url: BASE_URL + "admin/category/category_list",
      type: "POST",
    },

    //Set column definition initialisation properties.
    columnDefs: [{
      targets: [0, 2, 3, 5], //first column / numbering column
      orderable: false, //set not orderable
    }, ],
  });

  $("#target").on("change", function (e) {
    $("#iconfield").val(e.icon);
  });

  $("body").on("change", ".togle_switch", function (e) {
    $.notify({
      // options
      message: "Status updated for category",
      target: "_blank",
    }, {
      // settings
      element: "body",
      placement: {
        from: "top",
        align: "right",
      },
      offset: 20,
      spacing: 10,
      z_index: 1031,
      delay: 5000,
      timer: 1000,
    });

    if ($(this).prop("checked") == true) {
      var status = 1;
    } else {
      var status = 0;
    }
    var ids = $(this).data("id");
    $.ajax({
      url: BASE_URL + "admin/category/update_status",
      type: "POST",
      data: {
        category_id: ids,
        status: status
      },
      success: function () {},
      error: function (e) {},
    });
  });

  $("body").on("click", ".cat_delete", function (e) {
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

  //select2 tool jquery
  $(document).ready(function () {
    $(".select_dropdown").select2();
  });

  $(".popup").on("click", function () {
    $("#imagepreview").attr("src", $(this).attr("src"));
    $("#imagemodal").modal("show");
  });
  $("#custom-fields").multiSelect({
    keepOrder: true
  });
})(jQuery);