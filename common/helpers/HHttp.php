<?php
/**
 * HHttp helper class
 *
 * Utility class to work with HTTP erroor classes.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class HHttp
{

	const REQUEST_OK = 200;
	const ERROR_BADREQUEST = 400;
	const ERROR_UNAUTHORIZED = 401;
	const ERROR_REQUESTFAILED = 402;
	const ERROR_FORBIDDEN = 403;
	const ERROR_NOTFOUND = 404;
	const ERROR_INVALIDREQUEST = 405;
	const ERROR_INTERNAL = 500;
	const ERROR_INTERNAL_500 = 500;
	const ERROR_INTERNAL_502 = 502;
	const ERROR_INTERNAL_503 = 503;
	const ERROR_INTERNAL_504 = 504;


	public static $httpStatusMessage = array(
		self::REQUEST_OK => "OK - Everything worked as expected.",
		self::ERROR_BADREQUEST => "Bad Request - Often missing a required parameter.",
		self::ERROR_UNAUTHORIZED => "Unauthorized - Invalid API key.",
		self::ERROR_REQUESTFAILED => "Request Failed - Parameters were valid but request failed.",
		self::ERROR_NOTFOUND => "Not Found - The requested item doesn't exist.",
		self::ERROR_INVALIDREQUEST => "Request type - Method not allowed.",
		self::ERROR_INTERNAL => "Server errors - something went wrong on Api's end.",
		self::ERROR_INTERNAL_500 => "Server errors - something went wrong on Api's end.",
		self::ERROR_INTERNAL_502 => "Server errors - something went wrong on Api's end.",
		self::ERROR_INTERNAL_503 => "Server errors - something went wrong on Api's end.",
		self::ERROR_INTERNAL_504 => "Server errors - gateway timeout.",
	);

	public static function sendHttpResponseCode($code)
	{
		switch ($code) {
			case 100: $text = 'Continue'; break;
			case 101: $text = 'Switching Protocols'; break;
			case 200: $text = 'OK'; break;
			case 201: $text = 'Created'; break;
			case 202: $text = 'Accepted'; break;
			case 203: $text = 'Non-Authoritative Information'; break;
			case 204: $text = 'No Content'; break;
			case 205: $text = 'Reset Content'; break;
			case 206: $text = 'Partial Content'; break;
			case 300: $text = 'Multiple Choices'; break;
			case 301: $text = 'Moved Permanently'; break;
			case 302: $text = 'Moved Temporarily'; break;
			case 303: $text = 'See Other'; break;
			case 304: $text = 'Not Modified'; break;
			case 305: $text = 'Use Proxy'; break;
			case 400: $text = 'Bad Request'; break;
			case 401: $text = 'Unauthorized'; break;
			case 402: $text = 'Payment Required'; break;
			case 403: $text = 'Forbidden'; break;
			case 404: $text = 'Not Found'; break;
			case 405: $text = 'Method Not Allowed'; break;
			case 406: $text = 'Not Acceptable'; break;
			case 407: $text = 'Proxy Authentication Required'; break;
			case 408: $text = 'Request Time-out'; break;
			case 409: $text = 'Conflict'; break;
			case 410: $text = 'Gone'; break;
			case 411: $text = 'Length Required'; break;
			case 412: $text = 'Precondition Failed'; break;
			case 413: $text = 'Request Entity Too Large'; break;
			case 414: $text = 'Request-URI Too Large'; break;
			case 415: $text = 'Unsupported Media Type'; break;
			case 500: $text = 'Internal Server Error'; break;
			case 501: $text = 'Not Implemented'; break;
			case 502: $text = 'Bad Gateway'; break;
			case 503: $text = 'Service Unavailable'; break;
			case 504: $text = 'Gateway Time-out'; break;
			case 505: $text = 'HTTP Version not supported'; break;
			default:
				throw new exception('Unknown http status code "' . htmlentities($code) . '"');
				break;
		}

		header('HTTP/1.1 ' . $code . ' ' . $text);
	}

	/*
	 * @params:Error code
	 * @return:The message corresponds to error code
	 */
	public static function getErrorMessage($errorCode)
	{
		return self::$httpStatusMessage[$errorCode];
	}
}
