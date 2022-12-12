<?php
/**
 * Template for showing the OpenAi Options
 *
 * @package MartinCV_OpenAi_Post
 */

?>
<div class='martincv-openai-post__form'>
	<div class="martincv-openai-post__field">
		<label for="martincv-openai-post-words"><?php esc_html_e( 'Enter number of words', 'martincv-openai-post' ); ?></label>
		<input type="number" name="martincv_openai_post_words" min="1" id="martincv-openai-post-words" value="500">
	</div>
	<br>
	<div class="martincv-openai-post__cta">
		<input type="hidden" name="action" value="martincv_openai_generate_post">
		<input type="hidden" name="wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'ajax-martincv-openai-post-nonce' ) ); ?>">
		<button type="button" class="button button-primary"><?php esc_html_e( 'Generate Post', 'martincv-openai-post' ); ?></button>
	</div>
</div>
