<?php
define('ROOT_PATH', dirname(dirname(__FILE__)).'/');
define('LIB_PATH',ROOT_PATH.'lib/');
define('WORKFLOW_PATH',ROOT_PATH.'workflow/');

$GLOBALS['wfe_register'] = array('workflow_event');
include_once(LIB_PATH.'wfe.php');
