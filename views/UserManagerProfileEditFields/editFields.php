<?php if ($data instanceof stdClass): ?>
	<h3><?php _e("Additional Information", "user-manager-plugin"); ?></h3>

	<table class="form-table">
		<tbody>
			<?php if (count($data->editFields) > 0): ?>
				<?php foreach ($data->editFields as $data->editFieldKey => $data->editField): ?>
					<?php include $data->viewPath . "editField.php"; ?>
				<?php endforeach; ?>
			<?php else: ?>
				<?php _e("No additional information fields have been created yet.", "user-manager-plugin"); ?>
			<?php endif; ?>
		</tbody>
	</table>
<?php endif; ?>