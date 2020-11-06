/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */
(function ($) {
  "use strict";
  // this script needs to be loaded on every page where an ajax POST
  $.ajaxSetup({
    data: {
      [csrf_Name]: csrf_Hash,
    },
  });

  $(document).on("click", ".common_copy_record", function (e) {
    var link = $(this).attr("href");

    e.preventDefault(false);
    swal({
        title: are_you_sure,
        text: "Copy This Record",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Copy Now",
      },
      function (isConfirm) {
        if (isConfirm == true) {
          window.location.href = link;
        }
      }
    );
  });
  $("#table").on("hover", function (e) {
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
  });

  $(document).ready(function () {
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
  });
})(jQuery);