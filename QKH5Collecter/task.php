<?php

if (!isset($_remoteBasePath)) {
	exit("error: missing \"\$_remoteBasePath\"");
}

if (!isset($_targetDirName)) {
	exit("error: missing \"\$_targetDirName\"");
}

if (isEmpty($_targetDirName)) {
	exit("error: \"\$_targetDirName\" is empty!!\r\n");
}





define('REMOTE_BASE', $_remoteBasePath. '/' . $_targetDirName . '/');
define('CLIENT_DIRNAME', strtolower($_targetDirName));
define('WHITESPACE_ENCODE', '%20');
define('MANIFEST_REG', '/<html[^>]*manifest\s*=\s*\"(\w+\.manifest)\"[^>]*>/i');

$ACCEPT_FILEEXTS = explode(
	'|',
	'htm|html|js|css|gif|png|jpg|jpeg|bmp|webm|mp3|wmv|mid|ogg'
);

$MANIFEST_FILE = 'cache.manifest';





echo "start \"" . REMOTE_BASE . "\"...\r\n";


// download index.html
downloads( array($indexFile => "index.html"), false, 'getManifestFile' );

// download Application Cache File
downloads( array($MANIFEST_FILE), false, 'contentFix' );

// download apple pngs



// download files in cache.manifest
$dirs = array();
$files = file(CLIENT_DIRNAME . '/' . $MANIFEST_FILE);
$pathinfo = null;
foreach ($files as $index => $file) {
	$file = preg_replace( '[\r|\n]', '', trim($file) );
	$pathinfo = pathinfo($file);

	if ( isset($pathinfo['extension']) &&
		in_array($pathinfo['extension'], $ACCEPT_FILEEXTS) ) {

		if (!file_exists(CLIENT_DIRNAME . '/' . $file)) {
			$dirs = split('/', CLIENT_DIRNAME . '/' . $file);
			if (count($dirs) > 0) {
				downloads( array($file) , false, 'contentFix');
			}
		} else {
			echo "skip -> `" . $file . "` exists\r\n";
		}
	} else {
		echo "skip -> `" . $file . "` no-accept ext\r\n";
	}
}

echo "finish!!\r\n";





function downloads($files, $override = false, $callback = null) {

	$_data = "";

	foreach ($files as $_from => $_to) {

		// validate
		if ( isEmpty($_from) || isEmpty($_to) ) {
			continue;
		}

		// task
		echo "download \"" . $_to . "\" ...";

		// reset
		$_from = REMOTE_BASE . (is_string($_from) ? $_from : $_to);
		$_to = CLIENT_DIRNAME . '/' . $_to;

		// check
		if ( file_exists($_to) && $override !== true ) {
			echo "exists\r\n";
			continue;
		}

		mkdirs( dirname($_to) );

		if ( !($_data = file_get_contents( str_replace(' ', WHITESPACE_ENCODE, $_from) )) ) {
			echo "Empty\r\n";
			break;
		}

		echo "OK\r\n";

		if ( is_string($callback) && function_exists($callback) ) {
			$_data = call_user_func($callback, $_from, $_to, $_data);
		}

		file_put_contents( str_replace(WHITESPACE_ENCODE, ' ', $_to), $_data );

	}

}


function contentFix($from, $to, $data) {
	global $wordReplaceMap, $manifestReplaceMap, $htmlReplaceMap;

	if (stripos($to, '.manifest') !== false) {
		$data = str_replace($manifestReplaceMap[0], $manifestReplaceMap[1], $data, $count);
		echo "fixed url(" . $count . ")\r\n";
		return $data;
	} else if (stripos($to, '.htm') !== false) {
		$data = preg_replace($htmlReplaceMap[0], $htmlReplaceMap[1], $data, -1, $count);
		echo "fixed html(" . $count . ")\r\n";
		return $data;
	} else if (stripos($to, '.js') !== false) {
		$data = str_replace($wordReplaceMap[0], $wordReplaceMap[1], $data, $count);
		echo "fixed word(" . $count . ")\r\n";
		return $data;
	}

	return $data;
}

// filter & get filename of APP CACHE
function getManifestFile($from, $to, $data) {
	global $MANIFEST_FILE;

	preg_match(MANIFEST_REG, $data, $matchs);

	if ( count($matchs) > 0 ) {
		$MANIFEST_FILE = $matchs[1];
	}

	return contentFix($from, $to, $data);
}


function isEmpty($var) {
	return is_null($var) || $var === "" || (!is_numeric($var) && empty($var));
}

function mkdirs($dir) { 
	if ( !is_dir($dir) ) { 
		if ( !mkdirs( dirname($dir) ) ) return false;
		if ( !mkdir($dir, 0777) ) return false;
	}
	return true; 
}

?>