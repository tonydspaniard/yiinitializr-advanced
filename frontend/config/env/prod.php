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
	'components' => array(
//		change it to suit your needs
//		'db' => array(
//			'connectionString' => '{DB_CONNECTION}',
//			'username' => '{DB_USER}',
//			'password' => '{DB_PASSWORD}',
//			'enableProfiling' => false,
//			'enableParamLogging' => false,
//			'charset' => 'utf8',
//		),
	),
	'params' => array(
		'yii.debug' => false,
		'yii.traceLevel' => 0,
		'yii.handleErrors'   => APP_CONFIG_NAME !== 'test',
	)
);