<?php
/**
 * Config class file.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace Yiinitializr\Helpers;

use Yiinitializr\Helpers\ArrayX;

/**
 * Config provides easy access to Yiinitializr configuration file
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package Yiinitializr.helpers
 * @since 1.0
 */
class Config
{
	/**
	 * @var array the configuration settings
	 */
	private static $_settings;

	private static $_config_dir_path;
	private static $_envlock_file_path;

	/**
	 * Returns a value of the array
	 * @param $value
	 * @return mixed | null if no key is found
	 */
	public static function value($value)
	{
		return ArrayX::get(self::settings(), $value);
	}

	/**
	 * Reads the configuration settings from the file
	 * @return array|mixed
	 * @throws \Exception
	 */
	public static function settings()
	{
		if (null === self::$_settings)
		{
			self::$_settings = file_exists(self::getConfigurationDirectoryPath() . '/settings.php')
				? require_once(self::getConfigurationDirectoryPath() . '/settings.php')
				: array();
			self::$_settings['envlock'] = file_exists(self::getEnvironmentLockFilePath());

		}
		if (empty(self::$_settings))
			throw new \Exception('Unable to find Yiinitialzr settings file!');

		return self::$_settings;
	}

	/**
	 * @param string $content
	 */
	public static function createEnvironmentLockFile($content = '')
	{
		umask(0);
		file_put_contents(self::getEnvironmentLockFilePath(), $content);
		@chmod(self::getEnvironmentLockFilePath(), 0644);
	}

	/**
	 * Returns the configuration directory path
	 * @return string
	 */
	public static function getConfigurationDirectoryPath()
	{
		if (null === self::$_config_dir_path)
			self::$_config_dir_path = dirname(__FILE__) . '/../config';
		return self::$_config_dir_path;
	}

	/**
	 * Returns the environment lock file path
	 * @return string
	 */
	public static function getEnvironmentLockFilePath()
	{
		if (null === self::$_envlock_file_path)
			self::$_envlock_file_path = self::getConfigurationDirectoryPath() . '/env.lock';
		return self::$_envlock_file_path;
	}
}