(function ($) {
  "use strict";

  $(document).ready(function () {
    $(".select2").select2();
    
  });

  $(".selectpicker").selectpicker();

  (function () {
    var parent = document.querySelector(".range-slider");
    if (!parent) return;

    var rangeS = parent.querySelectorAll("input[type=range]"),
      numberS = parent.querySelectorAll("input[type=number]");

    rangeS.forEach(function (el) {
      el.oninput = function () {
        var slide1 = parseFloat(rangeS[0].value),
          slide2 = parseFloat(rangeS[1].value);

        if (slide1 > slide2) {
          [slide1, slide2] = [slide2, slide1];
        }

        numberS[0].value = slide1;
        numberS[1].value = slide2;
      };
    });

    numberS.forEach(function (el) {
      el.oninput = function () {
        var number1 = parseFloat(numberS[0].value),
          number2 = parseFloat(numberS[1].value);

        if (number1 > number2) {
          var tmp = number1;
          numberS[0].value = number2;
          numberS[1].value = tmp;
        }

        rangeS[0].value = number1;
        rangeS[1].value = number2;
      };
    });
  })();

  $(".gride.width_100").slideUp();

  $(".listing_type_list").on("click", function (list) {
    $(".gride.width_100").slideUp();
    $(".list.width_100").slideDown();
  });

  $(".listing_type_gride").on("click", function (gride) {
    $(".list.width_100").slideUp();
    $(".gride.width_100").slideDown();
  });

  $(".panel-collapse").on("show.bs.collapse", function () {
    $(this).siblings(".panel-heading").addClass("active");
  });

  $(".panel-collapse").on("hide.bs.collapse", function () {
    $(this).siblings(".panel-heading").removeClass("active");
  });

  $(".filter_by_price").on("click", function (e) {
    e.preventDefault();

    var pricr_from = $(".price_from").val();
    var pricr_to = $(".price_to").val();
    pricr_from = pricr_from ? pricr_from : 1;
    pricr_to = pricr_to ? pricr_to : 999999;

    var url = new URL(window.location);
    url.searchParams.set("price-range", pricr_from + "-" + pricr_to); // setting your param
    var newUrl = url.href;
    console.log(newUrl);
    window.location.href = newUrl;
  });

  $(".product_brnad").on("change", function (e) {
    e.preventDefault();
    var brands = $(this).val();
    
    if (brands) {
      
      var url = new URL(window.location);
      url.searchParams.set("brands", brands); // setting your param
      var newUrl = decodeURIComponent(url.href);
      console.log(newUrl);

      window.location.href = newUrl;
    }
  });

  $(".product_markets").on("change", function (e) {
    e.preventDefault();
    var markets = $(this).val();
    
    if (markets) {
      
      var url = new URL(window.location);
      url.searchParams.set("markets", markets); // setting your param
      var newUrl = decodeURIComponent(url.href);
      console.log(newUrl);
      window.location.href = newUrl;
    }
  });

  $("#category_name").on("change", function (e) {
    e.preventDefault();
    var category_slug = $(this).val();
    var base_url = $("#base_url").val();
    if (category_slug && base_url) {
      var url = base_url + "search/" + category_slug;
      window.location.href = decodeURIComponent(url);
    }
  });

  $("#clear_all").on("click", function (e) {
    e.preventDefault();
    var category_slug = $("#category_name").val();
    var base_url = $("#base_url").val();
    if (category_slug && base_url) {
      var url = base_url + "search/" + category_slug;
      window.location.href = decodeURIComponent(url);
    }
  });

  $(".remove_url_paramiter").on("click", function (e) {
    e.preventDefault();
    var url_paramiter = $(this).data("url_paramiter");
    if (url_paramiter) {
      var url = new URL(window.location);
      url.searchParams.delete(url_paramiter);
      var newUrl = decodeURIComponent(url.href);
      window.location.href = newUrl;
    }
  });

  $(".field_range_btn").on("click", function (e) {
    e.preventDefault();
    var url_paramiter = $(this).data("field_slug");
    var parent_div = $(this).closest(".field_range");
    var range_from = $(parent_div).find(".range_from").val();
    var range_to = $(parent_div).find(".range_to").val();

    if (url_paramiter && range_from && range_to) {
      if (range_from > range_to) {
        $(parent_div).find(".range_to").val(range_from);
        range_to = range_from;
      }

      var url = new URL(window.location);
      url.searchParams.set(url_paramiter, range_from + "-" + range_to); // setting your param
      var newUrl = decodeURIComponent(url.href);
      window.location.href = newUrl;
    }
  });

  $(".field_dropdown").on("change", function (e) {
    var url_paramiter = $(this).data("field_slug");
    var field_value = $(this).val();
    if (url_paramiter && field_value) {
      var url = new URL(window.location);
      url.searchParams.set(url_paramiter, field_value); // setting your param
      var newUrl = decodeURIComponent(url.href);
      window.location.href = newUrl;
    }
  });

  $(".field_text").on("click", function (e) {
    e.preventDefault();
    var url_paramiter = $(this).data("field_slug");
    var parent_div = $(this).closest(".input_text_field");
    var field_value = $(parent_div).find(".input_text").val();

    if (url_paramiter && field_value) {
      var url = new URL(window.location);
      url.searchParams.set(url_paramiter, field_value); // setting your param
      var newUrl = decodeURIComponent(url.href);
      window.location.href = newUrl;
    }
  });

  $(".field_radio").on("click", function (e) {
    var url_paramiter = $(this).data("field_slug");
    var field_value = $(
      ".field_radio[name=" + url_paramiter + "]:checked"
    ).val();
    if (url_paramiter && field_value) {
      var url = new URL(window.location);
      url.searchParams.set(url_paramiter, field_value); // setting your param
      var newUrl = decodeURIComponent(url.href);
      window.location.href = newUrl;
    }
  });

  $(".field_checkbox").on("click", function (e) {
    var url_paramiter = $(this).data("field_slug");
    var field_value = $(
      ".field_checkbox[name=" + url_paramiter + "]:checked"
    ).val();
    var checkedfield = [];
    $(".field_checkbox:checkbox[name=" + url_paramiter + "]:checked").each(
      function () {
        checkedfield.push($(this).val());
      }
    );

    if (url_paramiter && checkedfield) {
      var url = new URL(window.location);
      url.searchParams.set(url_paramiter, checkedfield); // setting your param
      var newUrl = decodeURIComponent(url.href);
      window.location.href = newUrl;
    }
  });

  $(".compare_product_link").on("click", function () {
    var base_url = $("#main_base_url").val();
    var product_slug = $(this).data("product_slug");
    var product_category = $(this).data("product_category");

    if ($(this).hasClass("add_to_compare")) {
      if (product_slug && product_category) {
        remove_compare_product(product_slug, product_category, $(this));
      } else {
        alert("Invalid Try");
      }
    } else {
      if (product_slug && product_category) {
        add_compare_product(product_slug, product_category, $(this));
      } else {
        alert("Invalid Try");
      }
    }
  });

  function add_compare_product(product_slug, product_category, section) {
    var base_url = $("#main_base_url").val();
    $.ajax({
      type: "POST",
      url:
        base_url + "compare-product/" + product_slug + "/" + product_category,
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
              .addClass("add_to_compare");
            $(".filter_listing")
              .find(
                ".compare_product_link.list_compare_link." +
                  product_slug +
                  " i.fas"
              )
              .removeClass("fa-plus");
            $(".filter_listing")
              .find(
                ".compare_product_link.list_compare_link." +
                  product_slug +
                  " i.fas"
              )
              .addClass("fa-check");

            $(".comapre_products_nav .nav-link .badge-dark").text(
              response.compare_count
            );
            console.log(response.msg);
          } else {
            $(".comapre_products_nav .nav-link .badge-dark").text(
              response.compare_count
            );
            alert(response.msg);
            location.reload();
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

  function remove_compare_product(product_slug, product_category, section) {
    var base_url = $("#main_base_url").val();
    $.ajax({
      type: "POST",

      url:
        base_url +
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
            alert(response.msg);
            location.reload();
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

  $(".products_sorting").on("change", function (change) {
    change.preventDefault();
    var sort_by = $(".products_sorting").val();
    var base_url = $("#base_url").val();
    if (sort_by) {
      var url = new URL(window.location);
      url.searchParams.set("sort_by", sort_by); // setting your param
      var newUrl = decodeURIComponent(url.href);
      console.log(newUrl);
      window.location.href = newUrl;
    }
  });
})(jQuery);
