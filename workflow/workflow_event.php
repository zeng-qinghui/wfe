<?php
class workflow_event extends workflow_base
{
	static function get_priority()
	{
		return array(
			new wfe_handle_node('TEST1', 50, 'workflow_event::test1_action'),
			new wfe_handle_node('TEST2', 50, 'workflow_event::test2_1_action'),
			new wfe_handle_node('TEST2', 30, 'workflow_event::test2_2_action')
		);
	}
	
	public static function test1_action($signal)
	{
		new wfe_signal('TEST2');
		echo 'Test1_OK!<br>';
	}
	
	public static function test2_1_action($signal)
	{
		echo 'Test2_1_OK!<br>';
	}
	
	public static function test2_2_action($signal)
	{
		echo 'Test2_2_OK!<br>';
	}
}
