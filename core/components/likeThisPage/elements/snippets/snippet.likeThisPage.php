<?php
global $modx;


$ltpCorePath = $modx->getOption('core_path').'components/likeThisPage/';
define('ltp_core_path',$modx->getOption('LTP.core_path',null,$ltpCorePath));
$LTP_classPath = ltp_core_path.'model/LTP/';

require_once $LTP_classPath.'LTP.class.php';
$LTP = new LTP(&$modx);
if (!($LTP instanceof LTP)) return 'ERROR: Could not initialise LTP class'."\n";



$cookieScript = '';

/* setup default properties */
$tpl = $modx->getOption('tpl',$scriptProperties,'output');
$button = $modx->getOption('button',$scriptProperties,'Like this Article');
$already = $modx->getOption('button',$scriptProperties,'You like this article');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');


// Get this page's resource ID
$resID = $LTP->getCurrentResourceID();




// Get this users uniqueID (if they done have one, create it)
$uuID  = $LTP->getUniqueUserID();
if(isset($uuID->script)){
	$cookieScript = $uuID->script;
};
$uuID = $uuID->ID;

// Does the user like this already?
$userLikesThis = $LTP->doILikeThisPage();


// Count how many people have liked this page
$likes = $LTP->getTotalLikesForThisPage();




// Switch button based on whether user likes already
if( $userLikesThis ){
	if( substr($already,0,4) == "img:" ){
		$btn .= '<img src="'.substr($already,4).'" alt="Like this Article" />';
	} else {
		$btn .= $already;
	};
} else {
	// Prepare button content
	$btn = '<a href="#" onclick="alert(\'like this\');">';
	if( substr($button,0,4) == "img:" ){
		$btn .= '<img src="'.substr($button,4).'" alt="Like this Article" />';
	} else {
		$btn .= $button;
	};
	$btn.= '</a>';
};


$properties = array(
		'total' => $likes,
		'liked' => false,
		'button' => $btn
	);



// Parse the chunk
$out = $LTP->getChunk($tpl,$properties);


// Load the javascripts
$jsFile .= '<script type="text/javascript" src="'.$LTP->jsFile.'"></script>'."\n".'<script>'."\n".'onLTP = function(){'."\n".$LTP->javascript."\n".'};'."\n".'</script>'."\n".'';



 
return $jsFile.$out;

?>
