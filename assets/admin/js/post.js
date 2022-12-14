(function ($) {
	$(function () {
		const $form = $("#martincv-openai-post__form");
		const $submitButton = $form.find("button");
		const $imagesNumberField = $form.find("input[name=images_number]");

		let formData = new FormData();

		let imageSizefieldAdded = false;

		const imageSizesField = `<div class="martincv-openai-post__field martincv-openai-post__image-size">
					<label for="martincv-openai-post__image-size">${martinCVOpenAiPost.selectImageLabel}</label><br>
					<select name="image_size" id="martincv-openai-post__image-size">
						<option value="256x256">256x256</option>
						<option value="512x512" selected>512x512</option>
						<option value="1024x1024">1024x1024</option>
					</select>
				</div>`;

		$imagesNumberField.on("input", function () {
			const value = $(this).val();

			if (value > 0 && !imageSizefieldAdded) {
				$imagesNumberField.parent().after(imageSizesField);
				imageSizefieldAdded = true;
				formData.append("image_size", "512x512");
			} else if (value <= 0) {
				$(document).find(".martincv-openai-post__image-size").remove();
				imageSizefieldAdded = false;
			}
		});

		$(document).on("change", "select[name=image_size]", function () {
			formData.delete("image_size");
			formData.append("image_size", $(this).val());
		});

		$submitButton.on("click", function (e) {
			e.preventDefault();

			let title = "";

			if (typeof wp.data == "undefined") {
				title = $("input[name=post_title]").val();
			} else {
				const { select } = wp.data;
				title = select("core/editor").getEditedPostAttribute("title");
			}

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
					if (!response.success) {
						alert(response.data);
						return;
					}
					let aiResponse = response.data.split("###images###");
					let text = aiResponse[0].split("\n\n");
					let images = aiResponse[1].split("|");
					const totalImages = images.length;
					const totalText = text.length;

					let addImageStep = 1;

					if (totalText - totalImages > 2) {
						addImageStep = 2;
					}

					let imageStep = 0;
					let imageIndex = 0;

					if (typeof wp.data == "undefined") {
						let content = "";
						text.forEach(function (p) {
							p = p.trim();

							if (p.length === 0) {
								return;
							}

							if (p.includes("<h3>")) {
								content += p;
							} else {
								content += "<p>" + p + "</p>";
							}

							if (
								images.length &&
								imageStep % addImageStep === 0 &&
								typeof images[imageIndex] != "undefined" &&
								images[imageIndex].trim().length
							) {
								content += images[imageIndex];

								imageIndex++;
							}

							imageStep++;
						});
						if ($("#wp-content-wrap").hasClass("html-active")) {
							$("#content").val(content);
						} else {
							var activeEditor = tinyMCE.get("content");
							if (activeEditor !== null) {
								activeEditor.setContent(content);
							}
						}
					} else {
						let blocks = [];
						text.forEach(function (p) {
							p = p.trim();

							if (p.length === 0) {
								return;
							}
							let newBlock;
							if (p.includes("<h3>")) {
								newBlock = wp.blocks.createBlock("core/heading", {
									content: $(p).text(),
									level: 3,
								});
							} else {
								newBlock = wp.blocks.createBlock("core/paragraph", {
									content: p,
								});
							}

							blocks.push(newBlock);

							if (
								images.length &&
								imageStep % addImageStep === 0 &&
								typeof images[imageIndex] != "undefined" &&
								images[imageIndex].trim().length
							) {
								let imgBlock = wp.blocks.createBlock("core/image", {
									url: $(images[imageIndex]).attr("src"),
									alt: $(images[imageIndex]).attr("alt"),
								});

								blocks.push(imgBlock);

								imageIndex++;
							}

							imageStep++;
						});

						wp.data.dispatch("core/block-editor").insertBlocks(blocks);
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

		$("#martincv-openai-settings-overwrite").on("click", function (e) {
			e.preventDefault();
			$(".martincv-openai-post-settings").slideToggle();
		});
	});
})(jQuery);
