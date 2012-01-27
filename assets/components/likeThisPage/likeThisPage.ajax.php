<?php

error_reporting(E_ALL);

$path_to_index = '/var/www/modx/';

define('MODX_API_MODE',true);
require_once $path_to_index."index.php";

ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);

$ltpCorePath = $modx->getOption('core_path').'components/likeThisPage/';
define('ltp_core_path',$modx->getOption('LTP.core_path',null,$ltpCorePath));
$LTP_classPath = ltp_core_path.'model/LTP/';


require_once $LTP_classPath.'LTP.class.php';

$LTP = new LTP(&$modx);
if (!($LTP instanceof LTP)) return 'ERROR: Could not initialise LTP class'."\n";


$uuID = $LTP->uuID;
$resID = $_GET["resID"];



$LTP->likeThisPage($resID);
die("DONE");


?>