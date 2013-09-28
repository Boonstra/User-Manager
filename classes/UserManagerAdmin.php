<?php

/**
 * Class UserManagerAdmin
 */
class UserManagerAdmin
{
	protected $userManagerMain;

	/**
	 * Constructor
	 *
	 * @param UserManagerMain $userManagerMain
	 */
	public function __construct(UserManagerMain $userManagerMain)
	{
		if (!is_admin())
		{
			return;
		}

		$this->userManagerMain = $userManagerMain;

		add_action("admin_init", array($this, "exportUsers"));
		add_action("admin_menu", array($this, "registerMenu"));
	}

	/**
	 * Registers admin menu items
	 */
	public function registerMenu()
	{
		add_submenu_page(
			"users.php",
			__("Export Users", "user-manager-plugin"),
			__("Export Users", "user-manager-plugin"),
			"list_users",
			"export-users",
			array($this, "showUserExportPage")
		);

		add_submenu_page(
			"users.php",
			__("Email Addresses", "user-manager-plugin"),
			__("Email Addresses", "user-manager-plugin"),
			"list_users",
			"email_addresses",
			array($this, "showEmailAddressesPage")
		);
	}

	/**
	 * Outputs the export page.
	 */
	public function showUserExportPage()
	{
		$data              = new stdClass();
		$data->columns     = $this->getDefaultColumns();
		$data->metaColumns = $this->getDefaultMetaColumns();
		$data->viewPath    = $this->userManagerMain->getViewPath(__CLASS__);

		include $data->viewPath . "exportUsers.php";
	}

	/**
	 * Output the email addresses page
	 */
	public function showEmailAddressesPage()
	{
		$data           = new stdClass();
		$data->users    = get_users();
		$data->viewPath = $this->userManagerMain->getViewPath(__CLASS__);

		include $data->viewPath . "emailAddresses.php";
	}

	/**
	 * Export users when export action is requested.
	 */
	public function exportUsers()
	{
		if (empty($_POST) ||
			!wp_verify_nonce(filter_input(INPUT_POST, "user-manager_nonce", FILTER_SANITIZE_STRING), "user-manager_export"))
		{
			return;
		}

		$spreadsheetObject = new UserManagerSpreadsheetObject(__("User list", "user-manager-plugin") . " - " . date("Y-m-d h:i:s") . ".xls");

		$columns = $this->getDefaultColumns();

		if (isset($_POST['columns']) &&
			is_array($_POST['columns']))
		{
			foreach ($columns as $columnKey => $columnName)
			{
				if (!isset($_POST['columns'][$columnKey]))
				{
					unset($columns[$columnKey]);
				}
			}
		}

		$metaColumns = $this->getDefaultMetaColumns();

		if (isset($_POST['metaColumns']) &&
			is_array($_POST['metaColumns']))
		{
			foreach ($metaColumns as $metaColumnKey => $metaColumnName)
			{
				if (!isset($_POST['metaColumns'][$metaColumnKey]))
				{
					unset($metaColumns[$metaColumnKey]);
				}
			}
		}

		// Create header
		$columnID = 0;
		foreach (array_merge($columns, $metaColumns) as $columnHeader)
		{
			$spreadsheetObject->setCell(0, $columnID, $columnHeader);

			$columnID++;
		}

		$users = get_users();
		if (count($users) <= 0)
		{
			$spreadsheetObject->downloadFile();
		}

		// Loop through users as rows
		foreach ($users as $rowID => $user)
		{
			$columnID = 0;
			$userMeta = get_userdata($user->ID);

			// Loop through regular user columns
			foreach ($columns as $columnKey => $columnName)
			{
				if (isset($user->$columnKey))
				{
					$spreadsheetObject->setCell($rowID + 1, $columnID, $user->$columnKey);
				}

				$columnID++;
			}

			// Loop through the extra meta columns
			foreach ($metaColumns as $metaColumnKey => $metaColumnName)
			{
				if (isset($userMeta->$metaColumnKey))
				{
					$spreadsheetObject->setCell($rowID + 1, $columnID, $userMeta->$metaColumnKey);
				}

				$columnID++;
			}
		}

		$spreadsheetObject->downloadFile();
	}

	/**
	 * @return array $columns
	 */
	public function getDefaultColumns()
	{
		return array();
	}

	/**
	 * @return array $metaColumns
	 */
	public function getDefaultMetaColumns()
	{
		return array(
			"last_name"  => __("Last Name"      , "user-manager-plugin"),
			"first_name" => __("First Name"     , "user-manager-plugin"),
			"street1"    => __("Street"         , "user-manager-plugin"),
			"street2"    => __("Street Addition", "user-manager-plugin"),
			"zip"        => __("Zip/Postal Code", "user-manager-plugin"),
			"city"       => __("City"           , "user-manager-plugin"),
			"phone"      => __("Phone Number"   , "user-manager-plugin"),
			"user_email" => __("Email Address"  , "user-manager-plugin"),
			"gender"     => __("Gender"         , "user-manager-plugin"),
			"birthDate"  => __("Date Of Birth"  , "user-manager-plugin")
		);
	}
}