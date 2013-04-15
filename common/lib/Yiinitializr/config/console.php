<?php
/**
 * Custom console.php config file.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
/**
 * Include required classes
 */
require_once dirname(__FILE__) . '/../Helpers/Initializer.php';
require_once dirname(__FILE__) . '/../Helpers/ArrayX.php';

/**
 * Return the configuration array appending composer callback methods
 */
return Yiinitializr\Helpers\ArrayX::merge(
	Yiinitializr\Helpers\Initializer::config('console', array(
		dirname(__FILE__) . '/../../../config/main.php',
		dirname(__FILE__) . '/../../../config/env.php',
		dirname(__FILE__) . '/../../../config/local.php')
	),
	array(
		'params' => array(
			'composer.callbacks' => array(
				'post-update' => array('yiic', 'migrate'),
				'post-install' => array('yiic', 'migrate'),
			)
		),
	)
);
