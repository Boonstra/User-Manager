<?php if ($data instanceof stdClass): ?>
	<tr>
		<td style="padding: 0 10px;">
			<input type="checkbox" checked="checked" id="<?php echo $data->name; ?>" name="<?php echo $data->name; ?>" value="1" />
			<label for="<?php echo $data->name; ?>"><?php echo $data->column; ?></label>
		</td>
	</tr>
<?php endif; ?>