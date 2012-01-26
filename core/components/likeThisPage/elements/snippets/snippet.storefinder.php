<?php
/**
 * @package storefinder
 */
$base_path = $modx->getOption('LTP.core_path');


// Add package to xPDO
$pkg = $modx->addPackage('likeThisPage',$base_path.'model/','modx_');


// Add a row
$store = $modx->newObject('LikeThisPage');


$store->fromArray(array(
    'timestamp' => time(),
    'uuid' => 234234,
    'resid' => 233,
));
$store->save();


// Test
$stores = $modx->getCollection('LikeThisPage');
echo 'Total: '.count($stores);




return '<hr><hr>';
