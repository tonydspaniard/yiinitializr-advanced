<?php
/**
 * Initializer class file.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace Yiinitializr\Helpers;

use Yiinitializr\Helpers\Config;
use Yiinitializr\Helpers\ArrayX;
use Yiinitializr\Cli\Console;

/**
 * Initializer provides a set of useful functions to initialize a Yii Application development.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @package Yiinitializr.helpers
 * @since 1.0
 */
class Initializer
{

	/**
	 * @param $root
	 * @param string $configName
	 * @param mixed $mergeWith
	 * @return mixed
	 * @throws Exception
	 */
	public static function create($root, $configName = 'main', $mergeWith = array())
	{
		if (($root = realpath($root)) === false)
			throw new Exception('could not initialize framework.');

		$config = self::config($configName, $mergeWith);

		if (php_sapi_name() !== 'cli') // aren't we in console?
			$app = \Yii::createWebApplication($config); // create web
		else
		{
			defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
			$app = \Yii::createConsoleApplication($config);
			$app->commandRunner->addCommands($root . '/cli/commands');
			$env = @getenv('YII_CONSOLE_COMMANDS');
			if (!empty($env))
				$app->commandRunner->addCommands($env);
		}
		//  return an app
		return $app;
	}

	/**
	 * @param string $configName config name to load (main, test, etc)
	 * @param null|string $mergeWith
	 * @return array
	 * @throws \Exception
	 */
	public static function config($configName = 'main', $mergeWith = null)
	{
		$files = array($configName);
		$directory = Config::value('yiinitializr.app.directories.config.' . $configName);
		if (null === $directory)
			throw new \Exception("Unable to find 'yiinitializr.app.directories.config.'{$configName} on the settings.");

		if (null !== $mergeWith)
		{
			if (is_array($mergeWith))
			{
				foreach($mergeWith as $file)
					$files[] = $file;
			}
			else
				$files[] = $mergeWith;
		}

		// do we have any other configuration files to merge with?
		$mergedSettingFiles = Config::value('yiinitializr.app.files.config.' . $configName);
		if (null !== $mergedSettingFiles)
		{
			if (is_array($mergedSettingFiles))
			{
				foreach($mergedSettingFiles as $file)
					$files[] = $file;
			}
			else
				$files[] = $mergedSettingFiles;
		}

		$config = self::build($directory, $files);

		$params = isset($config['params'])
			? $config['params']
			: array();

		self::setOptions($params);

		return $config;
	}

	/**
	 * @param $directory
	 * @param $files  array of configuration files to merge
	 * @return array
	 */
	public static function build($directory, $files)
	{
		$result = array();
		if (!is_array($files))
			$files = array($files);

		foreach ($files as $file)
		{
			$config = file_exists($file) && is_file($file)
				? require($file)
				: (is_string($file) && file_exists($directory . '/' . $file . '.php')
					? require($directory . '/' . $file . '.php')
					: array());

			if (is_array($config))
				$result = ArrayX::merge($result, $config);
		}

		return $result;
	}

	/**
	 * Set php and yii options - some based on the loaded config params
	 * @param array $params The config params being used for the app
	 */
	protected static function setOptions(array $params)
	{
		// yii config
		defined('YII_DEBUG') or define('YII_DEBUG', isset($params['yii.debug']) ? $params['yii.debug'] : false);
		defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', isset($params['yii.traceLevel']) ? $params['yii.traceLevel'] : 0);
		defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', isset($params['yii.handleErrors']) ? $params['yii.handleErrors'] : true);
		defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', YII_ENABLE_ERROR_HANDLER);

		// php config
		error_reporting(-1);
		if(isset($params['php.defaultCharset']))
			ini_set('default_charset', $params['php.defaultCharset']);
		if(isset($params['php.timezone']))
			date_default_timezone_set($params['php.timezone']);

		date_default_timezone_set($params['php.timezone']);

		if(!class_exists('YiiBase'))
			require(Config::value('yii.path').'/yii.php');
	}

	/**
	 *  Helper function to build environment files
	 * @param $environment
	 * @throws \Exception
	 */
	public static function buildEnvironmentFiles($environment = 'dev')
	{
		self::output("\n%gBuilding environment files.%n");

		umask(0);
		$directories = Config::value('yiinitializr.app.directories.config');
		if (null === $directories)
			throw new \Exception("Unable to find 'yiinitializr.app.directories.config' on the settings.");

		if (!is_array($directories))
			$directories = array($directories);

		$environment = strlen($environment)
			? $environment
			: 'dev';

		foreach ($directories as $directory)
		{
			if (file_exists($directory))
			{
				$environment_directory = $directory . '/env';
				if (!file_exists($environment_directory))
				{
					mkdir($environment_directory);

					self::output("Your environment directory has been created: %r{$environment_directory}%n.\n");
				}

				$environment_file = $environment_directory . '/' . $environment . '.php';

				if (!file_exists($environment_file))
				{
					file_put_contents($environment_file,  "<?php\n/**\n * {$environment}.php\n */\n\nreturn array(\n);");
					@chmod($environment_file, 0644);

					self::output("%gEnvironment configuration file has been created: %r{$environment_file}%n.\n");
				}
				if (!file_exists($directory . '/env.php'))
				{
					@copy($environment_file, $directory . '/env.php');

					self::output("Your environment configuration file has been created on {$directory}.\n");
				} else
					self::output("'{$directory}/env.php' \n%pfile already exists. No action has been executed.%n");
			}
		}
		Config::createEnvironmentLockFile($environment);
		self::output("%gEnvironment files creation process finished.%n\n");
	}

	/**
	 * @param string $name the name of the runtime folder,
	 * @throws \Exception
	 */
	public static function createRuntimeFolders($name = 'runtime')
	{
		self::output("\n%gBuilding runtime '{$name}' folders.%n");
		umask(0);
		$directories = Config::value('yiinitializr.app.directories.' . $name);

		if (null === $directories)
			throw new \Exception("Unable to find 'yiinitializr.app.directories.{$name}' on the settings.");

		if (!is_array($directories))
			$directories = array($directories);

		foreach ($directories as $directory)
		{
			$runtime = $directory . '/' . $name;
			if (!file_exists($runtime))
			{
				@mkdir($runtime, 02777);
				self::output("Your {$name} folder has been created on {$directory}.");
			} else
				self::output("'{$name}'\n%pfolder already exists. No action has been executed.%n");
		}
		self::output("\n%gRuntime '{$name}'' folders creation process finished.%n");
	}

	/**
	 * Outputs text only to console
	 * @param $message
	 */
	protected static function output($message)
	{
		if (php_sapi_name() === 'cli')
			Console::output($message);
	}
}
