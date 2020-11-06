(function ($) {
  "use strict";

  $(document).ready(function () {
    // Jsi18n demonstration
    $("#jsi18n-sample").on("click", function (e) {
      if (e.preventDefault) {
        e.preventDefault();
      } else {
        e.returnValue = false;
      }
      alert("{{admin dashboard jsi18n-sample}}");
    });
  });
})(jQuery);
