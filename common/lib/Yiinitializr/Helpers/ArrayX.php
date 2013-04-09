<?php
/**
 * ArrayX class file.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @copyright 2013 2amigOS! Consultation Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace Yiinitializr\Helpers;

/**
 * ArrayX provides a set of useful functions
 *
 * @author Antonio Ramirez <ramirez.cobos@gmail.com>
 * @package Yiinitializr.helpers
 * @since 1.0
 */
class ArrayX
{
	/**
	 * Get an item from an array using "dot" notation.
	 *
	 * <code>
	 *        // Get the $array['user']['name'] value from the array
	 *        $name = ArrayX::get($array, 'user.name');
	 *
	 *        // Return a default from if the specified item doesn't exist
	 *        $name = ArrayX::get($array, 'user.name', 'Taylor');
	 * </code>
	 *
	 * @param  array $array
	 * @param  string $key
	 * @param  mixed $default
	 * @return mixed
	 */
	public static function get($array, $key, $default = null)
	{
		if (is_null($key)) return $array;

		// To retrieve the array item using dot syntax, we'll iterate through
		// each segment in the key and look for that value. If it exists, we
		// will return it, otherwise we will set the depth of the array and
		// look for the next segment.
		foreach (explode('.', $key) as $segment)
		{
			if (!is_array($array) || !array_key_exists($segment, $array))
			{
				return self::value($default);
			}

			$array = $array[$segment];
		}

		return $array;
	}

	/**
	 * Removes an item from the given options and returns the value.
	 *
	 * If no key is found, then default value will be returned.
	 *
	 * @param $array
	 * @param $key
	 * @param null $default
	 * @return mixed|null
	 */
	public static function pop(&$array, $key, $default = null)
	{
		if (is_array($array))
		{
			$value = self::get($array, $key, $default);
			unset($array[$key]);
			return $value;
		} else
			return $default;
	}

	/**
	 * Set an array item to a given value using "dot" notation.
	 *
	 * If no key is given to the method, the entire array will be replaced.
	 *
	 * <code>
	 *        // Set the $array['user']['name'] value on the array
	 *        ArrayX::set($array, 'user.name', 'Taylor');
	 *
	 *        // Set the $array['user']['name']['first'] value on the array
	 *        ArrayX::set($array, 'user.name.first', 'Michael');
	 * </code>
	 *
	 * @param  array $array
	 * @param  string $key
	 * @param  mixed $value
	 * @return void
	 */
	public static function set(&$array, $key, $value)
	{
		if (is_null($key)) return $array = $value;

		$keys = explode('.', $key);

		// This loop allows us to dig down into the array to a dynamic depth by
		// setting the array value for each level that we dig into. Once there
		// is one key left, we can fall out of the loop and set the value as
		// we should be at the proper depth.
		while (count($keys) > 1)
		{
			$key = array_shift($keys);

			// If the key doesn't exist at this depth, we will just create an
			// empty array to hold the next value, allowing us to create the
			// arrays to hold the final value.
			if (!isset($array[$key]) || !is_array($array[$key]))
				$array[$key] = array();

			$array =& $array[$key];
		}

		$array[array_shift($keys)] = $value;
	}

	/**
	 * Remove an array item from a given array using "dot" notation.
	 *
	 * <code>
	 *        // Remove the $array['user']['name'] item from the array
	 *        ArrayX::forget($array, 'user.name');
	 *
	 *        // Remove the $array['user']['name']['first'] item from the array
	 *        ArrayX::forget($array, 'user.name.first');
	 * </code>
	 *
	 * @param  array $array
	 * @param  string $key
	 * @return void
	 */
	public static function forget(&$array, $key)
	{
		$keys = explode('.', $key);

		// This loop functions very similarly to the loop in the "set" method.
		// We will iterate over the keys, setting the array value to the new
		// depth at each iteration. Once there is only one key left, we will
		// be at the proper depth in the array.
		while (count($keys) > 1)
		{
			$key = array_shift($keys);

			// Since this method is supposed to remove a value from the array,
			// if a value higher up in the chain doesn't exist, there is no
			// need to keep digging into the array, since it is impossible
			// for the final value to even exist.
			if (!isset($array[$key]) || !is_array($array[$key]))
				return;

			$array =& $array[$key];
		}

		unset($array[array_shift($keys)]);
	}

	/**
	 * Return the first element in an array which passes a given truth test.
	 *
	 * <code>
	 *        // Return the first array element that equals "Taylor"
	 *        $value = ArrayX::first($array, function($k, $v) {return $v == 'Taylor';});
	 *
	 *        // Return a default value if no matching element is found
	 *        $value = ArrayX::first($array, function($k, $v) {return $v == 'Taylor'}, 'Default');
	 * </code>
	 *
	 * @param  array $array
	 * @param  Closure $callback
	 * @param  mixed $default
	 * @return mixed
	 */
	public static function first($array, $callback, $default = null)
	{
		foreach ($array as $key => $value)
		{
			if (call_user_func($callback, $key, $value)) return $value;
		}

		return value($default);
	}

