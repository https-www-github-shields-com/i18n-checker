<?php 

class Utils {
	
	private function __construct() {}
	
	public static function contentTypeToArray($contentType) {
		if ($contentType == null)
			return array ('mimetype' => null, 'charset' => null);
		$matches = explode(';', trim($contentType));
		if (isset($matches[1])) {
			$matches[1] = explode('=', $matches[1]);
			$matches[1] = isset($matches[1][1]) && trim($matches[1][1])
				? $matches[1][1]
				: $matches[1][0];
		} else
			$matches[1] = null;
		return array ('mimetype' => $matches[0], 'charset' => $matches[1]);
	}
	
	public static function charsetFromContentType($contentType) {
		$ct = Utils::contentTypeToArray($contentType);
		return $ct['charset'];
	}
	
	public static function mimeFromContentType($contentType) {
		$ct = Utils::contentTypeToArray($contentType);
		return $ct['mimetype'];
	}
	
	// Adds the values contained in the comma-separated list $string (typically a list of languages) to $array
	public static function arrayMergeCommaString(array $array = null, $string) {
		return array_merge((array) $array, array_map('trim', preg_split('/,/', $string)));
	}
	
	// Returns an array of values from a comma-separated string of values
	public static function getValuesFromCSString($string) {
		return array_map('trim', preg_split('/,/', $string));
	}
	
	public static function arrayToCS($array) {
		if (!is_array($array))
			return null;
		$result = '';
		foreach ($array as $val) {
			if (is_array($val))
				continue;
			$result .= $val.',';
		}
		return preg_replace('/,$/', '', $result);
		
	}
	
	public static function valuesFromValArray($array) {
		if ($array == null || !is_array($array))
			return null;
		$result = array();
		foreach ($array as $valArr) {
			if (array_key_exists('values', $valArr) && $valArr['values'] != null) // TODO: if not then an invalid array has been passed, log?
				$result[] = $valArr['values'];
		}
		return array_values(self::arrayFlatten($result));
	}
	
	public static function codesFromValArray($array) {
		if ($array == null || !is_array($array))
			return null;
		$result = array();
		foreach ($array as $valArr) {
			if (array_key_exists('code', $valArr) && $valArr['code'] != null) // TODO: if not then an invalid array has been passed, log?
				$result[] = $valArr['code'];
		}
		return array_values(self::arrayFlatten($result));
	}
	
	/*
	 public static function codesFromValArray($array) {
		if (!is_array($array))
			return null;
		$result = array();
		foreach ($array as $element) {
			array_merge($result, $element);
		}
		return $result;
	}
	 */
	
	public static function arrayTrim(array $array) {
		return array_map('trim', $array);
	}
	
	public static function arrayFlatten(array $array, $nbPass = 1) {
		$result = array();
		if ($nbPass <= 0)
			return $array;
		foreach ($array as $key => $value) {
			if (is_array($value))
				foreach ($value as $valKey => $valVal) 
					$result[] = $valVal;
			else 
				$result[] = $value;
		}
		return self::arrayFlatten($result, $nbPass-1);
	}
	
	public static function boolString($bValue) {
		return ($bValue ? 'true' : 'false');
	}
	
	// return an array of accepted languages/charsets from the Accept-Language and Accept-Charset HTTP headers 
	public static function parseHeader($header) {
		foreach (preg_split('/,/', $header) as $value) {
			$a = preg_split('/;/', $value);
			$result[] = trim($a[0]);
		}
		return $result;
	}
	
	public static function isASCII($string) {
		return preg_match('/^[\x20-\x7E]*$/', $string) == true;
	}
	
	// returns all elements in array1 that or not in array2
	public static function diffArray($array1, $array2) {
		if (!is_array($array1))
			return null;
		if (!is_array($array2))
			return $array1;
		foreach($array1 as $val) {
			if (!in_array($val, $array2))
				$result[] = $val;
		}
		return isset($result) ? $result : null;
	}
	
	public static function findCodeIn($code, $array) {
		if (!is_array($array))
			return null;
		foreach($array as $val) {
			if ($val['code'] == $code)
				return $val;
		}
	}
	
}