<?php
/**
 *
 * DateFormat class
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace YiiRestTools\Common\Enum;
/**
 *
 * Has some constants regarding date formats
 *
 * @author Antonio Ramirez <ramirez.cobos@gmail.com>
 * @package Yiinitializr.Common.Enum
 * @since 1.0
 */
class DateFormat
{
	const ISO8601    = 'Ymd\THis\Z';
	const ISO8601_S3 = 'Y-m-d\TH:i:s\Z';
	const RFC1123    = 'D, d M Y H:i:s \G\M\T';
	const RFC2822    = \DateTime::RFC2822;
	const SHORT      = 'Ymd';
}