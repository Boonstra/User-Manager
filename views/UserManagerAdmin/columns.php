<?php if ($data instanceof stdClass && (count($data->columns) + count($data->metaColumns)) > 0): ?>
	<h4><?php _e("Fields to export", "user-manager-plugin"); ?></h4>

	<table class="form-table">
		<tbody>
			<?php foreach ($data->columns as $data->columnKey => $data->column): ?>
				<?php $data->name = "columns[" . $data->columnKey . "]"; ?>
				<?php include $data->viewPath . "column.php"; ?>
			<?php endforeach; ?>

			<?php foreach ($data->metaColumns as $data->columnKey => $data->column): ?>
				<?php $data->name = "metaColumns[" . $data->columnKey . "]"; ?>
				<?php include $data->viewPath . "column.php"; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>