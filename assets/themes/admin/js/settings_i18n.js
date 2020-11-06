(function ($) {
  "use strict";

  $(document).ready(function () {
    /**
     * Apply form-control class and id to timezones dropdown
     */
    $("select[name=timezones]")
      .addClass("form-control")
      .attr("id", "timezones");
  });

  $(".popup").on("click", function () {
    $("#imagepreview").attr("src", $(this).attr("src"));
    $("#imagemodal").modal("show");
  });
})(jQuery);
