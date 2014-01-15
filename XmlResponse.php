<?php

/**
 * This class is based on the JsonResponse class by Weavora Team and code from various posters on http://snipplr.com/view/3491/
 * 
 * @author Fredrik Wollsén <fredrik@neam.se>
 * @link http://neam.se
 * @license MIT
 */
class XmlResponse extends WRestResponse
{

	public function getContentType()
	{
		return "application/xml";
	}

	public function setParams($params = array())
	{
		// An associative single-valued array is treated as a root element
		if (is_array($params) && count($params) == 1 && ($keys = array_keys($params)) && !is_numeric($keys[0])) //count($params) == 1 && ($keys = array_keys($params)) && !is_int($keys[0])
		{
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><{$keys[0]} />");
			$data = $params[$keys[0]];
			$this->_body = self::toXml($data, null, $xml);
		} else
		{
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><response />");
			$this->_body = self::toXml($params, null, $xml);
		}
		return $this;
	}

	/**
	 * The main function for converting to an XML document.
	 * Pass in a multi dimensional array and this recursively loops through and builds up an XML document.
	 *
	 * @param array $data
	 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
	 * @param SimpleXMLElement $xml - should only be used recursively
	 * @return string XML
	 */
	public static function toXML($data, $defaultKey = null, &$xml = null)
	{

		if (is_null($defaultKey)) $defaultKey = 'element';

		// turn off the compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)
			ini_set('zend.ze1_compatibility_mode', 0);

		// loop through the data passed in.
		foreach ($data as $key => $value)
		{

			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				$numeric = 1;
				$key = $defaultKey;
			}

			// delete any characters not allowed in XML element names
			$key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

			if (is_object($value))
			{
				$value = get_object_vars($value);
			}

			// if there is another array found recursively call this function
			if (is_array($value))
			{
				$node = self::is_assoc($value) || $numeric ? $xml->addChild($key) : $xml;

				// a recursive call.
				if ($numeric)
					$key = 'anon';
				self::toXml($value, $key, $node);
			} else
			{

				// add a single node.
				$value = htmlspecialchars($value);
				$xml->addChild($key, $value);
			}
		}

		// pass back as XML
		//return $xml->asXML();
		//
		// if you want the XML to be formatted, use the below part instead to return the XML
		$doc = new DOMDocument('1.0');
		$doc->preserveWhiteSpace = false;
		$doc->loadXML($xml->asXML());
		$doc->formatOutput = true;
		return $doc->saveXML();
	}

	/**
	 * Convert an XML document to a multi dimensional array
	 * Pass in an XML document (or SimpleXMLElement object) and this recursively loops through and builds a representative array
	 *
	 * @param string $xml - XML document - can optionally be a SimpleXMLElement object
	 * @return array ARRAY
	 */
	public static function toArray($xml)
	{
		if (is_string($xml))
			$xml = new SimpleXMLElement($xml);
		$children = $xml->children();
		if (!$children)
			return (string) $xml;
		$arr = array();
		foreach ($children as $key => $node)
		{
			$node = ArrayToXML::toArray($node);

			// support for 'anon' non-associative arrays
			if ($key == 'anon')
				$key = count($arr);

			// if the node is already set, put it into an array
			if (isset($arr[$key]))
			{
				if (!is_array($arr[$key]) || $arr[$key][0] == null)
					$arr[$key] = array($arr[$key]);
				$arr[$key][] = $node;
			} else
			{
				$arr[$key] = $node;
			}
		}
		return $arr;
	}

	// determine if a variable is an associative array
	public static function is_assoc($array)
	{
		return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
	}

}

