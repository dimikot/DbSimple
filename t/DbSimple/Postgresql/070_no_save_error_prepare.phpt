--TEST--
PostgreSQL: do not save errorous prepared statements

--FILE--
<?php
require_once dirname(__FILE__) . '/../init.php';

$LAST_ERROR = null;
function errorSaver($msg, $error)
{
	global $LAST_ERROR;
	$LAST_ERROR = $error;
}

function main(&$DB)
{
	global $LAST_ERROR;
	$query = "oshibka";
	$DB->setErrorHandler("errorSaver");
	$DB->query($query);
	$msg1 = $LAST_ERROR["message"];
	$DB->query($query);
	$msg2 = $LAST_ERROR["message"];
	if (false !== strpos($msg1, $query) && false !== strpos($msg2, $query)) echo "OK\n";
	else echo "Different errors are responded from the same errorous queries:\n$msg1\n$msg2\n";
}
?>


--SKIPIF--
<?php
if (!is_callable('pg_connect')) print('skip pgsql extension not loaded');
?>


--EXPECT--
Query: 'oshibka'
Query: 'oshibka'
OK

