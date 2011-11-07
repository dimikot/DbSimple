<?php
$stack = debug_backtrace();
chdir(dirname(realpath($stack[0]['file'])));

header("Content-type: text/plain");
include_once dirname(__FILE__) . "/../../lib/config.php";
ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.dirname(__FILE__).'/..'); // for Cache_Lite
include_once "DbSimple/Generic.php"; 

if (!is_array(@$DSN)) {
	$DSN = array();
	$dsnFile = "dsn.txt";
	$dsnOwn = trim(@join("", file($dsnFile)));
	if (!$dsnOwn) die("Current directory must contain $dsnFile file!");
	if ($dsnOwn == '*' || preg_match('/^\w+$/', $dsnOwn)) {
		$dir = dirname(__FILE__);
		$d = opendir($dir);
		while (false !== ($e = readdir($d))) {
			$full = realpath("$dir/$e");
			if ($e == "." || $e == ".." || !is_dir($full) || $full == realpath(getcwd())) continue;
			if ($dsnOwn != '*' && strtolower($e) != strtolower($dsnOwn)) continue;
			$dsn = trim(@join("", file("$full/$dsnFile")));
			if ($dsn) $DSN = array_merge($DSN, preg_split('/\s+/s', $dsn));
		}
	} else {
		$DSN[] = $dsnOwn;
	}
}

foreach ($DSN as $dsn) {
    $DB =& DbSimple_Generic::connect($dsn);
    $DB->setLogger('queryLogger');
    $DB->setErrorHandler('errorHandler');
    main($DB);
}

function queryLogger(&$DB, $query)
{
	if (preg_match('/^\s*--\s+(\d|error)/', $query)) return;
	printr($query, "Query");
}

function errorHandler($msg, $error)
{
	if (!error_reporting()) return;
	printr($error['message'], "Error");
}

// Debug human-readable output of any variable.
function printr($value, $comment=null)
{
    if ($comment !== null) echo "$comment: ";
    var_export($value);
    echo "\n";
}
?>