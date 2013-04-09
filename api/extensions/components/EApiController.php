<?php
/**
 * EApiController class
 *
 * The base controller where all api controllers extend from
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class EApiController extends EController
{

	/**
	 * @var array formatted input data
	 */
	protected $jsonInputDataArray;

	protected $rawInputdata;

	/**
	 * @return array list of action filters (See CController::filter)
	 */
	public function filters()
	{
		// add the filter that will also check for api/signature handshake
		return array(
			array(
				'EApiAccessControlFilter -error',
				'rules' => array(
					array('allow', 'users' => array('@'))
				)
			)
		);
	}

	/**
	 * Initialize method. Attaches the error handlers
	 * @see EApiErrorBehavior
	 */
	public function init()
	{
		// override methods to make sure we handle api errors
		Yii::app()->attachEventHandler('onError', array($this, 'apiErrorHandler'));
		Yii::app()->attachEventHandler('onException', array($this, 'apiErrorHandler'));
	}

	/**
	 * Returns the json formattted data
	 * @param bool $required
	 * @return mixed
	 * @throws EApiError
	 */
	public function getJsonInputAsArray($required = false)
	{
		if (null === $this->jsonInputDataArray)
		{
			$rawInputData = $this->getRawInputData();

			if (Yii::app()->params['testmode'])
			{
				$rawInputData = $_SERVER['API-TEST-JSON-PAYLOAD'];
			}

			if ($rawInputData || $required)
			{
				if (!is_array($this->jsonInputDataArray = json_decode($rawInputData, true)))
				{
					throw new EApiError(
						HHttp::ERROR_BADREQUEST,
						Yii::t('api', 'Bad Request - Did not contain a valid JSON string')
					);
				}
			}
		}

		return $this->jsonInputDataArray;
	}

	/**
	 * Returns the raw input data
	 * @return string
	 */
	public function getRawInputData()
	{
		if (null === $this->rawInputdata)
		{
			$this->rawInputdata = file_get_contents("php://input");
		}
		return $this->rawInputdata;
	}

	/**
	 * Error handler, when there is an error this will fire
	 * @param CEvent $event
	 */
	public function apiErrorHandler(CEvent $event)
	{
		$event->handled = true;
		$debug = Yii::app()->params['yii.debug'];
		$response = array();
		if ($event instanceof CExceptionEvent)
		{
			if ($event->exception instanceof EApiError)
			{
				$response['code'] = $event->exception->statusCode;
				$response['message'] = $event->exception->getMessage();
			} else
			{
				$response['code'] = isset($event->exception->statusCode)
					? $event->exception->statusCode
					: $event->exception->getCode();
				$response['message'] = $event->exception->getMessage();
				if ($debug)
					$response['traceback'] = $event->exception->getTrace();
			}
		} else if ($event instanceof CErrorEvent)
		{
			$response['code'] = 500;
			$response['message'] = $event->message;

			if ($debug)
				$response['traceback'] = debug_backtrace();
		}

		if (!$response['code'])
			$response['code'] = 500;

		try
		{
			HHttp::sendHttpResponseCode($response['code']);
		} catch (exception $e)
		{
			$response['code'] = 500;
			HHttp::sendHttpResponseCode($response['code']);
		}

		$this->renderJson($response);

		Yii::app()->end();
	}
}