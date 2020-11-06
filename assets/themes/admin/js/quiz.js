(function ($) {
  "use strict";

  //select2 tool jquery
  $(document).ready(function () {
    $(".select_dropdown").select2();
  });

  Dropzone.autoDiscover = false;
  $(function () {
    var myDropzone = $("#imageupload").dropzone({
      url: BASE_URL + "admin/quiz/dropzone-file",
      maxFilesize: 5,
      maxFiles: 5,
      renameFile: function (file) {
        var dt = new Date();
        var time = dt.getTime();
        return time + convertToSlug(file.name);
      },
      addRemoveLinks: true,
      dictResponseError: "Server not Configured",
      acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",
      timeout: 50000,

      removedfile: function (file) {
        var name = file.upload.filename;

        $.ajax({
          type: "POST",
          url: BASE_URL + "admin/quiz/dropzone-file-remove",
          data: {
            filename: name
          },

          success: function (data) {
            if (data) {
              data = JSON.parse(data);
              console.log("File has been successfully removed!!" + data);
              $('.featured_image_block :input[value="' + data + '"]').remove();
            } else {
              alert("error");
            }
          },
          error: function (e) {
            console.log(e);
          },
        });
        var fileRef;
        return (fileRef = file.previewElement) != null ?
          fileRef.parentNode.removeChild(file.previewElement) :
          void 0;
      },

      success: function (file, response) {
        response = JSON.parse(response);
        if (response.name) {
          if ($(".featured_image_input").length) {
            $(".featured_image_block")
              .last()
              .after(
                '<input type="hidden" name="featured_image[]" class="form-control featured_image_input" value="' +
                response.name +
                '">'
              );
          } else {
            $(".featured_image_block").append(
              '<input type="hidden" name="featured_image[]" class="form-control featured_image_input" value="' +
              response.name +
              '">'
            );
          }
        } else {
          alert("error");
        }
      },

      error: function (file, response) {
        return false;
      },

      init: function () {
        var self = this;
        // config
        self.options.addRemoveLinks = true;
        self.options.dictRemoveFile = "Delete";
        //New file added
        self.on("addedfile", function (file) {
          console.log("new file added ", file);
        });
        // Send file starts
        self.on("sending", function (file, xhr, formData) {
          formData.append([csrf_Name], csrf_Hash);
          console.log("upload started", file);
          $(".meter").show();
        });

        // File upload Progress
        self.on("totaluploadprogress", function (progress) {
          console.log("progress ", progress);
          $(".roller").width(progress + "%");
        });

        self.on("queuecomplete", function (progress) {
          $(".meter").delay(999).slideUp(999);
        });

        // On removing file
        self.on("removedfile", function (file) {
          console.log(file);
        });

        self.on("maxfilesexceeded", function (file) {
          // alert("No more files please!")
          alert("No more files please !");
          this.removeFile(file);
        });
      },
    });
  });

  function convertToSlug(Text) {
    return Text.toLowerCase().replace(/ /g, "-");
  }

  var table;
  var csrfName = $("#csrf_hash").val();
  var csrf_token = $("#csrf_token").val();

  $(document).ready(function () {
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
        url: BASE_URL + "admin/QuizController/quiz_list",
        type: "POST",
      },

      //Set column definition initialisation properties.
      columnDefs: [{
        targets: [0, 3], //first column / numbering column
        orderable: false, //set not orderable
      }, ],
    });

    //datatables
    var quiz_id = $(".quiz_id").val();
    var question_table;

    question_table = $("#table_question").DataTable({
      processing: true, //Feature control the processing indicator.
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [],
      ajax: {
        url: BASE_URL + "admin/quiz/question-list/" + quiz_id,
        type: "POST",
      },

      dom: "lBfrtip",
      buttons: ["copy", "csv", "excel", "pdf", "print"],
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "All"],
      ],
      lengthChange: true,

      //Set column definition initialisation properties.
      columnDefs: [{
        targets: [0], //first column / numbering column
        orderable: false, //set not orderable
      }, ],
    });
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

  $(".popup").on("click", function () {
    $("#imagepreview").attr("src", $(this).attr("src"));
    $("#imagemodal").modal("show");
  });

  $(".delete_featured_image").on("click", function (e) {
    e.preventDefault();

    var quiz_id = $(this).data("quiz_id");
    var featured_image_name = $(this).data("image_name");
    var img_box = $(this).closest(".col-1");

    if (quiz_id && featured_image_name) {
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
            $.ajax({
              dataType: "json",
              type: "post",
              data: {
                quiz_id: quiz_id,
                featured_image_name: featured_image_name,
              },
              url: BASE_URL + "admin/quiz/delete-image/" + quiz_id,
              success: function (response) {
                if (response) {
                  console.log("image Removed Success");
                  img_box.remove();
                } else {
                  console.log("error During Remove Image");
                }
              },
              error: function (jqXHR, status, err) {
                console.log(jqXHR);
              },
            });
          }
        }
      );
    } else {
      return false;
    }
  });

  $(document).ready(function () {
    $(".add-more").on('click',function () {
      var html = $(".copy_ticket_section").html();
      $(".after_ticket_section").append(html);
    });

    $(document).on("click", ".remove_block_btn", function () {
      $(this).parents(".copied_ticket_section").remove();
    });

    $(document).on("click", ".add-more_update", function () {
      var html = $(".copy_ticket_section").html();
      swal({
          title: are_you_sure,
          text: update_company_also,
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: yes_add_more_field,
        },
        function (isConfirm) {
          if (isConfirm == true) {
            $(".after_ticket_section").append(html);
          }
        }
      );
    });

    $(document).on("click", ".remove_block_btn_update", function () {
      var parent_div = $(this).parents(".copied_ticket_section");
      swal({
          title: are_you_sure,
          text: remove_from_company,
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: yes_remove_it,
        },
        function (isConfirm) {
          if (isConfirm == true) {
            parent_div.remove();
          }
        }
      );
    });
  });
})(jQuery);