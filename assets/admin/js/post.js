(function ($) {
	$(function () {
		const $form = $(".martincv-openai-post__form");
		const $submitButton = $form.find("button");

		$submitButton.on("click", function (e) {
			e.preventDefault();

			const { select } = wp.data;
			const title = select("core/editor").getEditedPostAttribute("title");

			$.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
					post_title: title,
					words_number: $form
						.find("input[name=martincv_openai_post_words]")
						.val(),
					action: $form.find("input[name=action]").val(),
					__nonce: $form.find("input[name=wpnonce]").val(),
				},
				success: function (response) {
					let text = response.data.split("\n\n");

					text.forEach(function (p) {
						let newBlock = wp.blocks.createBlock("core/paragraph", {
							content: p,
						});
						wp.data.dispatch("core/block-editor").insertBlocks(newBlock);
					});
				},
				error: function (xhr, status, error) {
					alert(xhr.responseJSON.data);
				},
			});
		});
	});
})(jQuery);
