<?php
class wfe_signal
{
	private static $_signal_collection = array();
	
	private $_signal_name;
	private $_upstream_params;
	private $_upstream_methods;
	
	public static function get_clean_signal_collection()
	{
		$rVal = self::$_signal_collection;
		self::$_signal_collection = array();
		return $rVal;
	}
	
	public function __construct($signal_name = 'ROOT', $params = array(), $methods = array())
	{
		$this->_signal_name = $signal_name;
		$this->_upstream_params = $params;
		$this->_upstream_methods = $methods;
		array_push(self::$_signal_collection, $this);
	}
	
	public function get_name()
	{
		return $this->_signal_name;
	}
	
	public function __get($name)
	{
		return $this->_upstream_params[$name];
	}
	
	public function __call($name, $arguments)
	{
		return call_user_func_array($this->_upstream_methods[$name], $arguments);
	}
}
