<?php if ($data instanceof stdClass): ?>
	<h3><?php _e("Email Addresses", "user-manager-plugin"); ?></h3>

	<textarea rows="30" style="width: 100%;"><?php foreach ($data->users as $user)
	{
		echo $user->user_email . ";" . PHP_EOL;
	} ?></textarea>
<?php endif; ?>