<?php

$_remoteBasePath = 'http://resource.duopao.com/duopao/games/small_games/weixingame';
$_targetDirName = isset($argv) && count($argv) > 1 ? $argv[1] : "";

$indexFile = (isset($argv) && count($argv) > 2 ? $argv[2] : $_targetDirName . '.htm');
// $cacheFile = (isset($argv) && count($argv) > 3 ? $argv[3] : 'offline') . '.manifest';

$wordReplaceMap = array(
	array(
		'http://resource.duopao.com/duopao/games/small_games/advplus/ad.htm',
		'../../advplus/ad.htm',
		'"../../"+this.version+"/res/LeiYooResLoadImg.png"',
	),
	array(
		'http://m.7k7k.com/categories_1_24_4_0.html',
		'http://m.7k7k.com/categories_1_24_4_0.html',
		'"about:blank"',
	),
);

$manifestReplaceMap = array(
	array(
		$indexFile,
		$_remoteBasePath. '/' . $_targetDirName . '/',
		'../../1.5/lib/'
		// ' ',
	),
	array(
		'index.htm',
		'',
		'js/',
		// '%20',
	),
);

$htmlReplaceMap = array(
	array(
		'/http\:\/\/resource\.duopao\.com\/duopao\/games\/small\_games\/advplus\/ad\.htm/i',
		'/<script[^>]*>(?:\s+)var\s+_bdhmProtocol(.|\n)*?(?=<\/script>)<\/script>/i'
	),
	array(
		'http://m.7k7k.com/categories_1_24_4_0.html',
		'',
	),
);


require_once( './task.php' );

?>