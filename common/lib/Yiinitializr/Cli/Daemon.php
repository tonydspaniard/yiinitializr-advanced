<?php
/**
 * Daemon class file.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @author Nofriandi Ramenta <nramenta@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @link https://github.com/nramenta/clio
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace Yiinitializr\Cli;
/**
 * Daemon  provides helpers for starting and killing daemonized processes
 *
 * @author Antonio Ramirez <ramirez.cobos@gmail.com>
 * @package Yiinitializr.Cli
 * @since 1.0
 */
class Daemon
{
	/**
	 * Daemonize a Closure object.
	 * @param array $options Set of options
	 * @param callable $callable Closure object to daemonize
	 * @return bool True on success
	 * @throws \Exception
	 */
	public static function work(array $options, \Closure $callable)
	{
		if (!extension_loaded('pcntl')) {
			throw new \Exception('pcntl extension required');
		}

		if (!extension_loaded('posix')) {
			throw new \Exception('posix extension required');
		}

		if (!isset($options['pid'])) {
			throw new \Exception('pid not specified');
		}

		$options = $options + array(
			'stdin'  => '/dev/null',
			'stdout' => '/dev/null',
			'stderr' => 'php://stdout',
		);

		if (($lock = @fopen($options['pid'], 'c+')) === false) {
			throw new \Exception('unable to open pid file ' . $options['pid']);
		}

		if (!flock($lock, LOCK_EX | LOCK_NB)) {
			throw new \Exception('could not acquire lock for ' . $options['pid']);
		}

		switch ($pid = pcntl_fork()) {
			case -1:
				throw new \Exception('unable to fork');
			case 0:
				break;
			default:
				fseek($lock, 0);
				ftruncate($lock, 0);
				fwrite($lock ,$pid);
				fflush($lock);
				return true;
		}

		if (posix_setsid() === -1) {
			throw new \Exception('failed to setsid');
		}

		fclose(STDIN);
		fclose(STDOUT);
		fclose(STDERR);

		if (!($stdin  = fopen($options['stdin'], 'r'))) {
			throw new \Exception('failed to open STDIN ' . $options['stdin']);
		}

		if (!($stdout = fopen($options['stdout'], 'w'))) {
			throw new \Exception('failed to open STDOUT ' . $options['stdout']);
		}

		if (!($stderr = fopen($options['stderr'], 'w'))) {
			throw new \Exception('failed to open STDERR ' . $options['stderr']);
		}

		pcntl_signal(SIGTSTP, SIG_IGN);
		pcntl_signal(SIGTTOU, SIG_IGN);
		pcntl_signal(SIGTTIN, SIG_IGN);
		pcntl_signal(SIGHUP,  SIG_IGN);

		call_user_func($callable, $stdin, $stdout, $stderr);
	}

	/**
	 * Whether a process is running or not
	 * @param $file
	 * @return bool
	 * @throws \Exception
	 */
	public static function isRunning($file)
	{
		if (!extension_loaded('posix')) {
			throw new \Exception('posix extension required');
		}

		if (!is_readable($file)) {
			return false;
		}

		if (($lock = @fopen($file, 'c+')) === false) {
			throw new \Exception('unable to open pid file ' . $file);
		}

		if (flock($lock, LOCK_EX | LOCK_NB)) {
			return false;
		} else {
			flock($lock, LOCK_UN);
			return true;
		}
	}

	/**
	 * Kills a daemon process specified by its PID file.
	 *
	 * @param $file  Daemon PID file
	 * @param bool $delete Flag to delete PID file after killing
	 * @return bool  True on success, false otherwise
	 * @throws \Exception
	 */
	public static function kill($file, $delete = false)
	{
		if (!extension_loaded('posix')) {
			throw new \Exception('posix extension required');
		}

		if (!is_readable($file)) {
			throw new \Exception('unreadable pid file ' . $file);
		}

		if (($lock = @fopen($file, 'c+')) === false) {
			throw new \Exception('unable to open pid file ' . $file);
		}

		if (flock($lock, LOCK_EX | LOCK_NB)) {
			flock($lock, LOCK_UN);
			throw new \Exception('process not running');
		}

		$pid = fgets($lock);

		if (posix_kill($pid, SIGTERM)) {
			if ($delete) unlink($file);
			return true;
		} else {
			return false;
		}
	}
}
