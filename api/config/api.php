<?php
/**
 * api.php configuration file
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
return array(
	'basePath' => realPath(__DIR__ . '/..'),
    'aliases'    => array(
        'frontend'     => dirname(__FILE__) . '/../../frontend',
        'common'       => dirname(__FILE__) . '/../../common',
        'backend'      => dirname(__FILE__) . '/../../backend',
        'vendor'       => dirname(__FILE__) . '/../../common/lib/vendor',
        'YiiRestTools' => dirname(__FILE__) . '/../../common/lib/YiiRestTools',
        'Yiinitializr' => dirname(__FILE__) . '/../../common/lib/Yiinitializr'
    ),
    'import'     => array(
        'application.extensions.components.*',
        'application.extensions.behaviors.*',
        'application.extensions.filters.*',
    ),
	'components' => array(
		'errorHandler' => array(
			'errorAction' => 'site/error',
			'class' => 'EApiErrorHandler'
		),
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CDbLogRoute',
					'connectionID' => 'db',
					'levels' => 'error, warning',
				),
			),
		),
		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules' => array(
				// REST patterns
				array('<controller>/index', 	'pattern' => 'api/<controller:\w+>', 		'verb' => 'POST'),
				array('<controller>/view', 		'pattern' => 'api/<controller:\w+>/view', 	'verb' => 'POST'),
				array('<controller>/update', 	'pattern' => 'api/<controller:\w+>/update', 'verb' => 'PUT'),
				array('<controller>/delete', 	'pattern' => 'api/<controller:\w+>/delete', 'verb' => 'DELETE'),
				array('<controller>/create', 	'pattern' => 'api/<controller:\w+>/create', 'verb' => 'POST'),
			),
		)
	)
);