<?php

class UserManagerProfileEditFields
{
	protected $userManagerMain;

	public function __construct(UserManagerMain $userManagerMain)
	{
		$this->userManagerMain = $userManagerMain;

		add_action('user_new_form', array($this, 'addAddFields'));

		add_action('user_register', array($this, 'saveEditFields'));

		add_action("show_user_profile", array($this, "addEditFields"));
		add_action("edit_user_profile", array($this, "addEditFields"));

		add_action("personal_options_update", array($this, "saveEditFields"));
		add_action("edit_user_profile_update", array($this, "saveEditFields"));

		add_filter('show_password_fields', array($this, 'hidePasswordFieldOnCreateUser'));
	}

	/**
	 * @return bool
	 */
	public function hidePasswordFieldOnCreateUser()
	{
		global $pagenow;

		if ($pagenow === 'user-new.php')
		{
			return false;
		}

		return true;
	}

	/**
	 * Adds the additional fields to the create a new user page.
	 */
	public function addAddFields()
	{
		$data             = new stdClass();
		$data->editFields = self::getEditFields(false);
		$data->viewPath   = $this->userManagerMain->getViewPath(__CLASS__);

		include $data->viewPath . "editFields.php";
	}

	/**
	 * Adds the additional fields to the user's profile page.
	 *
	 * @param WP_User $user
	 */
	public function addEditFields($user)
	{
		$data             = new stdClass();
		$data->editFields = self::getEditFields(true, $user->ID);
		$data->viewPath   = $this->userManagerMain->getViewPath(__CLASS__);

		include $data->viewPath . "editFields.php";
	}

	/**
	 * Store new user information from the additional fields.
	 *
	 * @param int $userID
	 *
	 * @return bool $success
	 */
	public function saveEditFields($userID)
	{
		if (!current_user_can("edit_user", $userID))
		{
			return false;
		}

		$editFields = self::getEditFields(true, $userID);

		foreach ($editFields as $editFieldKey => $editField)
		{
			$previousValue = "";
			$value         = "";

			if (isset($editField["value"]))
			{
				$previousValue = $editField["value"];
			}

			if (isset($_POST[$editFieldKey]))
			{
				$value = $_POST[$editFieldKey];
			}

			update_user_meta($userID, $editFieldKey, $value, $previousValue);
		}

		return true;
	}

	/**
	 * Returns the edit fields.
	 *
	 * TODO Store $editFields in the database, so editable fields can be adjusted. At the moment this is not necessary.
	 *
	 * @param bool $getWithUserData Optional, defaults to false.
	 * @param int  $userID          Optional, defaults to null. Required if $getWithUserData is true.
	 *
	 * @return array $editFields
	 */
	public static function getEditFields($getWithUserData = false, $userID = null)
	{
		if ($getWithUserData &&
			!is_numeric($userID))
		{
			$getWithUserData = false;
		}

		$editFields = array(
			"street1"   => array("type" => "text", "value" => "", "label" => __("Street"         , "user-manager-plugin")),
			"street2"   => array("type" => "text", "value" => "", "label" => __("Street Addition", "user-manager-plugin")),
			"zip"       => array("type" => "text", "value" => "", "label" => __("Zip/Postal Code", "user-manager-plugin")),
			"city"      => array("type" => "text", "value" => "", "label" => __("City"           , "user-manager-plugin")),
			"phone"     => array("type" => "text", "value" => "", "label" => __("Phone Number"   , "user-manager-plugin")),
			"gender"    => array("type" => "text", "value" => "", "label" => __("Gender"         , "user-manager-plugin"), "description" => __("'M' or 'F'", "user-manager-plugin")),
			"birthDate" => array("type" => "text", "value" => "", "label" => __("Date Of Birth"  , "user-manager-plugin"), "description" => __("DD-MM-YYYY", "user-manager-plugin"))
		);

		if (!$getWithUserData)
		{
			return $editFields;
		}

		foreach ($editFields as $editFieldKey => $editField)
		{
			$editFields[$editFieldKey]["value"] = get_user_meta($userID, $editFieldKey, true);
		}

		return $editFields;
	}
}