<?php class LTP {
    public $modx;
    public $config = array();
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
 
        $basePath = $this->modx->getOption('LTP.core_path',$config,$this->modx->getOption('core_path').'components/likeThisPage/');
        $assetsUrl = $this->modx->getOption('LTP.assets_url',$config,$this->modx->getOption('assets_url').'components/likeThisPage/');
        $this->config = array_merge(array(
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath.'model/',
            'processorsPath' => $basePath.'processors/',
            'templatesPath' => $basePath.'templates/',
            'chunksPath' => $basePath.'elements/chunks/',
            'jsUrl' => $assetsUrl.'js/',
            'cssUrl' => $assetsUrl.'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl.'connector.php',
        ),$config);
		
		$this->alan = 'GOD';
		
		
		$this->cookieName = 'modx-likeThisPage-uuid';
		$this->keyLength = 32;
		$this->jsFile = $this->config['assetsUrl'].'js/likeThisPage.functions.js';
		$this->ObjectName = 'LikeThisPage';
		$this->uuID = $this->getUniqueUserID();
		
		$this->javascript = "LTP.ajax_url = '".$this->config['assetsUrl']."likeThisPage.ajax.php';"."\n";
		$this->javascript.= "LTP.resID = ".$this->getCurrentResourceID().";"."\n";
		$this->javascript.= "if(document.getElementById('LTP_likeThisPageButton')!= null){document.getElementById('LTP_likeThisPageButton').onclick=function(){return LTP.likeThisPage();}};"."\n";
			
		// Add xPDO package
		$modx->addPackage('likeThisPage',$basePath.'model/');
		
		


    }
	
	
	
	
	
	// Get current resource ID--------------------------------------------------------------
	function getCurrentResourceID(){
		if(isset($this->modx->resource)){
			return $this->modx->resource->get('id');
		};
		return false;
	}//	
	
	// Get unique user id ------------------------------------------------------------------
	function getUniqueUserID(){
		// Check if the user already has a cookie
		$exists = ($this->findCookie() != false);
		
		if(!$exists){
			// Generate a new unique key
			$uuid = $this->generateNewUniqueKey();
			$this->javascript.= 'addCookie("'.$this->cookieName.'","'.$uuid.'",9999);';
		} else {
			$uuid = $this->findCookie();
		};
		return $uuid;	
	}//	
	
	
	
	// Grab the UUid from cookie ------------------------------------------------------------------
	function findCookie(){
		$cook = $_SERVER["HTTP_COOKIE"];
		$bits = explode(';',$cook);
		foreach($bits as $cookie){
			if( preg_match("/".$this->cookieName."/",$cookie) == 1 ){
				$half = explode('=',$cookie);
				return $half[1];
			};
		};
		return false;
	}//
	
	// Generate a new uuid key ------------------------------------------------------------------
	function generateNewUniqueKey(){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz{[}]~#@:;?/.,';
		$string = '';    
		for ($p = 0; $p < $this->keyLength; $p++) {
		    $string .= $characters[mt_rand(0, strlen($characters))];
		}
		return $string;
	}//
	
	
	
	// Count likes for this page  ------------------------------------------------------------------
	function getTotalLikesForThisPage(){
		// Query the db
		$boxes = $this->modx->getCollection('LikeThisPage', array(
		  'resid' => $this->getCurrentResourceID()
		));
		return count($boxes);
	}//
	
	
	// Does user like this page already?  ------------------------------------------------------------------
	function doILikeThisPage(){
		$likes = $this->modx->getCollection('LikeThisPage', array(
		  'resid' => $this->getCurrentResourceID(),
		  'uuid' => $this->uuID
		));
		
		return ( count($likes) > 0 ) ? true : false;
	}//
	
	
	
	// Add a like to the db  ------------------------------------------------------------------
	function likeThisPage( $resid = -1 ){
		if($resid == -1){ $resid = $this->getCurrentResourceID(); };
		
		$uuid = $this->uuID;
		
		// Check if this person already likes (prevent dupes)
		$liked = $this->modx->getCollection('LikeThisPage', array(
		  'resid' => $resid,
		  'uuid' => $uuid
		));
		if(count($liked) > 0){ return; };
		
		echo $resid."<br />";
		
		$store = $this->modx->newObject('LikeThisPage');
		$store->fromArray(array(
			'timestamp' => time(),
			'uuid' => $uuid,
			'resid' => (int)$resid	
		));
		$store->save();
	}//
	
	
	
	public function getChunk($name,$properties = array()) {
    $chunk = null;
    if (!isset($this->chunks[$name])) {
        $chunk = $this->_getTplChunk($name);
        if (empty($chunk)) {
            $chunk = $this->modx->getObject('modChunk',array('name' => $name));
            if ($chunk == false) return false;
        }
        $this->chunks[$name] = $chunk->getContent();
    } else {
        $o = $this->chunks[$name];
        $chunk = $this->modx->newObject('modChunk');
        $chunk->setContent($o);
    }
    $chunk->setCacheable(false);
    return $chunk->process($properties);
}
 
private function _getTplChunk($name,$postfix = '.chunk.tpl') {
    $chunk = false;
    $f = $this->config['chunksPath'].strtolower($name).$postfix;
    if (file_exists($f)) {
        $o = file_get_contents($f);
        $chunk = $this->modx->newObject('modChunk');
        $chunk->set('name',$name);
        $chunk->setContent($o);
    }
    return $chunk;
}
	
	
	
	
	
};// end class LTP

?>
