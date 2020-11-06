(function ($) {
  "use strict";

  $(".statrt_quiz_btnnn").on("click", function (e) {
    alert("fvfv");
    var link = $(this).attr("href");
    var quiz_id = $(this).data("quiz_id");
    e.preventDefault(false);

    if (quiz_id && link) {
      $.ajax({
        url: link,
        type: "POST",
        data: {
          quiz_id: quiz_id
        },
        success: function (result) {
          result = JSON.parse(result);
          if (result.success) {} else {}
        },
        error: function (e) {
          console.log(e);
        },
      });
    } else {}
  });

  $("#Quiz_filter").on("change", function (e) {
    $("#Quiz_filter_form").submit();
  });

  $(".quiz_running").on("click", function (e) {
    var link = $(this).attr("href");

    e.preventDefault(false);

    swal({
        title: quiz_already_running,
        text: stop_running_quiz_msg,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: resume_quiz,
        cancelButtonText: stop_quiz,
      },
      function (isConfirm) {
        if (isConfirm == true) {
          window.location.href = link;
        } else {
          var session_quiz_id = $(".session_quiz_id").val();
          window.location.href = BASE_URL + "result/" + session_quiz_id;
        }
      }
    );
  });

  $(".no_quiz_start").on("click", function (e) {
    var link = $(this).attr("href");

    if (login_user_id == 0 && ad_active_quiz == "") {
      e.preventDefault();
      swal({
          title: "Enter Your Name",
          type: "input",
          showCancelButton: true,
          closeOnConfirm: false,
          inputPlaceholder: "Write Your Name Here",
        },
        function (inputValue) {
          if (inputValue === false) return false;
          if (inputValue === "") {
            swal.showInputError("Plz Enter Name First!");
            return false;
          } else {
            var base_url = $("#main_base_url").val();

            $.ajax({
              type: "POST",
              url: BASE_URL + "quiz_Controller/set_leader_bord_user_name",
              data: {
                inputValue: inputValue,
              },

              success: function (response) {
                if (response) {
                  response = JSON.parse(response);
                  if (response.status != "error") {
                    window.location.href = link;
                  } else {
                    swal(response.msg, "error");
                    location.reload();
                  }
                } else {
                  swal("Server Response Error", "error");
                }
              },
              error: function (e) {
                console.log(e);
              },
            });
          }
        }
      );
    } else {
      location.reload();
    }
  });

  $(".like-quiz i").on("click", function (e) {
    e.preventDefault();
    var quiz_id = $(this).data("quiz_id");
    var element = $(this);
    if ($(this).hasClass("text-muted")) {
      $.ajax({
        url: BASE_URL + "like/like_quiz",
        type: "POST",
        data: {
          quiz_id: quiz_id
        },
        success: function (result) {
          result = JSON.parse(result);
          if (result.success) {
            element.removeClass("text-muted");
            element.addClass("text-success");
            $(".like-quiz-count-" + quiz_id).html(result.success.total_like);
          } else if (result.status == "redirect") {
            window.location.href = BASE_URL + "login";
          } else if (result.error == "unsuccessfull") {
            alert("Something happen wrong");
          }
        },
        error: function (e) {
          console.log(e);
        },
      });
    } else {
      $.ajax({
        url: BASE_URL + "dislike/like_quiz_delete",
        type: "POST",
        data: {
          quiz_id: quiz_id
        },
        success: function (result) {
          result = JSON.parse(result);
          if (result.success) {
            element.removeClass("text-success");
            element.addClass("text-muted");
            $(".like-quiz-count-" + quiz_id).html(result.success.total_like);
          } else if (result.status == "redirect") {
            window.location.href = BASE_URL + "login";
          } else if (result.error == "unsuccessfull") {
            alert("Something happen wrong");
          }
        },
        error: function (e) {
          console.log(e);
        },
      });
    }
  });
})(jQuery);