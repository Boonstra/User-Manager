<?php
/*
 Plugin Name: User Manager
 Plugin URI:
 Description:
 Version: 0.3.0
 Requires at least: 3.3
 Author: StefanBoonstra
 Author URI: http://stefanboonstra.com/
 License: GPLv2
*/

class UserManagerMain
{
	/**
	 * Setup plugin
	 */
	public function __construct()
	{
		$this->autoInclude();

		add_action("init", array($this, "localize"));

		new UserManagerAdmin($this);
		new UserManagerProfileEditFields($this);
	}

	/**
	 * Translates the plugin.
	 */
	public function localize()
	{
		load_plugin_textdomain(
			"user-manager-plugin",
			false,
			dirname(plugin_basename(__FILE__)) . '/languages/'
		);
	}

	/**
	 * Returns the url to the base directory of this plugin.
	 *
	 * @return string $pluginUrl
	 */
	public function getPluginUrl()
	{
		return plugins_url("", __FILE__) . "/";
	}

	/**
	 * Returns the path to the base directory of this plugin
	 *
	 * @return string $pluginPath
	 */
	public function getPluginPath()
	{
		return dirname(__FILE__) . DIRECTORY_SEPARATOR;
	}

	/**
	 * Returns the path to a directory with the passed directory name in the views directory
	 *
	 * @param string $directoryName
	 *
	 * @return string $viewPath
	 */
	public function getViewPath($directoryName)
	{
		$viewPath = $this->getPluginPath() . "views" . DIRECTORY_SEPARATOR;

		if (strlen($directoryName) <= 0)
		{
			return $viewPath;
		}

		return $viewPath . $directoryName . DIRECTORY_SEPARATOR;
	}

	/**
	 * This function will load classes automatically on-call.
	 */
	public function autoInclude()
	{
		if(!function_exists("spl_autoload_register"))
		{
			return;
		}

		function autoLoader($name)
		{
			$name = str_replace("\\", DIRECTORY_SEPARATOR, $name);
			$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . $name . ".php";

			if (is_file($file))
			{
				require_once $file;
			}
		}

		spl_autoload_register("autoLoader");
	}
}

new UserManagerMain();