	/**
	 * Recursively remove slashes from array keys and values.
	 *
	 * @param  array $array
	 * @return array
	 */
	public static function stripSlashes($array)
	{
		$result = array();

		foreach ($array as $key => $value)
		{
			$key = stripslashes($key);

			// If the value is an array, we will just recurse back into the
			// function to keep stripping the slashes out of the array,
			// otherwise we will set the stripped value.
			if (is_array($value))
			{
				$result[$key] = array_strip_slashes($value);
			} else
			{
				$result[$key] = stripslashes($value);
			}
		}

		return $result;
	}

	/**
	 * Divide an array into two arrays. One with keys and the other with values.
	 *
	 * @param  array $array
	 * @return array
	 */
	public static function divide($array)
	{
		return array(array_keys($array), array_values($array));
	}

	/**
	 * Pluck an array of values from an array.
	 *
	 * @param  array $array
	 * @param  string $key
	 * @return array
	 */
	public static function pluck($array, $key)
	{
		return array_map(function ($v) use ($key)
		{
			return is_object($v) ? $v->$key : $v[$key];

		}, $array);
	}

	/**
	 * Get a subset of the items from the given array.
	 *
	 * @param  array $array
	 * @param  array $keys
	 * @return array
	 */
	public static function only($array, $keys)
	{
		return array_intersect_key($array, array_flip((array)$keys));
	}

	/**
	 * Get all of the given array except for a specified array of items.
	 *
	 * @param  array $array
	 * @param  array $keys
	 * @return array
	 */
	public static function except($array, $keys)
	{
		return array_diff_key($array, array_flip((array)$keys));
	}


	/**
	 * Return the first element of an array.
	 *
	 * This is simply a convenient wrapper around the "reset" method.
	 *
	 * @param  array $array
	 * @return mixed
	 */
	public static function head($array)
	{
		return reset($array);
	}

	/**
	 * Merges two or more arrays into one recursively.
	 * If each array has an element with the same string key value, the latter
	 * will overwrite the former (different from array_merge_recursive).
	 * Recursive merging will be conducted if both arrays have an element of array
	 * type and are having the same key.
	 * For integer-keyed elements, the elements from the latter array will
	 * be appended to the former array.
	 * @param array $a array to be merged to
	 * @param array $b array to be merged from. You can specifiy additional
	 * arrays via third argument, fourth argument etc.
	 * @return array the merged array (the original arrays are not changed.)
	 */
	public static function merge($a, $b)
	{
		$args = func_get_args();
		$res = array_shift($args);
		while (!empty($args))
		{
			$next = array_shift($args);
			foreach ($next as $k => $v)
			{
				if (is_integer($k))
					isset($res[$k]) ? $res[] = $v : $res[$k] = $v;
				elseif (is_array($v) && isset($res[$k]) && is_array($res[$k]))
					$res[$k] = self::merge($res[$k], $v); else
					$res[$k] = $v;
			}
		}
		return $res;
	}

	/**
	 * Searches for a given value in an array of arrays, objects and scalar
	 * values. You can optionally specify a field of the nested arrays and
	 * objects to search in.
	 *
	 * Credits to Util.php
	 *
	 * @param array $array  The array to search
	 * @param string $search The value to search for
	 * @param bool $field The field to search in, if not specified all fields will be searched
	 * @return bool|mixed|string False on failure or the array key on
	 * @link https://github.com/brandonwamboldt/utilphp/blob/master/util.php
	 */
	public static function deepSearch(array $array, $search, $field = FALSE)
	{
		// *grumbles* stupid PHP type system
		$search = (string)$search;

		foreach ($array as $key => $elem)
		{

			// *grumbles* stupid PHP type system
			$key = (string)$key;

			if ($field)
			{
				if (is_object($elem) && $elem->{$field} === $search)
				{
					return $key;
				} else if (is_array($elem) && $elem[$field] === $search)
				{
					return $key;
				} else if (is_scalar($elem) && $elem === $search)
				{
					return $key;
				}
			} else
			{
				if (is_object($elem))
				{
					$elem = (array)$elem;

					if (in_array($search, $elem))
					{
						return $key;
					}
				} else if (is_array($elem) && in_array($search, $elem))
				{
					return array_search($search, $elem);
				} else if (is_scalar($elem) && $elem === $search)
				{
					return $key;
				}
			}
		}

		return false;
	}

	/**
	 * Returns an array containing all the elements of arr1 after applying
	 * the callback function to each one.
	 *
	 * Credits to Util.php
	 *
	 * @param array $array an array to run through the callback function
	 * @param $callback Callback function to run for each element in each array
	 * @param bool $on_nonscalar whether or not to call the callback function on nonscalar values (objects, resr, etc)
	 * @return array
	 * @link https://github.com/brandonwamboldt/utilphp/blob/master/util.php
	 */
	public static function deepMap(array $array, $callback, $on_nonscalar = FALSE)
	{
		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$args = array($value, $callback, $on_nonscalar);
				$array[$key] = call_user_func_array(array(__CLASS__, __FUNCTION__), $args);
			} else if (is_scalar($value) || $on_nonscalar)
			{
				$array[$key] = call_user_func($callback, $value);
			}
		}

		return $array;
	}

	/**
	 * Return the value of the given item.
	 *
	 * If the given item is a Closure the result of the Closure will be returned.
	 *
	 * @param  mixed $value
	 * @return mixed
	 */
	public static function value($value)
	{
		return (is_callable($value) and !is_string($value)) ? call_user_func($value) : $value;
	}
}