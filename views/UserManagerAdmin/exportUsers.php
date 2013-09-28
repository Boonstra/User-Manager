<?php if ($data instanceof stdClass): ?>
	<h3><?php _e("Export Users", "user-manager-plugin"); ?></h3>

	<form method="post" action="">
		<?php include $data->viewPath . "columns.php"; ?>

		<?php wp_nonce_field("user-manager_export", "user-manager_nonce"); ?>

		<?php submit_button(__("Export Users", "user-manager-plugin")); ?>
	</form>
<?php endif; ?>