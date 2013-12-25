<?php
include_once('wfe_handle_node.php');
include_once('wfe_signal.php');
include_once('workflow_base.php');
class wfe
{
	private $_workflow_list;
	
	public function __construct()
	{
		$this->_workflow_list = array();
		spl_autoload_register('wfe::_spl_autoload_workflow');
	}
	
	public function register_workflow($workflow_name)
	{
		$this->_workflow_list[] = $workflow_name;
	}
	
	public function handle()
	{
		$this->_workflow_list = array_unique($this->_workflow_list);
		$handle_list = array();
		foreach($this->_workflow_list as $workflow){
			$wf_handle_list = call_user_func_array("{$workflow}::get_priority",array());
			foreach($wf_handle_list as $li){
				if($li instanceof wfe_handle_node){
					$signal_text = $li->get_signal_text();
					if(!isset($handle_list[$signal_text])){
						$handle_list[$signal_text] = array();
					}
					$handle_list[$signal_text][] = $li;
				}else{
					throw new Exception("Workflow {$workflow} return an node, that is not instanceof wfe_handle_node", 1);
				}
			}
		}
		
		foreach($handle_list as &$handle_per_signal){
			usort($handle_per_signal, array("wfe", "_cmp_priority"));
		}
		
		$signal_list = wfe_signal::get_clean_signal_collection();
		do{
			foreach($signal_list as $signal){
				$sognal_text = $signal->get_name();
				if(isset($handle_list[$sognal_text])){
					$call_list = $handle_list[$sognal_text];
					foreach($call_list as $handle_node){
						$method = $handle_node->get_method();
						call_user_func_array($method, array($signal));
					}
				}
			}
			$signal_list = wfe_signal::get_clean_signal_collection();
		}while(count($signal_list));
		
	}
	
	public static function _cmp_priority(wfe_handle_node $a,wfe_handle_node $b)
	{
		$ap = $a->get_priority();
		$bp = $b->get_priority();
		if($ap == $bp){
			return 0;
		}
		return ($ap>$bp)?+1:-1;
	}
	
	public static function _spl_autoload_workflow($class_name)
	{
		if(substr($class_name, 0,9)=='workflow_'){
			$filename = substr($class_name,9);
			if(preg_match('#^[a-z0-9]+$#', $filename)){
				include_once(WORKFLOW_PATH.'workflow_'.$filename.'.php');
			}
		}
	}
}

$_wfe = new wfe();
foreach($GLOBALS['wfe_register'] as $workflow_name){
	$_wfe->register_workflow($workflow_name);
}


register_shutdown_function(array($_wfe,'handle'));
