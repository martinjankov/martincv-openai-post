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
					<tr>
						<th><?php esc_html_e( 'Enter OpenAi API Key', 'martincv-openai-post' ); ?></th>
						<td><input type="password" name="_martincv_openai_post[api_key]" value="<?php echo esc_attr( $martincv_openai_post['api_key'] ?? '' ); ?>"></td>
					</tr>
				</tbody>
			</table>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
