(function ($) {
  "use strict";

  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  // this script needs to be loaded on every page where an ajax POST
  $.ajaxSetup({
    data: {
      [csrf_Name]: csrf_Hash,
    },
  });

  $(".comapre_products_nav .nav-link").on("click", function (e) {


    comapre_products_nav();
  });

  $(document).on("click", function () {
    $(".comapre_products_nav .comapre_products ").slideUp();
  });

  $(document).on(
    "click",
    ".comapre_products_nav .comapre_products a.remove_from_compare.btn.btn-link",
    function (e) {
      e.preventDefault();
      var base_url = $("#main_base_url").val();
      var product_slug = $(this).data("product_slug");
      var product_category = $(this).data("product_category");

      $.ajax({
        type: "POST",
        url: base_url +
          "compare-product-remove/" +
          product_slug +
          "/" +
          product_category,
        data: {
          product_slug: product_slug,
          product_category: product_category,
        },

        success: function (response) {
          if (response) {
            response = JSON.parse(response);
            if (response.status != "error") {
              $(".filter_listing")
                .find(".compare_product_link.list_compare_link." + product_slug)
                .removeClass("add_to_compare");
              $(".filter_listing")
                .find(
                  ".compare_product_link.list_compare_link." +
                  product_slug +
                  " i.fas"
                )
                .removeClass("fa-check");
              $(".filter_listing")
                .find(
                  ".compare_product_link.list_compare_link." +
                  product_slug +
                  " i.fas"
                )
                .addClass("fa-plus");

              $(".comapre_products_nav .nav-link .badge-dark").text(
                response.compare_count
              );
              console.log(response.msg);
              comapre_products_nav();
            } else {
              $(".filter_listing")
                .find(".compare_product_link.list_compare_link." + product_slug)
                .removeClass("add_to_compare");
              $(".filter_listing")
                .find(
                  ".compare_product_link.list_compare_link." +
                  product_slug +
                  " i.fas"
                )
                .removeClass("fa-check");
              $(".filter_listing")
                .find(
                  ".compare_product_link.list_compare_link." +
                  product_slug +
                  " i.fas"
                )
                .addClass("fa-plus");

              $(".comapre_products_nav .nav-link .badge-dark").text(
                response.compare_count
              );
              console.log(response.msg);
              comapre_products_nav();
            }
          } else {
            alert("Server Response Error");
          }
        },
        error: function (e) {
          console.log(e);
        },
      });
    }
  );

  function comapre_products_nav() {
    var base_url = $("#main_base_url").val();
    $.ajax({
      type: "POST",
      url: base_url + "compare-product-nav-data",
      success: function (response) {
        if (response) {
          response = JSON.parse(response);
          $(".comapre_products_nav .comapre_products ").html(response.content);
          $(".comapre_products_nav .comapre_products ").slideDown();
        } else {
          alert("Server Response Error");
        }
      },
      error: function (e) {
        console.log(e);
      },
    });
  }

  $(".add_fav_product_btn").on('click', function (e) {
    var product_id = $(this).data("product_id");
    var base_url = $("#main_base_url").val();
    var element = $(this);
    if (product_id && base_url) {
      $.ajax({
        type: "GET",
        url: base_url + "add-to-fav-product/" + product_id,
        success: function (response) {
          if (response) {
            response = JSON.parse(response);
            if (response.status == "success") {
              if (response.action == "added") {
                $(element).children(".fav_icon").removeClass("text-secondary");
                $(element).children(".fav_icon").addClass("text-danger");
              } else {
                $(element).children(".fav_icon").removeClass("text-danger");
                $(element).children(".fav_icon").addClass("text-secondary");
              }
            } else {
              if (response.action == "redirect") {
                window.location.href = base_url + "login";
              } else {
                alert(response.msg);
              }
            }
          } else {
            alert("Server Response Error");
          }
        },
        error: function (e) {
          console.log(e);
        },
      });
    } else {
      alert("Sorry Invalid Request");
    }
  });

  /**
   * Configurations
   */
  var config = {
    logging: true,
    baseURL: BASE_URL,
  };

  /**
   * Bootstrap IE10 viewport bug workaround
   */
  if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement("style");
    msViewportStyle.appendChild(
      document.createTextNode("@-ms-viewport{width:auto!important}")
    );
    document.querySelector("head").appendChild(msViewportStyle);
  }

  /**
   * Execute an AJAX call
   */
  function executeAjax(url, data, callback) {
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      dataType: "json",
      async: true,
      success: function (results) {
        callback(results);
      },
      error: function (error) {
        alert("Error " + error.status + ": " + error.statusText);
      },
    });
    // prevent default action
    return false;
  }

  /**
   * Global core functions
   */
  $(document).ready(function () {
    /**
     * Session language selected
     */
    $("#session-language-dropdown a").on('click', function (e) {
      // prevent default behavior
      if (e.preventDefault) {
        e.preventDefault();
      } else {
        e.returnValue = false;
      }

      // set up post data
      var postData = {
        language: $(this).attr("rel"),
      };

      // define callback function to handle AJAX call result
      var ajaxResults = function (results) {
        if (results.success) {
          location.reload();
        } else {
          alert("{{core error session_language}}");
        }
      };

      // perform AJAX call
      executeAjax(
        config.baseURL + "ajax/set_session_language",
        postData,
        ajaxResults
      );
    });
  });

  // function to set a given theme/color-scheme
  function setTheme(themeName) {
    localStorage.setItem("theme", themeName);
    document.documentElement.className = themeName;
  }

  $(".toggleTheme").on("change", function (c) {
    toggleTheme();
  });
  // function to toggle between light and dark theme
  function toggleTheme() {
    if (localStorage.getItem("theme") === "theme-dark") {
      setTheme("theme-light");
    } else {
      setTheme("theme-dark");
    }
  }

  // Immediately invoked function to set the theme on initial load
  (function () {
    if (localStorage.getItem("theme") === "theme-dark") {
      setTheme("theme-dark");
      document.getElementById("slider").checked = false;
    } else {
      setTheme("theme-light");
      document.getElementById("slider").checked = true;
    }
  })();

  $(function () {
    var header = $(".start-style");
    $(window).scroll(function () {
      var scroll = $(window).scrollTop();

      if (scroll >= 10) {
        header.removeClass("start-style").addClass("scroll-on");
      } else {
        header.removeClass("scroll-on").addClass("start-style");
      }
    });
  });

  //Animation

  $(document).ready(function () {
    $("body.hero-anime").removeClass("hero-anime");
  });

  if (flash_message == "undefined") {
    var flash_message = "";
  }
  if (flash_error == "undefined") {
    var flash_error = "";
  }
  // if(flash_validation == 'undefined'){ var flash_validation = ''; }
  if (error_report == "undefined") {
    var error_report = "";
  }

  if (flash_message) {
    new Noty({
      type: "success",
      layout: "topRight",
      text: flash_message,
      timeout: 5000,
      progressBar: true,
      theme: "metroui ",
      closeWith: ["click", "button"],
    }).show();
  }

  if (flash_error) {
    new Noty({
      type: "error",
      layout: "topRight",
      text: flash_error,
      timeout: 5000,
      progressBar: true,
      theme: "mint",
      closeWith: ["click", "button"],
    }).show();
  }

  if (error_report) {
    new Noty({
      type: "error",
      layout: "topRight",
      text: error_report,
      timeout: 5000,
      progressBar: true,
      theme: "mint",
      closeWith: ["click", "button"],
    }).show();
  }

  if (ad_left_time > 0) {
    new Noty({
      type: "warning",
      layout: "bottomRight",
      text: '<div class="row"><div class="col-12 text-center"> <i class="fa fa-clock"></i> <div class="notytimerrr" data-seconds-left=' +
        ad_left_time +
        '></div><section class="actions"></section><div class="text-center col-12"><a class="btn btn-dark mt-2" href="' +
        ad_active_quiz +
        '">' +
        resume_quiz_lang +
        "</a></div></div></div>",
      timeout: ad_left_time * 1000,
      progressBar: true,
      theme: "mint",
      closeWith: false,
    }).show();
  }

  if (ad_left_time <= 0 && ad_active_quiz && test_page != "quiz") {
    new Noty({
      type: "warning",
      layout: "bottomRight",
      text: '<div class="row p-2"><h3> ' +
        check_quiz_result +
        ' </h3><div class="text-center col-12"><a class="btn btn-dark mt-2" href="' +
        ad_active_quiz +
        '">' +
        quiz_result_lang +
        "</a></div></div>",
      timeout: false,
      progressBar: true,
      theme: "mint",
      closeWith: false,
    }).show();
  }

  $(function () {
    $(".notytimerrr").startTimer({
      onComplete: function (element) {
        $("html, body").addClass("bodyTimeoutBackground");
        window.open(ad_active_quiz);
        location.reload();
      },
    });
  });

  var btn = $("#back-to-top-button");

  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass("show");
    } else {
      btn.removeClass("show");
    }
  });

  btn.on("click", function (e) {
    e.preventDefault();
    $("html, body").animate({
      scrollTop: 0
    }, "300");
  });
})(jQuery);