(function ($) {
	$(function () {
		const $form = $("#martincv-openai-post__form");
		const $submitButton = $form.find("button");

		$submitButton.on("click", function (e) {
			e.preventDefault();

			let title = "";

			if (typeof wp.data == "undefined") {
				title = $("input[name=post_title]").val();
			} else {
				const { select } = wp.data;
				title = select("core/editor").getEditedPostAttribute("title");
			}

			const formData = new FormData();
			formData.append("post_title", title);

			$form.find("input").each(function () {
				if ($(this).attr("type") === "checkbox" && !$(this).is(":checked")) {
					return;
				}
				formData.append($(this).attr("name"), $(this).val());
			});

			$.ajax({
				url: ajaxurl,
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function () {
					$submitButton.prop("disabled", true);
					$submitButton.text("Processing...");
				},
				success: function (response) {
					let text = response.data.split("\n\n");

					if (typeof wp.data == "undefined") {
						let content = "";
						text.forEach(function (p) {
							content += p;
						});
						tinyMCE.activeEditor.setContent(content);
					} else {
						text.forEach(function (p) {
							let newBlock = wp.blocks.createBlock("core/paragraph", {
								content: p,
							});
							wp.data.dispatch("core/block-editor").insertBlocks(newBlock);
						});
					}

					$submitButton.prop("disabled", false);
					$submitButton.text("Generate Post");
				},
				error: function (xhr, status, error) {
					alert(xhr.responseJSON.data);
					$submitButton.prop("disabled", false);
					$submitButton.text("Generate Post");
				},
			});
		});
	});
})(jQuery);
