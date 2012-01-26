<?php

$ltpCorePath = $modx->getOption('core_path').'components/likeThisPage/';
define('ltp_core_path',$modx->getOption('LTP.core_path',null,$ltpCorePath));
$LTP_classPath = ltp_core_path.'model/modLikeThisPage/';

require_once $LTP_classPath.'modLikeThisPage.class.php';
$LTP = new modLikeThisPage(&$modx);
if (!($LTP instanceof modLikeThisPage)) return 'ERROR: Could not initialise LTP class'."\n";


// Get this page's resource ID
$resID = $LTP->getCurrentResourceID();


?>