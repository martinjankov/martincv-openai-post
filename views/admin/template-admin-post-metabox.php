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
		<a href="javascript:void(0)" id="martincv-openai-settings-overwrite"><?php esc_html_e( 'Overwrite Global OpenAi Settings for this post', 'martincv-openai-post' ); ?></a>
	</div>
	<div class="martincv-openai-post-settings">
		<div class="martincv-openai-post__field">
			<label for="martincv-openai-post-max-length"><?php esc_html_e( 'Max Length', 'martincv-openai-post' ); ?></label>
			<input type="number" name="max_length" id="martincv-openai-post-max-length" min="0" max="4096" value="<?php echo esc_attr( $martincv_openai_post['max_length'] ?? 256 ); ?>">
		</div>
		<div class="martincv-openai-post__field">
			<label for="martincv-openai-post-temperature"><?php esc_html_e( 'Temperature', 'martincv-openai-post' ); ?></label>
			<input type="number" name="temperature" id="martincv-openai-post-temperature" min="0.0" max="1.0" step="0.1" value="<?php echo esc_attr( $martincv_openai_post['temperature'] ?? 0.7 ); ?>">
		</div>
		<div class="martincv-openai-post__field">
			<label for="martincv-openai-post-top-p"><?php esc_html_e( 'Top P', 'martincv-openai-post' ); ?></label>
			<input type="number" name="top_p" id="martincv-openai-post-top-p" min="0.0" max="1.0" step="0.1" value="<?php echo esc_attr( $martincv_openai_post['top_p'] ?? 1 ); ?>">
		</div>
		<div class="martincv-openai-post__field">
			<label for="martincv-openai-post-presence-penalty"><?php esc_html_e( 'Presence Length', 'martincv-openai-post' ); ?></label>
			<input type="number" name="presence_penalty" id="martincv-openai-post-presence-penalty" min="-2.0" max="2.0" step="0.1" value="<?php echo esc_attr( $martincv_openai_post['presence_penalty'] ?? 0 ); ?>">
		</div>
		<div class="martincv-openai-post__field">
			<label for="martincv-openai-post-frequency-penalty"><?php esc_html_e( 'Frequency Length', 'martincv-openai-post' ); ?></label>
			<input type="number" name="frequency_penalty" id="martincv-openai-post-frequency-penalty" min="-2.0" max="2.0" step="0.1" value="<?php echo esc_attr( $martincv_openai_post['frequency_penalty'] ?? 0 ); ?>">
		</div>
		<div class="martincv-openai-post__field">
			<label for="martincv-openai-post-best-of"><?php esc_html_e( 'Best of', 'martincv-openai-post' ); ?></label>
			<input type="number" name="best_of" id="martincv-openai-post-best-of" value="<?php echo esc_attr( $martincv_openai_post['best_of'] ?? 1 ); ?>" size="10">
		</div>
	</div>
	<br>
	<div class="martincv-openai-post__cta">
		<input type="hidden" name="action" value="martincv_openai_generate_post">
		<input type="hidden" name="__nonce" value="<?php echo esc_attr( wp_create_nonce( 'ajax-martincv-openai-post-nonce' ) ); ?>">
		<button type="button" class="button button-primary"><?php esc_html_e( 'Generate Post', 'martincv-openai-post' ); ?></button>
	</div>
</div>
