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
		// configure to suit your needs
//		'db' => array(
//			'connectionString' => '{DB_CONNECTION}',
//			'username' => '{DB_USER}',
//			'password' => '{DB_PASSWORD}',
//			'enableProfiling' => true,
//			'enableParamLogging' => true,
//			'charset' => 'utf8',
//		),
	),
	'params' => array(
		'yii.handleErrors'   => true,
		'yii.debug' => true,
		'yii.traceLevel' => 3,
	)
);