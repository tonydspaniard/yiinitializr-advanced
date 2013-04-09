<?php
/**
 *
 * Request class file.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace YiiRestTools\Helpers;

use YiiRestTools\Common\Enum\DateFormat;
use YiiRestTools\Common\Exception\InvalidArgumentException;

/**
 * Encapsulates the logic for getting the signature data for a RESTFul API call
 * YiiRestTools.Helpers
 */
class RequestData extends \CMap
{
	/**
	 * @var string The raw json policy
	 */
	protected $_jsonPolicy;

	/**
	 * @var array fills the
	 */
	protected $_jsonRequest;

	/**
	 * @var string The signature
	 */
	protected $_signature;

	public function __construct(array $options)
	{
		parent::__construct($options);
	}

	/**
	 * Analyzes the provided data and turns it into signature data
	 *
	 * @param string $secretKey
	 * @return $this
	 */
	public function prepareData($secretKey)
	{

		$options = self::fromConfig($this->toArray(), array(
			'ttd' => '+1 hour', // maximum of one hour call
		));

		// Format ttd option
		$ttd = $options['ttd'];
		$ttd = is_numeric($ttd) ? (int)$ttd : strtotime($ttd);
		$options->remove('ttd');

		// Save policy if passed in
		$rawPolicy = $options->itemAt('policy');
		$options->remove('policy');

		// Setup policy document - add expiration for check at the server
		$policy = array(
			'expiration' => gmdate(DateFormat::ISO8601_S3, $ttd),
		);
		$this->_jsonRequest['expiration'] = $policy['expiration'];

		// Add other options
		foreach ($options as $key => $value)
		{
			$value = (string)$value;
			if ($value[0] === '^')
			{
				$value = preg_replace('/\$\{(\w*)\}/', '', $value);
				$policy['conditions'][] = array('starts-with', '$' . $key, $value);
			} else
				$policy['conditions'][] = array($key => $value);

			$this->_jsonRequest[$key] = $value;
		}

		// Add policy
		$this->_jsonPolicy = $rawPolicy ? : json_encode($policy);
		$jsonPolicy64 = base64_encode($this->_jsonPolicy);

		// Add signature
		$this->_signature = base64_encode(hash_hmac(
			'sha1',
			$jsonPolicy64,
			$secretKey,
			true
		));
		$this->_jsonRequest['signature'] = $this->_signature;

		return $this;
	}

	/**
	 * Create a new collection from an array, validate the keys, and add default values where missing
	 *
	 * @param array $config   Configuration values to apply.
	 * @param array $defaults Default parameters
	 * @param array $required Required parameter names
	 *
	 * @return self
	 * @throws InvalidArgumentException if a parameter is missing
	 */
	public static function fromConfig(array $config = null, array $defaults = null, array $required = null)
	{
		$collection = new self($defaults);

		foreach ((array)$config as $key => $value)
		{
			$collection->add($key, $value);
		}

		foreach ((array)$required as $key)
		{
			if ($collection->contains($key) === false)
			{
				throw new InvalidArgumentException("Config must contain a '{$key}' key");
			}
		}

		return $collection;
	}

	/**
	 * Gets the raw JSON policy
	 *
	 * @return string
	 */
	public function getJsonPolicy()
	{
		return $this->_jsonPolicy;
	}

	/**
	 * Gets the signature
	 *
	 * @return string
	 */
	public function getSignature()
	{
		return $this->_signature;
	}

	/**
	 * Returns the JSON request payload
	 * @return string
	 */
	public function getJsonRequest()
	{
		return \CJSON::encode($this->_jsonRequest);
	}
}
