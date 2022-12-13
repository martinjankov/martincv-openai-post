<?php
/**
 * Template for showing the OpenAi Options
 *
 * @package MartinCV_OpenAi_Post
 */

?>
<div class='martincv-openai-post__form' id="martincv-openai-post__form">
	<div class="martincv-openai-post__field">
		<label for="martincv-openai-post-images-number"><?php esc_html_e( 'Number of images', 'martincv-openai-post' ); ?></label>
		<input type="number" name="images_number" min="0" id="martincv-openai-post-images-number">
	</div>
	<div class="martincv-openai-post__field">
		<label for="martincv-openai-post-headings-number"><?php esc_html_e( 'Number of headings', 'martincv-openai-post' ); ?></label>
		<input type="number" name="headings_number" min="0" id="martincv-openai-post-headings-number">
	</div>
	<div class="martincv-openai-post__field">
		<label for="martincv-openai-post-introduction"><?php esc_html_e( 'Include introduction', 'martincv-openai-post' ); ?></label>
		<input type="checkbox" name="introduction" id="martincv-openai-post-introduction" value="1">
	</div>
	<div class="martincv-openai-post__field">
		<label for="martincv-openai-post-conclusion"><?php esc_html_e( 'Include conclusion', 'martincv-openai-post' ); ?></label>
		<input type="checkbox" name="conclusion" id="martincv-openai-post-conclusion" value="1">
	</div>
	<div class="martincv-openai-post__field">
		<a href="javascript:void(0)"><?php esc_html_e( 'Overwrite Global OpenAi Settings for this post', 'martincv-openai-post' ); ?></a>
	</div>
	<br>
	<div class="martincv-openai-post__cta">
		<input type="hidden" name="action" value="martincv_openai_generate_post">
		<input type="hidden" name="__nonce" value="<?php echo esc_attr( wp_create_nonce( 'ajax-martincv-openai-post-nonce' ) ); ?>">
		<button type="button" class="button button-primary"><?php esc_html_e( 'Generate Post', 'martincv-openai-post' ); ?></button>
	</div>
</div>
