<?php
/**
 * Admin Settings Page Template
 *
 * @package    MartinCV_OpenAi_Post
 */

?>

<div class="wrap">
	<h1><?php esc_html_e( 'OpenAi Settings', 'martincv-openai-post' ); ?></h1>
	<hr>
	<form method="post" action="options.php">
		<?php settings_fields( 'martincv-openai-post-settings-group' ); ?>
		<?php do_settings_sections( 'martincv-openai-post-settings-group' ); ?>

		<div class="martincv-openai-post-post-types">
			<table>
				<tbody>
					<tr style="text-align: left;">
						<th>
							<?php esc_html_e( 'Enter OpenAi API Key', 'martincv-openai-post' ); ?><br>
							<a href="https://beta.openai.com/account/api-keys" target="_blank"><?php esc_html_e( 'Get API Key', 'martincv-openai-post' ); ?></a>
						</th>
						<td><input type="password" name="_martincv_openai_post[api_key]" value="<?php echo esc_attr( $martincv_openai_post['api_key'] ?? '' ); ?>" size="50"></td>
					</tr>
					<tr style="text-align: left;">
						<th>
							<?php esc_html_e( 'Temperature', 'martincv-openai-post' ); ?><br>
						</th>
						<td><input type="number" name="_martincv_openai_post[temperature]" value="<?php echo esc_attr( $martincv_openai_post['temperature'] ?? 0.7 ); ?>"  size="10" min="0.0" max="1.0" step="0.1"></td>
					</tr>
					<tr style="text-align: left;">
						<th>
							<?php esc_html_e( 'Max Length', 'martincv-openai-post' ); ?><br>
						</th>
						<td><input type="number" name="_martincv_openai_post[max_length]" value="<?php echo esc_attr( $martincv_openai_post['max_length'] ?? 256 ); ?>"  size="10" min="0.0" max="4000"></td>
					</tr>
					<tr style="text-align: left;">
						<th>
							<?php esc_html_e( 'Presence Penalty', 'martincv-openai-post' ); ?><br>
						</th>
						<td><input type="number" name="_martincv_openai_post[presence_penalty]" value="<?php echo esc_attr( $martincv_openai_post['presence_penalty'] ?? 0 ); ?>"  size="10" min="-2.0" max="2.0" step="0.1"></td>
					</tr>
					<tr style="text-align: left;">
						<th>
							<?php esc_html_e( 'Frequency Penalty', 'martincv-openai-post' ); ?><br>
						</th>
						<td><input type="number" name="_martincv_openai_post[frequency_penalty]" value="<?php echo esc_attr( $martincv_openai_post['frequency_penalty'] ?? 0 ); ?>"  size="10" min="-2.0" max="2.0" step="0.1"></td>
					</tr>
					<tr style="text-align: left;">
						<th>
							<?php esc_html_e( 'Best of', 'martincv-openai-post' ); ?><br>
						</th>
						<td><input type="number" name="_martincv_openai_post[best_of]" value="<?php echo esc_attr( $martincv_openai_post['best_of'] ?? 1 ); ?>"  size="10"></td>
					</tr>
				</tbody>
			</table>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
