<?php

$_remoteBasePath = 'http://www.yi588.com/h5Game';
$_targetDirName = isset($argv) && count($argv) > 1 ? $argv[1] : "";

$indexFile = 'index.html';

$wordReplaceMap = array(
	array('http://www.yi588.com'),
	array('http://m.7k7k.com/categories_1_24_4_0.html'),
);

$manifestReplaceMap = array(
	array(
		$_remoteBasePath. '/' . $_targetDirName . '/'
	),
	array(
		''
	),
);

$htmlReplaceMap = array(
	array(
		'/http\:\/\/www\.yi588\.com/i',
		'/<script[^>]*>(?:\s+)var\s+_bdhmProtocol(.|\n)*?(?=<\/script>)<\/script>/i',
	),
	array(
		'http://m.7k7k.com/categories_1_24_4_0.html',
		'',
	),
);



require_once( './task.php' );

?>