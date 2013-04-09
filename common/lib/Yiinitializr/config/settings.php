<?php
/**
 * Yiinitializr configuration file.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
$dirname = dirname(__FILE__);
$root = $dirname . '/../../../..';

return array(
	'yii' => array(
		'path' => $root . '/common/lib/vendor/yiisoft/yii/framework'
	),
	'yiinitializr' => array(
		'config' => array(
			'console' => $dirname . '/console.php'
		),
		'app' => array(
			'root' => $root,
			'directories' => array(
				'config' => array(
					'frontend' => $root . '/frontend/config',
					'console' => $root . '/console/config',
					'backend' => $root . '/backend/config',
					'common' => $root . '/common/config',
					'api' => $root . '/api/config'
				),
				'runtime' => array(
					'frontend' => $root . '/frontend',
					'console' => $root . '/console',
					'backend' => $root . '/backend',
					'api' => $root . '/api'
				),
				'assets' => array(
					$root . '/frontend/www',
					$root . '/backend/www',
				)
			),
			'files' => array(
				// files to merge the main configuration file with
				'config' => array(
					'frontend' => array('env', 'local'),
					'console' => array('env', 'local'),
					'backend' => array('env', 'local'),
					'common' => array('env', 'local'),
					'api' => array('env', 'local')
				)
			)
		),
	)
);
