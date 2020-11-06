(function ($) {
  "use strict";

  $(".submit_test").on("click", function (q) {
    q.preventDefault();
    var test_submit = "submit";
    $.ajax({
      type: "POST",
      url: BASE_URL + "test-submit-request",
      data: {
        test_submit: test_submit
      },

      success: function (data) {
        if (data) {
          data = JSON.parse(data);
          if (data.status == "success") {
            swal({
                title: are_you_sure,
                text: total_attemp + " " + data.attemp,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: yes_submit_now,
              },
              function (isConfirm) {
                if (isConfirm == true) {
                  var input = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "submit_test")
                    .val("submit_test");

                  $("#myform").append($(input));
                  $("#myform").submit();
                }
              }
            );
          } else {
            alert(data.msg);
          }
        } else {
          alert("Server Error");
        }
      },
      error: function (e) {
        console.log(e);
      },
    });
  });

  $(".answer_given").on("click", function (q) {
    var checked_or_not = $(".answer_input:checked").val();
    if (checked_or_not) {
      console.log(checked_or_not);
    } else {
      q.preventDefault(false);

      swal({
        title: "No answe given yet",
      });
    }
  });
  $(document).ready(function(){
    var not_attemp = $('.not-attemp').val();
    var correct = $('.correct').val();
    var wrong_answer = $('.wrong-answer').val();
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
          labels: ['Not Attemp', 'Right Answer', 'Wrong Answer'],
          datasets: [{
              label: '# of Question',
              data: [not_attemp, correct, wrong_answer,],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
        scales: {
              xAxes: [{
                  display: false
              }],
              yAxes: [{
                  display: false
              }]
          }
      }
    });
  });      

})(jQuery); 