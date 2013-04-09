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
 * @author Antonio Ramirez <ramirez.cobos@gmail.com>
 * @package Yiinitializr.helpers
 * @since 1.0
 */
class Config
{
	/**
	 * @var array the configuration settings
	 */
	private static $_settings;

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
		if(null === self::$_settings )
		{
			self::$_settings = file_exists( dirname(__FILE__) . '/../config/settings.php')
				? require_once( dirname(__FILE__) . '/../config/settings.php')
				: array();
		}
		if(empty(self::$_settings))
			throw new \Exception('Unable to find Yiinitialzr settings file!');

		return self::$_settings;
	}
}