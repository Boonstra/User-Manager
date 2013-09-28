<?php if ($data instanceof stdClass): ?>

	<?php

	$editFieldKey = htmlspecialchars($data->editFieldKey);

	$type = $label = $description = $value = "";

	if (isset($data->editField["type"]))
	{
		$type = htmlspecialchars($data->editField["type"]);
	}
	if (isset($data->editField["label"]))
	{
		$label = htmlspecialchars($data->editField["label"]);
	}
	if (isset($data->editField["description"]))
	{
		$description = htmlspecialchars($data->editField["description"]);
	}
	if (isset($data->editField["value"]))
	{
		$value = htmlspecialchars($data->editField["value"]);
	}

	?>

	<tr>
		<th><label for="<?php echo $editFieldKey; ?>"><?php echo $label; ?></label></th>
		<td>
			<input type="<?php echo $type; ?>" name="<?php echo $editFieldKey; ?>" value="<?php echo $value; ?>" />
			<?php if (strlen($description) > 0): ?>
				<span class="description"><?php echo $description; ?></span>
			<?php endif; ?>
		</td>
	</tr>
<?php endif; ?>