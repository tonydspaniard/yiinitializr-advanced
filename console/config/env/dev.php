<?php
/**
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
return array(
	'modules' => array(
		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => 'yii',
			'ipFilters' => array('127.0.0.1','::1'),
		),
	),
	'components' => array(
		'db' => array(
			'connectionString' => '{DB_CONNECTION}',
			'username' => '{DB_USER}',
			'password' => '{DB_PASSWORD}',
			'enableProfiling' => YII_DEBUG,
			'enableParamLogging' => YII_DEBUG,
			'charset' => 'utf8',
		),
	),
	'params' => array(
		'yii.handleErrors'   => YII_DEBUG,
		'yii.debug' => YII_DEBUG,
		'yii.traceLevel' => 3,
	)
);