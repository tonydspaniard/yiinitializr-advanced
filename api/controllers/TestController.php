<?php
/**
 *
 * TestController class
 *
 * Api demo controller
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class TestController extends EApiController
{
	public function actionIndex()
	{
		// just drop API request :)
		$this->renderJson($this->getJsonInputAsArray());
	}
	public function actionView()
	{
		$this->renderJson($this->getJsonInputAsArray());
	}
	public function actionCreate()
	{
		$this->renderJson($this->getJsonInputAsArray());
	}
	public function actionUpdate()
	{
		$this->renderJson($this->getJsonInputAsArray());
	}
	public function actionDelete()
	{
		$this->renderJson($this->getJsonInputAsArray());
	}

}
