(function ($) {
	"use strict";

	$(document).ready(function () {

		$(".like-quiz i").on("click", function (e) {
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
		/* 1. Visualizing things on Hover - See next part for action on click */
		$("#stars li").on("mouseover", function () {
			var onStar = parseInt($(this).data("value"), 10); // The star currently mouse on

			// Now highlight all the stars that's not after the current hovered star
			$(this)
				.parent()
				.children("li.star")
				.each(function (e) {
					if (e < onStar) {
						$(this).addClass("hover");
					} else {
						$(this).removeClass("hover");
					}
				});
		}).on("mouseout", function () {
			$(this)
				.parent()
				.children("li.star")
				.each(function (e) {
					$(this).removeClass("hover");
				});
		});

		/* 2. Action to perform on click */
		$("#stars li").on("click", function () {

			var onStar = parseInt($(this).data("value")); // The star currently selected
			var stars = $(this).parent().children("li.star");
			var hidd = $('.rate').val(onStar);

			for (var i = 0; i < stars.length; i++) {

				$(stars[i]).removeClass("selected");

			}

			for (var i = 0; i < onStar; i++) {
				$(stars[i]).addClass("selected");
			}

			// JUST RESPONSE (Not needed)
			var ratingValue = parseInt(
				$("#stars li.selected").last().data("value"),
				10
			);
			var msg = "";
			if (ratingValue > 1) {
				msg = "Thanks! You rated this " + ratingValue + " stars.";
			} else {
				msg =
					"We will improve ourselves. You rated this " + ratingValue + " stars.";
			}
			responseMessage(msg);
		});
	});

	function responseMessage(msg) {
		$(".success-box").fadeIn(200);
		$(".success-box div.text-message").html("<span>" + msg + "</span>");
	}


})(jQuery);