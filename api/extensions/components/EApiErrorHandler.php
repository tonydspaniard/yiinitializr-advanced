<?php
/**
 *
 * EApiErrorHandler class
 *
 * Utility class that handles the API errors just in case we wish to log them to a database.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class EApiErrorHandler extends CErrorHandler
{
	public function handle($event)
	{
		parent::handle($event);
	}

	protected function isAjaxRequest()
	{
		return false;
	}
}