$(function () {
  "use strict";
  $(".timerrr")
    .startTimer({
      onComplete: function (element) {
        $("#myform").html("");
        $("html, body").addClass("bodyTimeoutBackground");
        location.reload();
      },
    })
    .click(function () {
      location.reload();
    });
});
