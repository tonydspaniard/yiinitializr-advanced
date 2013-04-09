<?php
/**
 * EApiAccessRule
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class EApiAccessRule extends CAccessRule
{
	/**
	 * Checks whether the Web user is allowed to perform the specified action.
	 * @param ApiUser $user the user object @see the model created
	 * @param CController $controller the controller currently being executed
	 * @param CAction $action the action to be performed
	 * @param string $ip the request IP address
	 * @param string $verb the request verb (GET, POST, etc.)
	 * @return integer 1 if the user is allowed, -1 if the user is denied, 0 if the rule does not apply to the user
	 */
	public function isRequestAllowed($user, $controller, $action, $ip, $verb)
	{

		if ($this->isActionMatched($action)
			&& $this->isUserMatched(Yii::app()->user)
			&& $this->isRoleMatched(Yii::app()->user)
			&& $this->isSignatureMatched($user)
			&& $this->isIpMatched($ip)
			&& $this->isVerbMatched($verb)
			&& $this->isControllerMatched($controller)
		)
		{
			return $this->allow ? 1 : -1;
		} else
			return 0;
	}

	/**
	 * Checks whether the policy and sig
	 * @param ApiUser $user
	 * @return bool
	 * @throws EApiError
	 */
	public function isSignatureMatched($user)
	{


		$requestArray = Yii::app()->getController()->getJsonInputAsArray();

		if (empty($requestArray))

			throw new EApiError(
				HHttp::ERROR_BADREQUEST,
				HHttp::getErrorMessage(HHttp::ERROR_BADREQUEST)
			);

		$signature = ArrayX::pop($requestArray, 'signature');
		$expires = ArrayX::pop($requestArray, 'expiration');

		if (!$signature || !$expires)
		{

			throw new EApiError(
				HHttp::ERROR_BADREQUEST,
				HHttp::getErrorMessage(HHttp::ERROR_BADREQUEST)
			);
		}

		// check time
		if (strtotime($expires) < time())
		{
			throw new EApiError(
				HHttp::ERROR_INTERNAL_504,
				HHttp::getErrorMessage(HHttp::ERROR_INTERNAL_504)
			);
		}
		// set back the expiration time to recreate the policy and make a handshake
		$requestArray['ttd'] = $expires;
		$requestData = new RequestData($requestArray);
		$requestData->prepareData($user->api_secret); // use secret to create signature

		return strcmp($requestData->getSignature(), $signature) === 0;
	}
}