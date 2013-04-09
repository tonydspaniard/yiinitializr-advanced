<?php
/**
 * EApiAccessControlFilter class
 *
 * Extends CAccessControlFilter to provide RESTFul API access
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
use YiiRestTools\Helpers\RequestData;
use Yiinitializr\Helpers\ArrayX;

class EApiAccessControlFilter extends CAccessControlFilter
{
	/**
	 * @var string the attribute name of the ApiUser to set the name of CWebUser
	 */
	public $attributeName = 'username';

	/**
	 * @var ApiUser initialized from the public api key used on the header
	 * @see api.key.name on
	 */
	protected $_user;

	/**
	 * @var array the list of rules
	 */
	private $_rules = array();

	/**
	 * @return array list of access rules.
	 */
	public function getRules()
	{
		return $this->_rules;
	}

	/**
	 * @param array $rules list of access rules.
	 */
	public function setRules($rules)
	{

		foreach ($rules as $rule)
		{
			if (is_array($rule) && isset($rule[0]))
			{
				$r = new EApiAccessRule;
				$r->allow = $rule[0] === 'allow';
				foreach (array_slice($rule, 1) as $name => $value)
				{
					if ($name === 'expression' || $name === 'roles' || $name === 'message' || $name === 'deniedCallback')
						$r->$name = $value;
					else
						$r->$name = array_map('strtolower', $value);
				}
				$this->_rules[] = $r;
			}
		}
	}

	/**
	 * Performs the pre-action filtering.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 * @return boolean whether the filtering process should continue and the action
	 * should be executed.
	 * @throws EApiError
	 */
	protected function preFilter($filterChain)
	{

		$app = Yii::app();
		$request = $app->getRequest();
		$user = $this->getUser();
		$verb = $request->getRequestType();
		$ip = $request->getUserHostAddress();

		foreach ($this->getRules() as $rule)
		{

			if (($allow = $rule->isRequestAllowed($user, $filterChain->controller, $filterChain->action, $ip, $verb)) > 0) // allowed
				break;
			elseif ($allow < 0) // denied
			{
				if (isset($rule->deniedCallback))
					call_user_func($rule->deniedCallback, $rule);
				else
					throw new EApiError(HHttp::ERROR_FORBIDDEN, $this->resolveErrorMessage($rule));

				return false;
			}

		}
		return true;
	}

	/**
	 * Performs the post-action filtering.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 */
	protected function postFilter($filterChain)
	{
		Yii::app()->getSession()->destroy();
	}

	/**
	 * Returns the
	 * @return mixed
	 * @throws EApiError
	 */
	protected function getUser()
	{

		if (null === $this->_user)
		{
			$apiKeyName = 'HTTP_' . Yii::app()->params['api.key.name'];

			if (!isset($_SERVER[$apiKeyName]) ||
				!($apiKey = trim($_SERVER[$apiKeyName])) ||
				!($this->_user = ApiUser::model()->findByAttributes(array(
					'api_key' => $apiKey
				)))
			)
			{
				throw new EApiError(
					HHttp::ERROR_UNAUTHORIZED,
					HHttp::getErrorMessage(HHttp::ERROR_UNAUTHORIZED)
				);
			}
		}

		Yii::app()->user->setId($this->_user->id);
		Yii::app()->user->setName($this->_user->{$this->attributeName});

		return $this->_user;
	}
}