<?php
class wfe_handle_node
{
	private $_signal_text;
	private $_priority;
	private $_method;
	public function __construct($signal_text = 'ROOT', $priority = 50, $method = NULL)
	{
		$this->_signal_text = $signal_text;
		$this->_priority = $priority;
		$this->_method = $method;
	}
	
	public function get_signal_text()
	{
		return $this->_signal_text;
	}
	
	public function get_priority()
	{
		return $this->_priority;
	}
	
	public function get_method()
	{
		return $this->_method;
	}
}