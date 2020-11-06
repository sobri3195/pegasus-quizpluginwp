(function ($) {
	"use strict";

	$(".d_none").hide();
	$(".page-list").on("click", function () {
		valuechange();
	});

	function valuechange() {
		if ($(".page-list").is(":checked")) {
			$(".all-page").show();
			$(".url").prop("disabled", true).val("");
		} else {
			$(".all-page").hide().val("-1");
			$(".url").prop("disabled", false);
		}
	}
})(jQuery);